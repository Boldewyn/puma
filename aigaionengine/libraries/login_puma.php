<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login management for Puma.Phi
 */
class Login_puma {


    /**
     * Our private, secret key
     */
    private $_key;


    /**
     *
     */
    public function __construct () {
        $this->_key = pack("h*", '64283871BCDF24D590A983D674D8CD9C3E5EB90290BBEA8BE1B40FCB66A1DA3D');
    }


    /** Returns an associative array containing the login name of the user and all groups that this user
    belongs to... (the same names that are stored in aigaion in the abbreviation) */
    public function getLoginInfo() {
        $CI = &get_instance();

        $login_tries = $CI->latesession->get('__login_tries')? $CI->latesession->get('__login_tries')+1 : 1;
        $CI->latesession->set('__login_tries', $login_tries);
        $GET = $this->_parse_get();

        if (isset($GET['k']) && $CI->latesession->get('__login_key') && $GET['k'] == md5($this->encrypt_secret($CI->latesession->get('__login_key'), $this->_key))) {
            unset($_SESSION['__login_key']);
            $data = $this->check_login_data($GET);
            if (is_array($data)) {
                unset($_SESSION['__login_tries']);
                $this->_create_user($data);
                return $data;
            } else {
                return array('login'=>'', 'groups'=>array(), 'error'=>sprintf(__('Error checking login data: %s'), $data));
            }
        } elseif (isset($GET['error'])) {
            return array('login'=>'', 'groups'=>array(), 'error'=>sprintf(__('Error: %s'), htmlspecialchars($GET['error'])));
        } elseif ($CI->latesession->get('__login_tries') > 3) {
            unset($_SESSION['__login_tries']);
            unset($_SESSION['__login_key']);
            return array('login'=>'', 'groups'=>array(), 'error'=>__('Error: Infinite redirection during login.'));
        } elseif (isset($GET['k'])) {
            $CI->latesession->get('__login_tries');
            unset($_SESSION['__login_tries']);
            unset($_SESSION['__login_key']);
            return array('login'=>'', 'groups'=>array(), 'error'=>__('Error: Authentication could not be established.'));
        } else {
            $CI->latesession->set('__login_key', $this->mkkey(session_id()));
            header("Location: http://www-cgi.uni-regensburg.de/~stm01875/puma_login.php?p=".urlencode($_SERVER['REQUEST_URI'])."&k=".urlencode($_SESSION['__login_key']), TRUE, 302);
            exit;
        }

        //fail
        return array('login'=>'','groups'=>array());
    }




    /**
    * Create a random key for the login session
    */
    private function mkkey ($seed="") {
        return md5($seed.uniqid(mt_rand(), true));
    }


