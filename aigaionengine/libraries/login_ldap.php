<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php

/** Login management from LDAP. */
class Login_ldap {
    appendErrorMessage(__("Loaded deprecated login_ldap module. LDAP login now uses delegated password checking!"));
    /** Returns an associative array containing the login name of the user and all groups that this user 
    belongs to... (the same names that are stored in aigaion in the abbreviation). Expects user and password
    to be stored in the POST */
    function getLoginInfo() {
        //get username and/or pwd from POST
        $loginName = '';
        $loginPwd = '';
        $groups = array();
        if (((isset($_POST["loginName"]))) && (isset($_POST['loginPass'])))    {
            #user logs in via login screen.
            //get username & pwd
            $postloginName = $_POST["loginName"];
            $postloginPwd = $_POST['loginPass'];
            //now try to login from LDAP 
            $serverType = "";
            if (getConfigurationSetting('LDAP_IS_ACTIVE_DIRECTORY') != 'FALSE') {
                    $serverType = "ActiveDirectory";
            }
            $ldap = new Authldap(getConfigurationSetting('LDAP_SERVER'),
                                 getConfigurationSetting('LDAP_BASE_DN'),
                                 $serverType, 
                                 getConfigurationSetting('LDAP_DOMAIN'),
                                 "", "");
            //$ldap->dn = getConfigurationSetting('LDAP_BASE_DN');
            //$ldap->server = getConfigurationSetting('LDAP_SERVER');
        	/*
        	$ldap = new Authldap(
        	getConfigurationSetting('LDAP_SERVER'), 
        	getConfigurationSetting('LDAP_BASE_DN'), 
        	"ActiveDirectory",  $sDomain =  "", 
        	$postloginName, $postloginPwd);
            */
        	$ds = $ldap->connect();
        	if (!$ds) {
          		appendErrorMessage("LDAP auth: There was a problem.<br/>");
          		appendErrorMessage( "Error code : " . $ldap->ldapErrorCode . "<br/>");
          		appendErrorMessage( "Error text : " . $ldap->ldapErrorText . "<br/>");
        	} else {
       	    
        	    if ($ldap->checkPass($postloginName,$postloginPwd)) {
            		$loginName = $postloginName;
            	} else {
            	    appendErrorMessage($ldap->ldapErrorText);
            	}
        	    
        		//get groups...
        	}// else {
          		//appendErrorMessage( "LDAP auth: Password check failed.<br/>");
          	//	appendErrorMessage( "Error code : " . $ldap->ldapErrorCode . "<br/>");
          		//appendErrorMessage( "Error text : " . $ldap->ldapErrorText . "<br/>");
        	//}
        }
        
        return array('login'=>$loginName,'groups'=>$groups);
    }

}
?>