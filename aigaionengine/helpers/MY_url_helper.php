<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
Aigaion: Extension of the prep_url function...
*/


/**
 * Test, if the request was an AJAX request (or a faked one with ?ajax=1)
 */
function is_ajax() {
    return !!((array_key_exists('HTTP_X_REQUESTED_WITH', $_SERVER) 
               && strpos($_SERVER['HTTP_X_REQUESTED_WITH'], 'XMLHttpRequest') !== FALSE) ||
              (array_key_exists('ajax', $_REQUEST) && $_REQUEST['ajax'] == '1')
            );
}


/**
 * Return to referrer, if it is on the same site
 */
function back_to_referer($msg='', $alt='', $error=False) {
    if ($msg != '') {
        if ($error) {
            appendErrorMessage($msg);
        } else {
            appendMessage($msg);
        }
    }
    if (array_key_exists('HTTP_REFERER', $_SERVER)
        && strpos($_SERVER['HTTP_REFERER'], AIGAION_ROOT_URL) === 0) {
        redirect(substr($_SERVER['HTTP_REFERER'], strlen(AIGAION_ROOT_URL)));
    } else {
        redirect($alt);
    }
    exit;
}
function back_to_referrer($msg='', $alt='', $error=False) { back_to_referer($msg, $alt, $error); }


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
	
  
  
function anchor($uri = '', $title = '', $attributes = '')
{
  $title = (string) $title;

  if ( ! is_array($uri))
  {
    $site_url = ( ! preg_match('!^\w+://|^#! i', $uri)) ? site_url($uri) : $uri;
  }
  else
  {
    $site_url = site_url($uri);
  }

  if ($title == '')
  {
    $title = $site_url;
  }

  if ($attributes != '')
  {
    $attributes = _parse_attributes($attributes);
  }

  return '<a href="'.$site_url.'"'.$attributes.'>'.$title.'</a>';
}

  
//__END__