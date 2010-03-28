<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Helper for accessing user data
| -------------------------------------------------------------------
*/


/** Return the Abbreviation of a certain user. */
function getAbbrevForUser($user_id) {
    static $abbrevs = array();
    if (count($abbrevs) == 0) {
        $CI = &get_instance();
        foreach ($CI->user_db->getAllUsers() as $user) {
            $abbrevs[$user->user_id+0] = $user->abbreviation;
        }
    }
    if (!array_key_exists($user_id+0,$abbrevs)) {
        return __('unknown');
    } else {
        return $abbrevs[$user_id+0];
    }
}


/**
 * Return the link to a certain user's page
 */
function getUrlForUser($user_id) {
    static $urls = array();
    if (count($urls) == 0) {
        $CI = &get_instance();
        foreach ($CI->user_db->getAllUsers() as $user) {
            $urls[$user->user_id+0] = site_url('user/'.$user->login);
        }
    }
    if (!array_key_exists($user_id+0,$urls)) {
        return site_url('user');
    } else {
        return $urls[$user_id+0];
    }
}


//__END__