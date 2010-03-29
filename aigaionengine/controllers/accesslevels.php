<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Accesslevels extends Controller {

    function Accesslevels() {
        parent::Controller();
    }

    /** There is no default controller . */
    function index() {
        redirect('');
    }

    /**
     * accesslevels/edit
     */
    function edit($type, $object_id) {
        if ($type=='topic') {
            $this->load->view('header', array('title'=>__('Edit access levels')));
            $this->load->view('accesslevels/editfortopic', array('topic_id'=>$object_id));
            $this->load->view('footer');
            return;
        }
        //determine publication
        $publication = null;
        switch ($type) {
            case 'publication':
                $publication = $this->publication_db->getByID($object_id);
                break;
            case 'attachment':
                $attachment = $this->attachment_db->getByID($object_id);
                if ($attachment!=null)$publication = $this->publication_db->getByID($attachment->pub_id);
                break;
            case 'note':
                $note= $this->note_db->getByID($object_id);
                if ($note!=null)$publication = $this->publication_db->getByID($note->pub_id);
                break;
        }
        if ($publication==null) {
            appendErrorMessage(__('Couldn&rsquo;t find publication to edit access levels.'));
            redirect('');
        }

        $this->load->view('header', array('title'=>__('Eit access levels')));
        $this->load->view('accesslevels/editforpublication',
            array('publication'=>$publication,'type'=>$type,'object_id'=>$object_id));
        $this->load->view('footer');
    }

    /**
     * accesslevels/toggle
     */
    function toggle($type, $object_id, $rights_type) {
        $userlogin = getUserLogin();
        $available_rights = array('public', 'intern', 'private');
        $read = '';
        $edit = '';
        $rw = $rights_type.'_access_level';

        //determine object type and check access level
        $object = null;
        switch ($type) {
            case 'publication':
                $object = $this->publication_db->getByID($object_id);
                $db = $this->publication_db;
                break;
            case 'attachment':
                $object = $this->attachment_db->getByID($object_id);
                $db = $this->attachment_db;
                break;
            case 'note':
                $object = $this->note_db->getByID($object_id);
                $db = $this->note_db;
                break;
        }
        if ($object!=null) {
            //get old rights summary in case we fail the type change
            $read = $object->derived_read_access_level;
            $edit = $object->derived_edit_access_level;

            //check if the user has the required rights
            if ($this->accesslevels_lib->canEditObject($object)) {
                //determine current and new access level
                $currentLevel = $object->$rw;
                $newlevel     = $currentLevel;
                if ($currentLevel == 'public') {
                    $newlevel = 'intern';
                } elseif ($currentLevel == 'intern') {
                    if ($userlogin->userid()==$object->user_id) {
                        $newlevel = 'private';
                    } else {
                        $newlevel = 'public';
                    }
                } elseif ($currentLevel == 'private') {
                    $newlevel = 'public';
                }
            }
            if ($rights_type == 'read') {
                $this->accesslevels_lib->setReadAccessLevel($type,$object_id,$newlevel,true);
            } elseif ($rights_type == 'edit') {
                $this->accesslevels_lib->setEditAccessLevel($type,$object_id,$newlevel,true);
            }

            $object = $db->getByID($object_id);
            $read = $object->derived_read_access_level;
            $edit = $object->derived_edit_access_level;
        }
        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript');
            $this->output->set_output('["'.$read.'","'.$edit.'"]');
        } else {
            back_to_referrer(__('The rights have been updated.'));
        }
    }

    /**
     * accesslevels/set
     */
    function set() {
        $type = $this->input->post('type');
        $id = $this->input->post('id');
        $read = $this->input->post('read');
        $edit = $this->input->post('edit');
        if ($type && $id) {
            if ($read) {
                $this->accesslevels_lib->setReadAccessLevel($type,$id,$read);
            }
            if ($edit) {
                $this->accesslevels_lib->setEditAccessLevel($type,$id,$edit);
            }
            $r = true;
        } else {
            $r = false;
        }
        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript; charset=utf-8');
            $this->output->set_output($r? 'true' : 'false');
        } else {
            back_to_referrer($r? '' : __('The access levels could not be set.'), 'accesslevels/edit/'.$type.'/'.$id, !$r);
        }
    }

}

//__END__
