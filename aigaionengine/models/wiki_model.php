<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 */
class Wiki_model extends Model {

    public function __construct() {
        parent::Model();
    }
    
    public function get($item, $original = False) {
        $id = $this->_get_current($item);
        if ($id) {
            $query = $this->db->select('content, original_content')
                          ->from('wiki_pages')
                          ->where('id', $id)
                          ->get();
            if ($query->num_rows() == 0) {
                return false;
            }
            if ($original) {
                return $query->row()->original_content;
            } else {
                return $query->row()->content;
            }
        } else {
            return false;
        }
    }
    
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
    
    public function set($item) {
        $userlogin = getUserLogin();
        if ($content = $this->_sanitize($this->input->post('content'))) {
            $id = $this->_get_current($item);
            $query = $this->db->insert('wiki_pages', array(
                'item' => $item,
                'content' => $content,
                'original_content' => $this->input->post('content'),
                'description' => $this->input->post('description'),
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
    
    public function get_history($item) {
        $query = $this->db->where('item', $item)
                          ->order_by('created', 'desc')
                          ->get('wiki_pages');
        return $query->result();
    }
    
    public function preview($content) {
        return $this->_sanitize($content);
    }
    
    protected function _sanitize($content) {
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