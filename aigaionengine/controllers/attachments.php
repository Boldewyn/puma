<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Attachments extends Controller {

	function Attachments() {
		parent::Controller();	
	}
	
	/** There is no default controller for attachments. */
	function index() {
		redirect('');
	}

    /** 
     * display a single attachment
     */
	function single($att_id) {
	    $attachment = $this->attachment_db->getByID($att_id);
	    if ($attachment==null) {
	        appendErrorMessage(__('Download attachment: non-existing id passed.'));
	        redirect('');
	    }
        $output = $this->load->view('attachments/download', array('attachment'   => $attachment));
	}

	/** 
	 * delete a single attachment
     */
	function delete($att_id, $commit='') {
	    $attachment = $this->attachment_db->getByID($att_id);
	    if ($attachment==null) {
	        appendErrorMessage(__('Delete attachment: non-existing id passed'));
	        redirect('');
	    }
        $userlogin  = getUserLogin();
        restrict_to_right($userlogin->hasRights('attachment_edit') && 
                          $this->accesslevels_lib->canEditObject($attachment), __('Delete attachment'));

        if ($commit=='commit') {
            $attachment->delete();
            redirect('publications/show/'.$attachment->pub_id);
        } else {
            $this->load->view('header', array('title' => __('Delete attachment')));
            $this->load->view('confirm', array(
                'url' => 'attachments/delete/'.$attachment->att_id.'/commit',
                'question' => sprintf(__('Are you sure that you want to delete the attachment &ldquo;%s&rdquo;?'), h($attachment->name)),
            ));
            $this->load->view('footer');
        }
    }


	/** 
	 * add a single attachment
	 */
	function add($pub_id) {
        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            appendErrorMessage(__('Add attachment: non-existing id passed.'));
            redirect('');
        }
        $userlogin  = getUserLogin();
        restrict_to_right($userlogin->hasRights('attachment_edit') &&
                          $this->accesslevels_lib->canEditObject($publication), __('Add attachment'));

        $this->load->view('header', array('title' => __('Add attachment')));
        $this->load->view('attachments/add', array('publication'=>$publication));
        $this->load->view('footer');
    }
    
    /** 
     * edit a single attachment
     */
    function edit($att_id=-1) {
	    $attachment = $this->attachment_db->getByID($att_id);
	    if ($attachment==null) {
	        appendErrorMessage(__('Edit attachment: non-existing id passed.'));
	        redirect('');
	    }
        $userlogin  = getUserLogin();
        restrict_to_right($userlogin->hasRights('attachment_edit') &&
                          $this->accesslevels_lib->canEditObject($attachment),
                          __('Edit attachment'));
	    
        $this->load->view('header', array('title' => __('Attachment')));
        $this->load->view('attachments/edit', array('attachment'=>$attachment));
        $this->load->view('footer');
	}
    
    /** 
     * commit changes to an attachment
     */
    function commit() {
        $attachment = $this->attachment_db->getFromPost();
	    if ($attachment==null) {
	        appendErrorMessage(__('Commit attachment: no data to commit.'));
	        redirect('');
	    }

        //if validation was successfull: add or change.
        $success = False;
        if ($this->input->post('action') == 'edit') {
            $success = $attachment->update();
        } else {
            $success = $attachment->add();
        }
        if (!$success) {
            //might happen, e.g. if upload fails due to post size limits, upload size limits, etc.
            //or illegal attachment extensions etc.
            appendErrorMessage(__('Commit attachment: an error occurred'), 'severe'); 
        }
        redirect('publications/show/'.$attachment->pub_id);

	}
    
    /** 
     * set an attachment as main attachment
     */
    function setmain($att_id=-1) {
	    $attachment = $this->attachment_db->getByID($att_id);
	    if ($attachment==null) {
	        appendErrorMessage(__('Edit attachment: non-existing id passed.'));
	        redirect('');
	    }
	    $attachment->ismain=true;
	    $attachment->update();
        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript; charset=utf-8');
            $this->output->set_output('true');
        } else {
	        redirect('publications/show/'.$attachment->pub_id);
        }
    }
    
    /** 
     * reset main status for an attachment
     */
    function unsetmain($att_id=-1) {
	    $attachment = $this->attachment_db->getByID($att_id);
	    if ($attachment==null) {
	        appendErrorMessage(__('Edit attachment: non-existing id passed.'));
	        redirect('');
	    }
	    $attachment->ismain=false;
	    $attachment->update();
        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript; charset=utf-8');
            $this->output->set_output('true');
        } else {
	        redirect('publications/show/'.$attachment->pub_id);
        }
    }

}


//__END__