    /**
    * encrypt a given string
    */
    private function encrypt_secret ($text, $key) {
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, "abcdefghijklmnopqrstuvwxyz012345");
    }


    /**
    * decrypt a given string
    */
    private function decrypt_secret ($secret, $key) {
        return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $secret, MCRYPT_MODE_ECB, "abcdefghijklmnopqrstuvwxyz012345"), "\0");
    }


    /**
    * Check login data for integrity
    */
    private function check_login_data ($data, $strict=False) {
        $fields = array("user", "pre", "sur", "email", "group", "maingroup");
        foreach ($fields as $field) {
            if (! isset($data[$field])) {
                return sprintf(__("The necessary field %s is missing."), $field);
            }
        }
        if (! preg_match("/^[a-z]{3}[0-9]{5}$/", $data['user'])) {
            return sprintf(__("The username %s is unknown."), htmlspecialchars($data['user']));
        }
        if ($data['maingroup'] !== "physik" && $data['user'] !== "stm01875") {
            return sprintf(__("Members of the group %s are not allowed at the moment. We apologize for any inconvenience!"), htmlspecialchars($data['maingroup']));
        }
        if ($strict && substr($data['user'], 0, 3) !== strtolower(substr($data['sur'], 0, 2).substr($data['pre'], 0, 1))) {
            return sprintf(__("The username %s is unknown."), htmlspecialchars($data['user']));
        }
        if ($strict && ! preg_match("/@(?:[a-z]\.)?uni-regensburg.de$/", $data['email'])) {
            return sprintf(__("The email address %s is not allowed."), htmlspecialchars($data['email']));
        }
        $CI = &get_instance();
        $Q = $CI->db->get_where('users',array('login'=>$data['user']));
        if ($Q->num_rows() == 0) {
            $password = md5($this->mkkey('unguessable_password'));
        } else {
            $row = $Q->row();
            if ($row->type != "normal") {
                return __("The account is locked internally.");
            }
            $password = $row->password;
        }
        return array(
            'login'              => $data['user'],
            'groups'             => array("ndsuser", $data['group'], $data['maingroup']),
            'initials'           => substr($data['pre'], 0, 1).substr($data['sur'], 0, 1),
            'firstname'          => $data['pre'],
            'betweenname'        => '',
            'surname'            => $data['sur'],
            'email'              => $data['email'],
            'lastreviewedtopic'  => 1,
            'abbreviation'       => $data['user'],
            'password'           => $password,
            'type'               => 'normal',
            'theme'              => 'default',
            'summarystyle'       => 'author',
            'authordisplaystyle' => 'fvl',
            'liststyle'          => '0',
            'newwindowforatt'    => 'FALSE',
            'exportinbrowser'    => 'TRUE',
            'utf8bibtex'         => 'FALSE',
        );
    }


    /**
     * Global $_GET is unset by CI. Grr!
     */
    private function _parse_get () {
        $qs = $_SERVER['QUERY_STRING'];
        $qm = strpos($qs, "?");
        if ($qm !== FALSE) {
            $qs = substr($qs, $qm);
        }
        $get = explode("&", $qs);
        $ret = array();
        foreach ($get as $q) {
            $q = explode("=", $q, 2);
            $ret[urldecode($q[0])] = isset($q[1])? urldecode($q[1]) : '';
        }
        return $ret;
    }


    /**
     * Create a new user if necessary
     */
    private function _create_user ($data) {
        $CI = &get_instance();
        $Q = $CI->db->get_where('users',array('login'=>$data['login']));
        if ($Q->num_rows() == 0) { // user does not yet exist
            $group_ids = array();
            foreach ($data['groups'] as $groupname) {
                $groupQ = $CI->db->get_where('users', array('type'=>'group', 'abbreviation'=>$groupname));
                if ($groupQ->num_rows() > 0) {
                    $R = $groupQ->row();
                    $group_ids[] = $R->user_id;
                } else {
                    //group must also be created...
                    $CI->db->insert("users", array('surname'=>$groupname,'abbreviation'=>$groupname,'type'=>'group'));
                    $new_id = $CI->db->insert_id();
                    //subscribe group to top topic
                    $CI->db->insert('usertopiclink', array('user_id' => $new_id, 'topic_id' => 1));
                    $group_ids[] = $new_id;
                }
            }
            $insertData = $data;
            unset($insertData['groups']);
            $CI->db->insert("users", $insertData);
            $new_id = $CI->db->insert_id();
            //add group links, and rightsprofiles for these groups, to the user
            foreach ($group_ids as $group_id) {
                $CI->db->insert('usergrouplink',array('user_id'=>$new_id,'group_id'=>$group_id));
                $group = $CI->group_db->getByID($group_id);
                foreach ($group->rightsprofile_ids as $rightsprofile_id) {
                    $rightsprofile = $CI->rightsprofile_db->getByID($rightsprofile_id);
                    foreach ($rightsprofile->rights as $right) {
                        $CI->db->delete('userrights',array('user_id'=>$new_id,'right_name'=>$right));
                        $CI->db->insert('userrights',array('user_id'=>$new_id,'right_name'=>$right));
                    }
                }
            }
            //subscribe new user to top topic
            $CI->db->insert('usertopiclink', array('user_id' => $new_id, 'topic_id' => 1));
        }
    }


}

