<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| NOTE FOR AIGAION:
| -------------------------------------------------------------------
|
| Generally, there is no need to adapt the values in this file for an
| instance of Aigaion. If you want to change settings you should 
| probably adapt the settings specified in the main index.php file for
| this instance of Aigaion.
*/

/*
| -------------------------------------------------------------------
| NOTE FOR AIGAION:
| -------------------------------------------------------------------
|
| Here we initialize some settings that MAY have been set in the main index.php.
| If they have not been set, default values will be used. Directories are relative 
| to the directory of the main index.php
*/
$ROOT_PATH = dirname(FCPATH);
# URL where to store attachments. Default: root_url/attachments
if (!defined('AIGAION_ATTACHMENT_URL') || (AIGAION_ATTACHMENT_URL=='')) {
    define ('AIGAION_ATTACHMENT_URL',AIGAION_ROOT_URL."/attachments");
}
# Directory where to store attachments. Default: this directory/attachments
if (!defined('AIGAION_ATTACHMENT_DIR') || (AIGAION_ATTACHMENT_DIR=='')) {
    define ('AIGAION_ATTACHMENT_DIR',$ROOT_PATH."/attachments");
}
#URL to the application: default same as AIGAION_ROOT_URL/aigaionengine
if (!defined('APPURL') || (APPURL=='')) {
    define ('APPURL',AIGAION_ROOT_URL.'/aigaionengine');
}
#AIGAION_DB_PREFIX: table prefix, default "", see config/database.php
if (!defined('AIGAION_DB_PREFIX')) {
    define ('AIGAION_DB_PREFIX','');
}
#EXPORT_REPLY_ADDRESS: table prefix, default ""
if (!defined('EXPORT_REPLY_ADDRESS')) {
    define ('EXPORT_REPLY_ADDRESS','');
}
#MAXIMUM_ATTACHMENT_SIZE: table prefix, default 10000
if (!defined('MAXIMUM_ATTACHMENT_SIZE')) {
    define('MAXIMUM_ATTACHMENT_SIZE', '10000');
}

#multilanguage support.
#By default, we expect the following languages to be present: de, en, nl. Preferred is, by default, en.
#you can override these settings in index.php
global $AIGAION_SUPPORTED_LANGUAGES;
if (!isset($AIGAION_SUPPORTED_LANGUAGES) || !is_array($AIGAION_SUPPORTED_LANGUAGES))
{
  $AIGAION_SUPPORTED_LANGUAGES = array ('de', 'en', 'nl', 'no'); //default is the original set of languages from the multiling Aigaion 2.0
}
global $AIGAION_SHORTLIST_LANGUAGES;
if (!isset($AIGAION_SHORTLIST_LANGUAGES) || !is_array($AIGAION_SHORTLIST_LANGUAGES))
{
  $AIGAION_SHORTLIST_LANGUAGES = $AIGAION_SUPPORTED_LANGUAGES;
}
if (!defined('AIGAION_DEFAULT_LANGUAGE'))
{
  define('AIGAION_DEFAULT_LANGUAGE',  'en');
}

define('AIGSTR','A1I2G3A4I5O6N7');

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your Code Igniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://www.your-site.com/
|
| -------------------------------------------------------------------
| NOTE FOR AIGAION:
| -------------------------------------------------------------------
| 
| This setting is determined by the mandatory define of AIGAION_ROOT_URL
| in the main index.php of the instance of the site.
*/
$config['base_url']	= AIGAION_ROOT_URL;

/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
if (defined('CLEAN_URLS') && CLEAN_URLS)
	$config['index_page'] = "";
else
	$config['index_page'] = "index.php";

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of "AUTO" works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol']	= "AUTO";

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by Code Igniter.
| For more information please see the user guide:
|
| http://www.codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = "";

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language']	= "de";

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = "UTF-8";

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the "hooks" feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = TRUE;


/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://www.codeigniter.com/user_guide/general/core_classes.html
| http://www.codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';


/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify which characters are permitted within your URLs.
| When someone tries to submit a URL with disallowed characters they will
| get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';


/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default Code Igniter uses search-engine friendly segment based URLs:
| www.your-site.com/who/what/where/
|
| You can optionally enable standard query string based URLs:
| www.your-site.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The two other items let you set the query string "words" that will
| invoke your controllers and its functions:
| www.your-site.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since Code Igniter is designed primarily to
| use segment based URLs.
|
*/
$config['enable_query_strings'] = FALSE;
$config['directory_trigger'] = 'd';	 // experimental not currently in use
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to 
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 1;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/logs/ folder.  Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Sessions class with encryption
| enabled you MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = "";

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'session_cookie_name' = the name you want for the cookie
| 'encrypt_sess_cookie' = TRUE/FALSE (boolean).  Whether to encrypt the cookie
| 'session_expiration'  = the number of SECONDS you want the session to last.
|  by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
|  'time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']		= 'ci_session';
$config['sess_expiration']		= 7200;
$config['sess_encrypt_cookie']	= FALSE;
$config['sess_use_database']	= FALSE;
$config['sess_table_name']		= 'ci_sessions';
$config['sess_match_ip']		= FALSE;
$config['sess_match_useragent']	= TRUE;
$config['sess_time_to_update'] 		= 300;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
|
*/
$config['cookie_prefix']	= "";
$config['cookie_domain']	= "";
$config['cookie_path']		= "/";

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = FALSE;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not "echo" any values with compression enabled.
|
*/
$config['compress_output'] = FALSE;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are "local" or "gmt".  This pref tells the system whether to use
| your server's local time as the master "now" reference, or convert it to
| GMT.  See the "date helper" page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';


/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = FALSE;


?>