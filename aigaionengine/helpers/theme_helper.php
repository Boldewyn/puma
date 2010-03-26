<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
|  Helper for accessing icons and stylesheets
| -------------------------------------------------------------------
|
|   Provides access to the themes, icons and stylesheets, dependent on the theme 
|   settings of the current user.
|
|	Usage:
|       //load this helper:
|       $this->load->helper('theme'); 
|       //get available themes by name:
|       $list = getThemes(); 
|       //check whether theme exists:
|       $exists = themeExists($themeName); 
|       //retrieve url for icon:
|       $iconUrl = 
|       
*/

    /* Return a list of available themes. Themes are subdirectories of ROOT/themes/
       other than the CVS directory. */
    function getThemes() {
    	$themepath = STATICPATH.'themes/';
    	$themelist = array();
    	if ($handle = opendir($themepath)) {
    		while (false !== ($nextfile = readdir($handle))) {
    			if (substr($nextfile, 0, 1) != '.' 
    			    && (strtolower($nextfile)!='cvs') 
    			    && (is_dir($themepath.'/'.$nextfile))
    			    && file_exists($themepath.'/'.$nextfile.'/style.css')
    			    ) {
    				$themelist[] = $nextfile;
    			}
    		}
    		closedir($handle);
    	}
    	return $themelist;
    }
    
    /* this function checks whether a named theme exists. Themes are subdirectories 
       of ROOT/themes/ other than the CVS directory.
       Furthermore, the directory should not be empty :-/  (CVS will keep the dirs 
       even if the theme is gone). So we also check for theme/css/style.css */
    function themeExists($themeName) {
    	#don't accidentally accept CVS directory...
    	if (strtolower($themeName) == 'cvs') return false;
    	if (strtolower($themeName) == 'puma') return true;
    	$path = STATICPATH.'/themes/'.$themeName.'/';
    	return (file_exists($path) && file_exists($path.'style.css') && is_dir($path));
    }

    /** If a user is logged in, return name of theme, otherwise return name of default theme. */
    function getThemeName() {
      $userlogin = getUserLogin();
        if ($userlogin->isLoggedIn()) {
            $return = $userlogin->getPreference('theme');
            return themeExists($return)? $return : 'puma';
        } else {
            return 'puma';
        }
    }
    
    /* Return true iff icon exists at all */
    function iconExists($iconName) {
        return file_exists(STATICPATH.'themes/'.getThemeName().'/images/icons/'.$iconName) || file_exists(STATICPATH.'themes/puma/images/icons/'.$iconName);
    }
    /* Return true iff icon exists in current theme */
    function iconExistsInTheme($iconName) {
        return file_exists(STATICPATH.'themes/'.getThemeName().'/images/icons/'.$iconName);
    }
    /** Return the Url of the requested icon (full file name!), taking current theme
        into account. */
    function getIconUrl($iconName) {
        if (iconExistsInTheme($iconName)) {
            return STATICURL.'themes/'.getThemeName().'/images/icons/'.$iconName;
        } else {
            return STATICURL.'themes/puma/images/icons/'.$iconName;
        }
    }
    /** Return the Url of the requested css file (full file name!), taking current 
        theme into account. */
    function getCssUrl($cssName) {
        return STATICURL.'themes/'.getThemeName().'/'.$cssName;
    }

//__END__
