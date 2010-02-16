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
        if ($original) {
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
            return $query->row();
        } else {
            return false;
        }
    }
    
    /**
     * Get the latest changes in all wiki pages
     */
    public function get_latest($n=1) {
        $query = $this->db->select('item, created, description')
                          ->order_by('created', 'desc')
                          ->get('wiki_pages');
        if ($query->num_rows() == 0) {
            return array();
        }
        $return = array();
        foreach ($query->result() as $r) {
            if (count($return) == $n) { break; /* we've got enough */ }
            if (array_key_exists($r->item, $return)) { continue; /* We already counted this page */ }
            $return[$r->item] = array($r->created, $r->description);
        }
        return $return;
    }
    
    /**
     * Get all wiki pages (in-/excluding discussion pages)
     */
    public function get_all($really_all=False) {
        $this->db->select('item')
                 ->order_by('item asc');
        if (! $really_all) {
            $this->db->not_like('item', 'Discussion:%');
        } elseif ($really_all == 'discussion') {
            $this->db->like('item', 'Discussion:%');
        }
        $query = $this->db->get('wiki_active');
        $result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $p) {
                $result[] = $p['item'];
            }
        }
        return $result;
    }
    
    /**
     * Edit a wiki item
     */
    public function set($item, $content, $description) {
        $userlogin = getUserLogin();
        if ($xcontent = $this->_sanitize($content)) {
            $id = $this->_get_current($item);
            $query = $this->db->insert('wiki_pages', array(
                'item' => $item,
                'content' => $xcontent,
                'original_content' => $content,
                'description' => $description,
                'editor' => $userlogin->userId(),
                'replaces' => $id,
            ));
            $content_id = $this->db->insert_id();
            if ($id) {
                $this->db->where('item', $item)
                         ->update('wiki_active', array('id' => $content_id));
            } else {
                $this->db->insert('wiki_active',
                                  array('id' => $content_id, 'item' => $item));
            }
        } else {
            return false;
        }
    }
    
    /**
     * Revert one or several edits
     */
    public function revert($item, $steps=1) {
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
                if (! $next_id) { return false; /* don't remove the very first entry */ }
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
            if (strpos($xcontent, '\ref{') !== False) {
                function _sanitize_get_ref ($m) {
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