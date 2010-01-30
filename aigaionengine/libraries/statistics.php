<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Statistics {

    public function __construct() {
    }

    public function get($where="1=1") {
        $userlogin = getUserLogin();
        if ($userlogin->hasRights ('database_manage')) {
            $query = $this->db->where($where)->get('statistics');
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function set() {
        $that =& get_instance();
        if ($that->uri->segment (1, false) == 'stats') {
            // statistics shall not be tracked (even the reading of statistic data)
            return false;
        } else {
            $userlogin = getUserLogin();
            if ($userlogin->isAnonymous()) {
                $user = "-";
            } else {
                $user = $userlogin->loginName();
            }
            $that->db->insert('statistics', array(
                'user' => $user,
                'speaking' => $that->userlanguage->get(),
                'with' => $_SERVER['HTTP_USER_AGENT'],
                'wants' => $_SERVER['REQUEST_URI'],
            ));
        }
    }
  
}

//__END__