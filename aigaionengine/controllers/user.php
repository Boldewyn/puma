<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends Controller {

    public function User() {
        parent::Controller();
    }

    /**
     *
     */
    public function index ($id, $action=False) {
        $user = $this->user_db->getByLogin($id);
        $userlogin = getUserLogin();
        if ($user==null) {
            appendErrorMessage(sprintf(__("User %s does not exist."), h($id)));
            redirect('');
        } elseif ($action && ! in_array($action, array("contact", "edit"))) {
            appendErrorMessage(sprintf(__("Unknown action requested: %s."), h($action)));
            redirect('');
        } elseif ($userlogin->isAnonymous() && $id != "admin") {
            appendErrorMessage(__('You must be logged in to view this user&rsquo;s page.'));
            redirect('');
        }

        if (! $action) {
            $this->load->view('header', array("title"=>sprintf(__("User %s"), $id));
            $this->load->view('user/full', array('user' => $user));
            $this->load->view('footer', '');
        } else {
            $this->$action($id, $user);
        }
    }

    /**
     *
     */
    protected function contact ($id, $user) {
        $this->load->view('header', array("title"=>sprintf(__("Contact user %s"), $id));
        $this->load->view('user/contact', array('user' => $user));
        $this->load->view('footer', '');
    }

    /**
     *
     */
    protected function edit ($id, $user) {
        $userlogin = getUserLogin();
        if ((!$userlogin->hasRights('user_edit_all'))
             &&
            (!$userlogin->hasRights('user_edit_self') || ($userlogin->userId() != $user->user_id))) {
            appendErrorMessage(__('You are not allowed to edit other users.'));
            redirect('');
        }

        $this->load->view('header', array("title"=>sprintf(__("Edit user %s"), $id));
        $this->load->view('user/edit', array('user' => $user, 'status' => $status));
        $this->load->view('footer', '');
    }*/

}

//__END__