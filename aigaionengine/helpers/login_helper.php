<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Helper for accessing the UserLogin object
| -------------------------------------------------------------------
|
|   Provides access to the UserLogin object
|
|	Usage:
|       $this->load->helper('login'); //load this helper
|       $val = getUserLogin(); //retrieve UserLogin object
|
|   Implementation:
|       The UserLogin object will not be created until requested for the first time.
|       When it is requested for the first time it is created and stored in the session.
|
*/

function getUserLogin() {
    $CI = &get_instance();

    $userlogin = $CI->latesession->get('USERLOGIN');
    if (!isset($userlogin)||($userlogin==null)) {
        $userlogin = new UserLogin();
        $CI->latesession->set('USERLOGIN',$userlogin);
    }
    return $userlogin;
}

function restrict_to_admins($msg='', $redirect='') {
    $userlogin = getUserLogin();
    if (! $userlogin->hasRights('database_manage')) {
        if ($msg) { appendErrorMessage($msg); }
        redirect($redirect);
        exit;
    }
}

function restrict_to_users($msg='', $redirect='') {
    if (! is_user()) {
        if ($msg) { appendErrorMessage($msg); }
        redirect($redirect);
        exit;
    }
}

function restrict_to_right($right, $msg='', $redirect='') {
    $userlogin = getUserLogin();
    if (! $userlogin->hasRights('database_manage')
        && (is_string($right) && ! $userlogin->hasRights($right))
        || $right != True) {
        if ($msg) { appendErrorMessage(sprintf(__('Insufficient rights: %s.'), $msg)); }
        redirect($redirect);
        exit;
    }
}

function is_user() {
    $userlogin = getUserLogin();
    return ($userlogin->isLoggedIn() && !$userlogin->isAnonymous());
}

function is_online ($user) {
    $last_seen = $user->preferences['last_seen'];
    $timestamp = mktime(substr($last_seen, 11,2), substr($last_seen, 14,2),
        substr($last_seen, 17,2), substr($last_seen, 5,2), substr($last_seen, 8,2),
        substr($last_seen, 0,4));
    if (time() - $timestamp < 180) {
        return True;
    }
    return False;
}

//__END__