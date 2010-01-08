<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends Controller {

    public function User() {
        parent::Controller();
    }

    /**
     *
     */
    public function index ($id, $action=False) {
        $user = $this->user_db->getByID($id);
        $userlogin = getUserLogin();
        if ($user==null) {
            appendErrorMessage(sprintf(__("User %s does not exist."), h($id)));
            redirect('');
        } elseif ($action && ! in_array($action, array("contact", "edit"))) {
            appendErrorMessage(sprintf(__("Unknown action requested: %s."), h($action)));
            redirect('');
        } elseif ($userlogin->isAnonymous()) {
            appendErrorMessage(__('You must be logged in to view this user&rsquo;s page.'));
            redirect('');
        }


        if (! $action) {
            $this->load->view('header', array("title"=>sprintf(__("User %s"), $id));
            $this->load->view('user/full', array('user' => $user));
            $this->load->view('footer', '');
        } else {
            $this->$action($user);
        }
    }

    /**
     *
     */
    protected function contact ($user) {
    }

    /**
     *
     */
    protected function edit ($user) {
    }

}

//__END__