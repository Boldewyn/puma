<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Repostform extends Controller {

	function Repostform() {
		parent::Controller();	
	}
	
	/** This controller allows one to repost a form that failed because the user was logged out. */
	function index() {
	    if (!$this->latesession->get('FORMREPOST')) {
	        appendMessage(__('No form data to repost.'));
	        redirect('');
	    }
        $this->load->view('header', array('title' => __('Repost form')));
        $this->load->view('repostform');
        $this->load->view('footer');
	}
	
}

//__END__
