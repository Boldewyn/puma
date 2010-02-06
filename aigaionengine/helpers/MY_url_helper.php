<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
Aigaion: Extension of the prep_url function...
*/


/**
 *
 */
function is_ajax() {
    return !!(array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) 
              && strpos($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') > -1);
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