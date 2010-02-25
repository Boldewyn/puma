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
            return false;
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
            $xcontent = preg_replace('#<script.*?/script>#i', '', $content);
            $xcontent = str_replace('<', $mask, $xcontent);
            $xcontent = preg_replace('#'.$mask.'(/?(?:a(?:bbr|cronym)?|br?|i(?:mg)?'.
                                     '|em|strong|p|blockquote|q|div|s(?:ub|up|pan|amp)'.
                                     '|[oud]l|li|dt|dd|pre|h[r1-6]|var'.
                                     '|t(?:able|r|body|head|foot|h|d)))(>|\s)#i',
                                     '<$1$2', $xcontent);
            $xcontent = preg_replace('#\son([a-z]+)=(["\']).*?\2#i', '', $xcontent);
            $xcontent = preg_replace('#\shref=(["\'])javascript:.*?\1#i', '', $xcontent);
            $xcontent = str_replace($mask, '&lt;', $xcontent);
            $xcontent = preg_replace('/\[\[(.+?)\]\]/', '<a class="interwiki" href="'.base_url().'wiki/$1">$1</a>', $xcontent);
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
    
}


//__END__