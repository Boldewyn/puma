<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?><?php
/** This class regulates the database access for a siteconfig.
 
*/

class Siteconfig_db {
    
  
    function Siteconfig_db()
    {
    }
     
    /** returns the config object for the current site */
    function getSiteConfig() {
        $CI = &get_instance();
        $result = new Siteconfig();
        $result->configSettings = array();
        $Q = $CI->db->get('config');
        foreach ($Q->result() as $R) {
            //where needed, interpret setting as other than string
            if ($R->setting == "ALLOWED_ATTACHMENT_EXTENSIONS") {
                $value = split(",",$R->value);
            } 
            else if ($R->setting=='language')
            {
              $value = $R->value;
              //check existence of language
              global $AIGAION_SUPPORTED_LANGUAGES;
              if (!in_array($value,$AIGAION_SUPPORTED_LANGUAGES))
              {
                appendErrorMessage(sprintf(__("Language '%s' no longer exists under that name. Please reset the relevant profile and site settings."),$val)."<br/>");
                $value = AIGAION_DEFAULT_LANGUAGE;
              }
            } 
            else 
            {
                $value = $R->value;
            }  
            $result->configSettings[$R->setting]=$value;
        }
        if (   (!isset($result->configSettings['LOGIN_HTTPAUTH_ENABLE']) || ($result->configSettings['LOGIN_HTTPAUTH_ENABLE']=='')) 
            &&
               (isset($result->configSettings['USE_EXTERNAL_LOGIN']))
            &&
               ($result->configSettings['USE_EXTERNAL_LOGIN'] == 'TRUE')
            &&
               (isset($result->configSettings['EXTERNAL_LOGIN_MODULE']))
            &&
               ($result->configSettings['EXTERNAL_LOGIN_MODULE'] == 'Httpauth')
            )
            {
            $result->configSettings['LOGIN_HTTPAUTH_ENABLE'] = 'TRUE';
                
        }

        return $result;
    }
    
