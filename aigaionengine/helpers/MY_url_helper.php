<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
Aigaion: Extension of the prep_url function...
*/


/**
 * Test, if the request was an AJAX request
 */
function is_ajax() {
    return !!(array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) 
              && strpos($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') > -1);
}


/**
 * Return to referrer, if it is on the same site
 */
function back_to_referer($msg='', $alt='') {
    if ($msg != '') {
        appendMessage($msg);
    }
    if (array_key_exists('HTTP_REFERER', $_SERVER)
        && strpos($_SERVER['HTTP_REFERER'], AIGAION_ROOT_URL) === 0) {
        redirect(substr($_SERVER['HTTP_REFERER'], strlen(AIGAION_ROOT_URL)));
    } else {
        redirect($alt);
    }
    exit;
}


/**
 * Prep URL
 *
 * Simply adds the http:// part if missing
 *
 * @access	public
 * @param	string	the URL
 * @return	string
 */
function prep_url($str = '')
{
	if ($str == 'http://' OR $str == '')
	{
		return '';
	}
	
	//mod by PDM, for Aigaion 2.0
	if (eregi('^[a-z]+://', $str) == FALSE)
	{
		$str = 'http://'.$str;
	}
	
	return $str;
}
	
?>