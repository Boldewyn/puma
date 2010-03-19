<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Language extends Controller {

	function Language() {
		parent::Controller();	
	}
	
	/** There is no default controller . */
	function index() {
        $this->load->view('header', array('title' => __('Select language'))); 
        $this->load->view('language/choose');
        $this->load->view('footer');
	}

    /** 
     * set the session language
    */    
    function set() {
        $language = $this->uri->segment(3); 
        $userlogin = getUserLogin();
        //is language in supported list?
        global $AIGAION_SUPPORTED_LANGUAGES;
        if (!in_array($language,$AIGAION_SUPPORTED_LANGUAGES)) {
            appendErrorMessage(sprintf(__('Unknown language: &ldquo;%s&rdquo;'), $language));
        } else {
            $userlogin->effectivePreferences['language'] = $language;
            $this->latesession->set('USERLOGIN',$userlogin);
        }
        back_to_referer();
    }  
    
}


//__END__
