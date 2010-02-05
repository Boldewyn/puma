<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function option_get($key, $user_id=Null, $like=False) {
    if ($user_id === Null) {
        $userlogin = getUserLogin();
        $user_id = $userlogin->userId();
    }
    $CI =& get_instance();
    $CI->db->from('useroptions')
                      ->where('user_id', $user_id);
    if ($like) {
        $CI->db->select(array('key', 'value'))->like('key', $key);
    } else {
        $CI->db->select('value')->where('key', $key);
    }
    $query = $CI->db->get();
    if ($like) {
        $option = $query->result_array();
        if (count($option) > 0) {
            $o = array();
            foreach($option as $op) {
                $o[$op['key']] = $op['value'];
            }
            return $o;
        } else {
            return array();
        }
    } else {
        $option = $query->row();
        return $option? $option->value : Null;
    }
}

function option_get_like($key, $user_id=Null) {
    return option_get($key, $user_id, True);
}

function option_set($key, $value=Null, $user_id=Null) {
    if ($user_id === Null) {
        $userlogin = getUserLogin();
        $user_id = $userlogin->userId();
    }
    $CI =& get_instance();
    $option = option_get($key, $user_id);
    $constraints = array('key' => $key, 'user_id' => $user_id);
    if ($value != Null) {
        $CI->db->set('value', $value);
        if ($option == Null) {
            $CI->db->set($constraints)->insert('useroptions');
        } else {
            $CI->db->where($constraints)->update('useroptions');
        }
    } elseif ($option != Null) {
        $CI->db->where($constraints)->delete('useroptions');
    }
    return $option;
}

//__END__