<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Wiki_model extends Model {

    public function __construct() {
        parent::Model();
    }
    
    /**
     * Get the content of a wiki item
     */
    public function get($item, $original = False) {
        $id = $this->_get_current($item);
        if ($id === False) {
            return '';
        } elseif ($original) {
            return $this->get_version($id)->original_content;
        } else {
            return $this->get_version($id)->content;
        }
    }
    
    /**
     * Get all info about a specific version of an item
     */
    public function get_version($id) {
        if ($id) {
            $query = $this->db->where('id', $id)
                              ->get('wiki_pages');
            if ($query->num_rows() == 0) {
                return false;
            }
            $r = $query->row();
            if ($this->_internal_is_allowed($r->id, 'read', $r->editor, $r->read_access_level)) {
                return $r;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    
    /**
     * Get the latest changes in all wiki pages
     */
    public function get_latest($n=1) {
        $query = $this->db->select('id, item, created, description, editor, read_access_level')
                          ->order_by('created', 'desc')
                          ->get('wiki_pages');
        if ($query->num_rows() == 0) {
            return array();
        }
        $return = array();
        foreach ($query->result() as $r) {
            if (count($return) == $n) { break; /* we've got enough */ }
            if (array_key_exists($r->item, $return) ||
                ! $this->_internal_is_allowed($r->id, 'read', $r->editor, $r->read_access_level)) {
                continue; /* We already counted this page, or it isn't allowed */
            }
            $return[$r->item] = array($r->created, $r->description);
        }
        return $return;
    }
    
    /**
     * Get all wiki pages (in-/excluding discussion pages)
     */
    public function get_all($really_all=False) {
        $this->db->select('id, item')
                 ->order_by('item asc');
        if (! $really_all) {
            $this->db->not_like('item', 'Discussion:%');
        } elseif ($really_all == 'discussion') {
            $this->db->like('item', 'Discussion:%');
        }
        $query = $this->db->get('wiki_active');
        $result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $r) {
                if ($this->is_allowed($r->id, 'read')) {
                    $result[] = $r->item;
                }
            }
        }
        return $result;
    }
    
    /**
     * Edit a wiki item
     */
    public function set($item, $content, $description, $read_access_level='public', $edit_access_level='public') {
        restrict_to_right('wiki_edit', __('Edit wiki'), '/wiki');
        if ($xcontent = $this->_sanitize($content)) {
            $id = $this->_get_current($item);
            if ($this->is_allowed($id, 'edit')) {
                $userlogin = getUserLogin();
                $query = $this->db->insert('wiki_pages', array(
                    'item' => $item,
                    'content' => $xcontent,
                    'original_content' => $content,
                    'description' => $description,
                    'editor' => $userlogin->userId(),
                    'replaces' => $id,
                    'read_access_level' => $read_access_level,
                    'edit_access_level' => $edit_access_level,
                ));
                $content_id = $this->db->insert_id();
                if ($id) {
                    $this->db->where('item', $item)
                             ->update('wiki_active', array('id' => $content_id));
                } else {
                    $this->db->insert('wiki_active',
                                      array('id' => $content_id, 'item' => $item));
                }
                return $content_id;
            }
        }
        return False;
    }
    
    /**
     * Revert one or several edits
     */
    public function revert($item, $steps=1) {
        restrict_to_right('wiki_edit', __('Edit wiki'), '/wiki');
        while ($steps > 0) {
            $steps--;
            $id = $this->_get_current($item);
            $query = $this->db->select('replaces')
                          ->from('wiki_pages')
                          ->where('id', $id)
                          ->get();
            if ($query->num_rows() == 0) {
                return false;
            } else {
                $next_id = $query->row()->replaces;
                if (! $next_id || ! $this->is_allowed($next_id, 'edit')) {
                    return false; /* don't remove the very first entry or entries without allowance */
                }
                $this->db->delete('wiki_pages', array('id' => $id));
                $this->db->where('item', $item)
                         ->update('wiki_active', array('id' => $next_id));
            }
        }
        return true;
    }
    
    /**
     * Get the history of an item
     */
    public function get_history($item) {
        restrict_to_right('wiki_edit', __('Edit wiki'), '/wiki');
        $query = $this->db->from('wiki_pages')->join('users', 'users.user_id = wiki_pages.editor')
                          ->where('item', $item)
                          ->order_by('created', 'desc')
                          ->get();
        return $query->result();
    }
    
    /**
     * Generate a preview from any content
     */
    public function preview($content) {
        return $this->_sanitize($content);
    }
    
    /**
     * check, if a user may see this item (specific to a version == ID)
     */
    public function is_allowed($id, $mode='read') {
        $query = $this->db->select($mode.'_access_level, editor')
                          ->where('id', $id)->get('wiki_pages');
        if ($query->num_rows() == 0) {
            $userlogin = getUserLogin();
            return $userlogin->hasRights('wiki_'.$mode); // wiki page doesn't exist.
        }
        $data = $query->row_array();
        return $this->_internal_is_allowed($id, $mode, $data['editor'], $data[$mode.'_access_level']);
    }
    
    /**
     * check, if a user may see this item (specific to a version == ID)
     */
    protected function _internal_is_allowed($id, $mode, $editor, $access_level) {
        $userlogin = getUserLogin();
        if ($userlogin->userId() == $editor ||
            $userlogin->hasRights($mode.'_all_override') ||
            $access_level == 'public' ||
            ($userlogin->hasRights('wiki_'.$mode) && in_array($access_level, array('public', 'private')))
            // || check for group
        ) {
            return true;
        }
        return false;
    }
    
    protected function _sanitize($content) {
        $xcontent = '';
        if ($content) {
            $mask = '___§§§WIKIMASK§§§___';
            $xcontent = preg_replace('#<script.*?/script>#i', '', $content); # No JS allowed
            $xcontent = str_replace('<', $mask, $xcontent);
            $xcontent = preg_replace('#'.$mask.'(/?(?:a(?:bbr|cronym)?|br?|i(?:mg)?'.
                                     '|em|strong|p|blockquote|q|div|s(?:ub|up|pan|amp)'.
                                     '|[oud]l|li|dt|dd|pre|h[r1-6]|var'.
                                     '|t(?:able|r|body|head|foot|h|d)))(>|\s)#i',
                                     '<$1$2', $xcontent);
            $xcontent = preg_replace('#\son([a-z]+)=(["\']).*?\2#i', '', $xcontent); # No events allowed
            $xcontent = preg_replace('#\shref=(["\'])javascript:.*?\1#i', '', $xcontent); # No javascript: links allowed
            $xcontent = str_replace($mask, '&lt;', $xcontent); # all but the above elements disallowed
            $xcontent = preg_replace('/\[\[(.+?)|(.+?)\]\]/', 
                        '<a class="interwiki" href="'.base_url().'wiki/$1">$2</a>', $xcontent); # Interwiki links w/ alt text
            $xcontent = preg_replace('/\[\[(.+?)\]\]/', 
                        '<a class="interwiki" href="'.base_url().'wiki/$1">$1</a>', $xcontent); # Interwiki links
            if (strpos($xcontent, '\ref{') !== False) { // references to publications
                function _sanitize_get_ref($m) {
                    $CI =& get_instance();
                    $pub = $CI->publication_db->getByBibtexID($m[1]);
                    if ($pub) {
                        return anchor('publications/show/'.$pub->pub_id, sprintf('[%s]', $pub->bibtex_id));
                    } else {
                        return $m[0];
                    }
                }
                $xcontent = preg_replace_callback('/\\\\ref{([^}]+)}/', '_sanitize_get_ref', $xcontent);
            }
            $xcontent = $this->_handle_latex($xcontent);
        }
        return $xcontent;
    }
    
    protected function _get_current($item) {
        $query = $this->db->select('id')
                      ->from('wiki_active')
                      ->where('item', $item)
                      ->get();
        if ($query->num_rows() == 0) {
            return false;
        }
        return $query->row()->id;
    }

    protected function _handle_latex($string) {
        $newstring = '';
        $map = array(
            '\\\\textbf{' => array('<strong>','</strong>'),
            '\\\\textit{' => array('<em>','</em>'),
            '\\\\textsc{' => array('<span style="font-variant:small-caps">','</span>'),
            '\\\\texttt{' => array('<span style="font-family:monospace">','</span>'),
            '\\\\textrm{' => array('<span style="font-weight:normal;font-style:normal;font-variant:normal">','</span>'),
            '\\\\textsf{' => array('<span style="font-family:sans-serif">','</span>'),
            '\\\\emph{' => array('<em>','</em>'),
            '\\\\underline{' => array('<span style="text-decoration:underline">','</span>'),
            '\\\\overline{' => array('<span style="text-decoration:overline">','</span>'),
            '{\\\\bf\\s+' => array('<strong>','</strong>'),
            '{\\\\em\\s+' => array('<em>','</em>'),
            '{\\\\it\\s+' => array('<em>','</em>'),
            '{\\\\sc\\s+' => array('<span style="font-variant:small-caps">','</span>'),
            '{\\\\tt\\s+' => array('<span style="font-family:monospace">','</span>'),
            '{\\\\rm\\s+' => array('<span style="font-weight:normal;font-style:normal;font-variant:normal">','</span>'),
            '{\\\\sf\\s+' => array('<span style="font-family:sans-serif">','</span>'),
            '{\\\\tiny\\s+' => array('<span style="font-size:xx-small">','</span>'),
            '{\\\\scriptsize\\s+' => array('<span style="font-size:x-small">','</span>'),
            '{\\\\footnotesize\\s+' => array('<span style="font-size:x-small">','</span>'),
            '{\\\\small\\s+' => array('<span style="font-size:small">','</span>'),
            '{\\\\normalsize\\s+' => array('<span style="font-size:normal">','</span>'),
            '{\\\\large\\s+' => array('<span style="font-size:large">','</span>'),
            '{\\\\Large\\s+' => array('<span style="font-size:x-large">','</span>'),
            '{\\\\LARGE\\s+' => array('<span style="font-size:x-large">','</span>'),
            '{\\\\huge\\s+' => array('<span style="font-size:xx-large">','</span>'),
            '{\\\\Huge\\s+' => array('<span style="font-size:xx-large">','</span>'),
        );
        $parts = preg_split('/('.join('|', array_keys($map)).'|})/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
        $level = array();
        foreach ($parts as $part) {
            if (array_key_exists($part, $map)) {
                array_unshift($level, $map[$part][1]);
                $newstring .= $map[$part][0];
            } elseif (array_key_exists(str_replace('\\', '\\\\', $part), $map)) {
                array_unshift($level, $map[str_replace('\\', '\\\\', $part)][1]);
                $newstring .= $map[str_replace('\\', '\\\\', $part)][0];
            } elseif (array_key_exists(str_replace('\\', '\\\\', rtrim($part)).'\\s+', $map)) {
                array_unshift($level, $map[str_replace('\\', '\\\\', rtrim($part)).'\\s+'][1]);
                $newstring .= $map[str_replace('\\', '\\\\', rtrim($part)).'\\s+'][0];
            } elseif ($part == '}') {
                if (count($level) == 0 || substr($newstring, -1) == '\\') {
                    array_shift($level);
                    $newstring .= '}';
                } else {
                    $newstring .= array_shift($level);
                }
            } else {
                $newstring .= $part;
            }
        }
        $pregmap = array(
            '/\\\\\'([AEIOUYaeiouy])\\s+/' => '&$1acute;',
            '/\\\\\"([AEIOUaeiouy])\\s+/' => '&$1uml;', 
            '/\\\\\`([AEIOUaeiou])\\s+/' => '&$1grave;',
            '/\\\\\^([AEIOUaeiou])\\s+/' => '&$1circ;', 
            '/\\\\\~([ANOano])\\s+/' => '&$1tilde;',
            '/\\\\\,([Cc])\\s+/' => '&$1cedil;',
            '/\\\\([aoAO][Ee])\\s+/' => '&$1lig;',
            '/\\\\(alpha|eta|nu|tau|beta|theta|xi|upsilon|gamma|iota|phi|delta|kappa|pi|'.
              'chi|epsilon|lambda|rho|psi|zeta|mu|sigma|omega|Gamma|Lambda|Sigma|Psi|Delta|'.
              'Xi|Upsilon|Omega|Theta|Pi|Phi)\\s+/' => '&$1;',
        );
        $strmap = array(
            '\\ss' => '&szlig;',
            '\\aa' => '&aring;',
            '\\AA' => '&Aring;',
            '\\o' => '&oslash;',
            '\\O' => '&Oslash;',
            '\\ldots' => '&hellip;',
            '\\leq' => '&le;',
            '\\geq' => '&ge;',
            '---' => '&mdash;',
            '``' => '&ldquo;',
            "''" => '&rdquo;',
            '\\qquad' => '&emsp;&emsp;',
            '\\quad' => '&emsp;',
            '\\ ' => '&nbsp;',
            '\\,' => '&#8198;',
            '\\-' => '&shy;',
            '\\P' => '&para;',
            '\\dag' => '&;',
            '\\ddag' => '&;',
            '\\textbar' => '|',
            '\\textgreater' => '&gt;',
            '\\textless' => '&lt;',
            '\\textemdash' => '&mdash;',
            '\\textendash' => '&ndash;',
            '\\texttrademark' => '&trade;',
            '\\textregistered' => '&reg;',
            '\\copyright' => '&copy;',
            '\\textexclamdown' => '&iexcl;',
            '\\textquestiondown' => '&iquest;',
            '\\S' => '&sect;',
            '\\%' => '%',
            '\\$' => '$',
            '\\/' => '', // we don't need the italic correction...
            '\\backslash' => '\\',
        );
        foreach ($pregmap as $pattern => $replacement) {
            $newstring = preg_replace($pattern, $replacement, $newstring);
        }
        foreach ($strmap as $pattern => $replacement) {
            $newstring = str_replace($pattern, $replacement, $newstring);
        }
        $newstring = preg_replace('/([^!])--/', '$1&ndash;', $newstring);
        $newstring = preg_replace('/([^\\\\])~/', '$1&nbsp;', $newstring);
        $newstring = str_replace('\\&', '&amp;', $newstring);
        return $newstring;
    }
    
}


//__END__