    /** returns the config object posted from the siteconfig edit form */
    function getFromPost() {
        $CI = &get_instance();
        //correct form?
        if ($CI->input->post('formname')!='siteconfig') {
            return null;
        }
        $result = new Siteconfig();
        $result->configSettings['CFG_ADMIN']                        = $CI->input->post('CFG_ADMIN');
        $result->configSettings['CFG_ADMINMAIL']                    = $CI->input->post('CFG_ADMINMAIL');
        $result->configSettings['ALLOWED_ATTACHMENT_EXTENSIONS']    = split(',',$CI->input->post('ALLOWED_ATTACHMENT_EXTENSIONS'));
        if ($CI->input->post('ALLOW_ALL_EXTERNAL_ATTACHMENTS')=='ALLOW_ALL_EXTERNAL_ATTACHMENTS') {
            $result->configSettings['ALLOW_ALL_EXTERNAL_ATTACHMENTS'] = 'TRUE';
        } else {
            $result->configSettings['ALLOW_ALL_EXTERNAL_ATTACHMENTS'] = 'FALSE';
        }
        if ($CI->input->post('SERVER_NOT_WRITABLE')=='SERVER_NOT_WRITABLE') {
            $result->configSettings['SERVER_NOT_WRITABLE']          = 'TRUE';
        } else {
            $result->configSettings['SERVER_NOT_WRITABLE']          = 'FALSE';
        }
        $result->configSettings['WINDOW_TITLE']                     = $CI->input->post('WINDOW_TITLE');
        if ($CI->input->post('USE_UPLOADED_LOGO')=='USE_UPLOADED_LOGO') {
            $result->configSettings['USE_UPLOADED_LOGO']           = 'TRUE';
        } else {
            $result->configSettings['USE_UPLOADED_LOGO']           = 'FALSE';
        }
        if ($CI->input->post('ALWAYS_INCLUDE_PAPERS_FOR_TOPIC')=='ALWAYS_INCLUDE_PAPERS_FOR_TOPIC') {
            $result->configSettings['ALWAYS_INCLUDE_PAPERS_FOR_TOPIC'] ='TRUE';
        } else {
            $result->configSettings['ALWAYS_INCLUDE_PAPERS_FOR_TOPIC'] ='FALSE';
        }
        if ($CI->input->post('PUBLICATION_XREF_MERGE')=='PUBLICATION_XREF_MERGE') {
            $result->configSettings['PUBLICATION_XREF_MERGE']       = 'TRUE';
        } else {
            $result->configSettings['PUBLICATION_XREF_MERGE']       = 'FALSE';
        }
        $result->configSettings['DEFAULTPREF_THEME']              = $CI->input->post('DEFAULTPREF_THEME');
        $result->configSettings['DEFAULTPREF_LANGUAGE']           = $CI->input->post('DEFAULTPREF_LANGUAGE');
        $result->configSettings['DEFAULTPREF_SUMMARYSTYLE']       = $CI->input->post('DEFAULTPREF_SUMMARYSTYLE');
        $result->configSettings['DEFAULTPREF_AUTHORDISPLAYSTYLE'] = $CI->input->post('DEFAULTPREF_AUTHORDISPLAYSTYLE');
        $result->configSettings['DEFAULTPREF_LISTSTYLE']          = $CI->input->post('DEFAULTPREF_LISTSTYLE');
        $result->configSettings['DEFAULTPREF_SIMILAR_AUTHOR_TEST']          = $CI->input->post('DEFAULTPREF_SIMILAR_AUTHOR_TEST');
        if ($CI->input->post('DEFAULTPREF_NEWWINDOWFORATT')=='DEFAULTPREF_NEWWINDOWFORATT') {
            $result->configSettings['DEFAULTPREF_NEWWINDOWFORATT']       = 'TRUE';
        } else {
            $result->configSettings['DEFAULTPREF_NEWWINDOWFORATT']       = 'FALSE';
        }
        if ($CI->input->post('DEFAULTPREF_EXPORTINBROWSER')=='DEFAULTPREF_EXPORTINBROWSER') {
            $result->configSettings['DEFAULTPREF_EXPORTINBROWSER']       = 'TRUE';
        } else {
            $result->configSettings['DEFAULTPREF_EXPORTINBROWSER']       = 'FALSE';
        }
        if ($CI->input->post('DEFAULTPREF_UTF8BIBTEX')=='DEFAULTPREF_UTF8BIBTEX') {
            $result->configSettings['DEFAULTPREF_UTF8BIBTEX']       = 'TRUE';
        } else {
            $result->configSettings['DEFAULTPREF_UTF8BIBTEX']       = 'FALSE';
        }
        if ($CI->input->post('CONVERT_BIBTEX_TO_UTF8')=='CONVERT_BIBTEX_TO_UTF8') {
            $result->configSettings['CONVERT_BIBTEX_TO_UTF8']       = 'TRUE';
        } else {
            $result->configSettings['CONVERT_BIBTEX_TO_UTF8']       = 'FALSE';
        }
        //if ($CI->input->post('CONVERT_LATINCHARS_IN')=='CONVERT_LATINCHARS_IN') {
        //    $result->configSettings['CONVERT_LATINCHARS_IN']='TRUE';
        //} else {
        //    $result->configSettings['CONVERT_LATINCHARS_IN']='FALSE';
        //}
        $result->configSettings['BIBTEX_STRINGS_IN']                = $CI->input->post('BIBTEX_STRINGS_IN');
        $result->configSettings['ATT_DEFAULT_READ']                = $CI->input->post('ATT_DEFAULT_READ');
        $result->configSettings['ATT_DEFAULT_EDIT']                = $CI->input->post('ATT_DEFAULT_EDIT');
        $result->configSettings['PUB_DEFAULT_READ']               = $CI->input->post('PUB_DEFAULT_READ');
        $result->configSettings['PUB_DEFAULT_EDIT']               = $CI->input->post('PUB_DEFAULT_EDIT');
        $result->configSettings['NOTE_DEFAULT_READ']               = $CI->input->post('NOTE_DEFAULT_READ');
        $result->configSettings['NOTE_DEFAULT_EDIT']               = $CI->input->post('NOTE_DEFAULT_EDIT');
        $result->configSettings['TOPIC_DEFAULT_READ']                = $CI->input->post('TOPIC_DEFAULT_READ');
        $result->configSettings['TOPIC_DEFAULT_EDIT']                = $CI->input->post('TOPIC_DEFAULT_EDIT');

        //====LOGIN SETTINGS
        //$result->configSettings['EXTERNAL_LOGIN_MODULE']            = $CI->input->post('EXTERNAL_LOGIN_MODULE');
        if ($CI->input->post('LOGIN_CREATE_MISSING_USER')=='LOGIN_CREATE_MISSING_USER') {
            $result->configSettings['LOGIN_CREATE_MISSING_USER']           = 'TRUE';
        } else {
            $result->configSettings['LOGIN_CREATE_MISSING_USER']           = 'FALSE';
        }
        
        //DISABLED DISABLED DISABLED 
        //please see comments in userlogin class, external login function
        if ($CI->input->post('LOGIN_HTTPAUTH_ENABLE')=='LOGIN_HTTPAUTH_ENABLE') {
            $result->configSettings['LOGIN_HTTPAUTH_ENABLE']           = 'TRUE';
            $result->configSettings['USE_EXTERNAL_LOGIN']              = 'TRUE';
            $result->configSettings['EXTERNAL_LOGIN_MODULE']           = 'Httpauth';
        } else {
            $result->configSettings['LOGIN_HTTPAUTH_ENABLE']           = 'FALSE';
            $result->configSettings['USE_EXTERNAL_LOGIN']              = 'FALSE';
            $result->configSettings['EXTERNAL_LOGIN_MODULE']           = 'Aigaion';
        }
        $result->configSettings['LOGIN_HTTPAUTH_GROUP']					= $CI->input->post('LOGIN_HTTPAUTH_GROUP');
//        if ($result->configSettings['EXTERNAL_LOGIN_MODULE']=='Aigaion') {
//            $result->configSettings['USE_EXTERNAL_LOGIN']           = 'FALSE';
//        } else {
//            
//        }
        $result->configSettings['LDAP_SERVER']                     = $CI->input->post('LDAP_SERVER');
        $result->configSettings['LDAP_BASE_DN']                    = $CI->input->post('LDAP_BASE_DN');
        $result->configSettings['LDAP_DOMAIN']                     = $CI->input->post('LDAP_DOMAIN');
        if ($CI->input->post('LDAP_IS_ACTIVE_DIRECTORY')=='LDAP_IS_ACTIVE_DIRECTORY') {
            $result->configSettings['LDAP_IS_ACTIVE_DIRECTORY']    = 'TRUE';
        } else {
            $result->configSettings['LDAP_IS_ACTIVE_DIRECTORY']    = 'FALSE';
        }
        if ($CI->input->post('LOGIN_ENABLE_ANON')=='LOGIN_ENABLE_ANON') {
            $result->configSettings['LOGIN_ENABLE_ANON']           = 'TRUE';
        } else {
            $result->configSettings['LOGIN_ENABLE_ANON']           = 'FALSE';
        }
        $result->configSettings['LOGIN_DEFAULT_ANON']              = $CI->input->post('LOGIN_DEFAULT_ANON');

        if ($CI->input->post('LOGIN_ENABLE_DELEGATED_LOGIN')=='LOGIN_ENABLE_DELEGATED_LOGIN') {
            $result->configSettings['LOGIN_ENABLE_DELEGATED_LOGIN']           = 'TRUE';
        } else {
            $result->configSettings['LOGIN_ENABLE_DELEGATED_LOGIN']           = 'FALSE';
        }
        $result->configSettings['LOGIN_DELEGATES']                 = $CI->input->post('LOGIN_DELEGATES');
        if ($CI->input->post('LOGIN_DISABLE_INTERNAL_LOGIN')=='LOGIN_DISABLE_INTERNAL_LOGIN') {
            $result->configSettings['LOGIN_DISABLE_INTERNAL_LOGIN']           = 'TRUE';
        } else {
            $result->configSettings['LOGIN_DISABLE_INTERNAL_LOGIN']           = 'FALSE';
        }
        
        $result->configSettings['EMBEDDING_SHAREDDOMAIN']                     = $CI->input->post('EMBEDDING_SHAREDDOMAIN');
        $result->configSettings['LOGINTEGRATION_SECRETWORD']                     = $CI->input->post('LOGINTEGRATION_SECRETWORD');
        if ($CI->input->post('ENABLE_TINYMCE')=='ENABLE_TINYMCE') {
            $result->configSettings['ENABLE_TINYMCE'] = 'TRUE';
        } else {
            $result->configSettings['ENABLE_TINYMCE'] = 'FALSE';
        }
        
        return $result;
    }

