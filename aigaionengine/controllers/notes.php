<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notes extends Controller {

    function Notes() {
        parent::Controller();
    }
    
    /** no default */
    function index() {
        redirect('');
    }

    /** 
    notes/delete
    
    Entry point for deleting a note.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    deleting. 
    
    Fails with error message when one of:
        delete requested for non-existing note
        insufficient user rights
        
    Parameters passed via URL segments:
        3rd: note_id, the id of the to-be-deleted-note
        4th: if the 4th segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified
             
      Returns:
          A full HTML page showing a 'request confirmation' form for the delete action, if no 'commit' was specified
          Redirects somewhere (?) after deleting, if 'commit' was specified
    */
    function delete($note_id, $commit='') {
        $note = $this->note_db->getByID($note_id);
        if ($note==null) {
            appendErrorMessage(__('Delete note: non-existing id passed.'));
            redirect('');
        }
        restrict_to_right('note_edit', __('delete note'), 'publications/show/'.$note->pub_id);
        restrict_to_right(!!$this->accesslevels_lib->canEditObject($note), __('delete note'), 'publications/show/'.$note->pub_id);

        if ($commit=='commit') {
            //do delete, redirect somewhere
            $note->delete();
            redirect('publications/show/'.$note->pub_id);
        } else {
            $this->load->view('header', array('title'=>__('Delete note')));
            $this->load->view('confirm', array(
                'url' => 'notes/delete/'.$note->note_id.'/commit',
                'question' => __('Are you sure, that you want to delete the note below?'),
                'cancel_url' => 'publications/show/'.$note->pub_id,
                'additional_info' => '<h3>'.('Note text:').'</h3>'.$note->text,
            ));
            $this->load->view('footer');
        }
    }
      
    /** Entrypoint for adding a note. Shows the necessary form. 3rd segment is pub_id */
    function add($pub_id) {
        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            appendErrorMessage(__('Add note: non-existing id passed.'));
            redirect('');
        }
        restrict_to_right('note_edit', __('add note'));
        restrict_to_right(!!$this->accesslevels_lib->canEditObject($publication), __('add note'));
          
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        $this->load->view('header', array('title'=>__('Add note')));
        $this->load->view('notes/edit', array('pub_id' => $pub_id));
        $this->load->view('footer');
    }
      
    /** Entrypoint for editing a note. Shows the necessary form. */
    function edit($note_id=1) {
        $this->load->helper('publication_helper');
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        $note = $this->note_db->getByID($note_id);
        if ($note==null) {
            appendErrorMessage(__('Edit note: non-existing id passed.'));
            redirect('');
        }
        restrict_to_right('note_edit', __('edit note'), '');
        restrict_to_right(!!$this->accesslevels_lib->canEditObject($note), __('edit note'), '');
        
        $this->load->view('header', array('title'=>__('Edit note')));
        $this->load->view('notes/edit' , array('note' => $note));
        $this->load->view('publications/list', array(
            'publications' => array($this->publication_db->getByID($note->pub_id)),
            'header' => __('Publication belonging to note:'),
            'noNotes' => true, 'noBookmarkList' => true,
            'order' => 'none'));
        $this->load->view('footer');
    }
      
    /**
    notes/commit
      
    Fails with error message when one of:
        edit-commit requested for non-existing note
        insufficient user rights
        
    Parameters passed via POST:
        action = (add|edit)
             
      Redirects to somewhere (?) if the commit was successfull
      Redirects to the edit or add form if the validation of the form values failed
      */
    function commit() {
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        //get data from POST
        $note = $this->note_db->getFromPost();
        if ($note == null) {
            appendErrorMEssage(__('Commit note: no data to commit.'));
            redirect('');
        }
        
        //validation rules: 
        $this->validation->set_rules(array('pub_id' => 'required'));
        $this->validation->set_fields(array('pub_id' => __('Publication id')));
          
        if ($this->validation->run() == FALSE) {
            //return to add/edit form if validation failed
            $this->load->view('header', array('title'=>__('Note')));
            $this->load->view('notes/edit', array('note' => $note,
                                                  'action' => $this->input->post('action')));
            $this->load->view('footer');
        } else {    
            //if validation was successfull: add or change.
            $success = False;
            if ($this->input->post('action') == 'edit') {
                $success = $note->update();
            } else {
                $success = $note->add();
            }
            if (!$success) {
                //this is quite unexpected, I think this should not happen if we have no bugs.
                appendErrorMessage(__('Commit note: an error occurred.'), 'severe');
                redirect('publications/show/'.$note->pub_id);
            }
            //redirect somewhere if commit was successfull
            redirect ('publications/show/'.$note->pub_id);
        }
    }
      
}

//__END__