    /** commit the config settings embodied in the given data */
    function update($siteconfig) {
        $CI = &get_instance();
        $CI->load->library('file_upload');
        //check rights
        $userlogin = getUserLogin();
        if (     !$userlogin->hasRights('database_manage')
            ) {
                return;
        }
        //check some of the settings on impossible combinations
        //-delegate password checking can only be enabled if some delegates are specified. Otherwise, disable again.
        if (   $siteconfig->configSettings['LOGIN_ENABLE_DELEGATED_LOGIN']=='TRUE' 
            && $siteconfig->configSettings['LOGIN_DELEGATES']==''
           ) {
            appendErrorMessage(__('Delegated password checking can only be enabled when some password checking module was specified! Since this was not the case, delegated password checking has been disabled.').'<br/>');
            $siteconfig->configSettings['LOGIN_ENABLE_DELEGATED_LOGIN']='FALSE';
        }
        //-at least one of internal or external login must be enabled. If not, enable internal login again
        if (   $siteconfig->configSettings['LOGIN_DISABLE_INTERNAL_LOGIN']=='TRUE' //no internal login
            && 
               $siteconfig->configSettings['LOGIN_ENABLE_DELEGATED_LOGIN']!='TRUE' //no delegate login or no delegates
           ) {
            appendErrorMessage(__('At least one of internal login or delegated password checking must be enabled! Since this was not the case, internal login has been re-enabled.').'<br/>');
            $siteconfig->configSettings['LOGIN_DISABLE_INTERNAL_LOGIN']='FALSE';
        }
        //-Anon access enabled, but no default anon account specified? give warning, but do not bother changing the settings
        if ($siteconfig->configSettings['LOGIN_ENABLE_ANON']=='TRUE') { //anon access enabled?
            $anonAcc = $siteconfig->configSettings['LOGIN_DEFAULT_ANON'];
            $anonUser = $CI->user_db->getByID($anonAcc);
            if ($anonUser == NULL || $anonUser->type!='anon') {//no valid default anon account
                appendMessage(__('Anonymous guest access has been enabled, but no valid anonymous account was specified. Note that anonymous login will not work until such an anonymous account has been created, and assigned as default anonymous account.').'<br/>');
            }
           
        }
        //start to update
        foreach ($siteconfig->configSettings as $setting=>$value) {
            
            if ($setting == 'ALLOWED_ATTACHMENT_EXTENSIONS') {
            	#check allowed extensions: all extensions should be prefixed with a . and should be trimmed of spaces
            	$templist = array();
            	foreach ($value as $ext) {
            		$ext = trim($ext);
            		if (($ext=="") || ($ext==".")) {
            			continue;
            		}
            		if (strpos($ext,".") === FALSE) {
            			$ext = ".".$ext;
            		}
            		//disallow a specific class of attachments permanently
            		if (!in_array(strtolower(substr($ext,-4)),array('.php','php3','php4','.exe','.bat'))) {
            		    $templist[] = $ext;
            		} else {
            		    appendErrorMessage(sprintf(__("The extension '%s' is never allowed for Aigaion attachments, and has been removed from the list of allowed attachments."),$ext));
            		}
            	}
            	if (sizeof($templist)==0) {
            		$templist[] = ".pdf";
            	}                
            	$value = implode(',',$templist);
            }
        	#check existence of setting
        	$CI->db->query("INSERT IGNORE INTO ".AIGAION_DB_PREFIX."config (setting) VALUES (".$CI->db->escape($setting).")");
        	#update value
            $CI->db->where('setting', $setting);
            $CI->db->update('config', array('value'=>$value));
        	if (mysql_error()) {
        		appendErrorMessage("Error updating config: <br/>");
        		appendErrorMessage(mysql_error()."<br/>");
        	}
        }
    	#upload (from post) new custom logo, if available
        if (  ($siteconfig->configSettings['USE_UPLOADED_LOGO']=='TRUE') 
            || (
                isset($_FILES['new_logo'])
                &&
                $_FILES['new_logo']['error']==0
                ) ) {
            $siteconfig->configSettings['USE_UPLOADED_LOGO']='TRUE';
            $max_size = 1024*10; // the max. size for uploading
            	
            $my_upload = new File_upload;
            $my_upload->upload_dir = AIGAION_ATTACHMENT_DIR.'/'; // "files" is the folder for the uploaded files (you have to create this folder)
            $my_upload->extensions = array('.jpg');
            $my_upload->max_length_filename = 100; // change this value to fit your field length in your database (standard 100)
            $my_upload->rename_file = true;
        	$my_upload->the_temp_file = $_FILES['new_logo']['tmp_name'];
        	$my_upload->the_file = $_FILES['new_logo']['name'];
        
        	$my_upload->http_error = $_FILES['new_logo']['error'];
        	if ($my_upload->http_error > 0) {
        		//appendErrorMessage("Error while uploading custom logo: ".$my_upload->error_text($my_upload->http_error));
        	} else {
    
            	$my_upload->replace = "y";
            	$my_upload->do_filename_check = "n"; // use this boolean to check for a valid filename
            
                if (!$my_upload->upload("custom_logo")) {
                    //if failed: set to false again and give message? no, cause maybe there just was no file uploaded :)
                    appendErrorMessage(__("Failed to upload custom logo.")." ".$my_upload->show_error_string().'<br/>' );
                    //$USE_UPLOADED_LOGO = "FALSE";
                } else {
                    appendMessage(__("New logo uploaded").".<br/>");
                }
            }
        } else {
            //appendMessage("No new logo<br/>".$siteconfig->configSettings['USE_UPLOADED_LOGO']);
        }
        #reset cached config settings
        $CI = &get_instance();
        $CI->latesession->set('SITECONFIG',null);
        #reset profile settings (to account for possibly changed preference defaults)
        $userlogin->initPreferences();
    }
}
?>