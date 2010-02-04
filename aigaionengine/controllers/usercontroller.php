<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usercontroller extends Controller {

    public function Usercontroller() {
        parent::Controller();
    }

    /**
     *
     */
    public function index ($id=False, $action=False) {
        $userlogin = getUserLogin();
        if ($id == False) {
            if (! $userlogin->isLoggedIn() || $userlogin->isAnonymous()) {
                appendErrorMessage(__('You must be logged in to view the users’ page.'));
                redirect('');
            }
            $query = $this->db->from('users')->join('usergrouplink', 'users.user_id = usergrouplink.user_id')
                          ->where('users.type', 'normal')
                          ->order_by('usergrouplink.group_id asc')->order_by('users.login asc')
                          ->get();
            $users = $query->result_array();
            $query = $this->db->select(array('user_id', 'surname', 'abbreviation'))
                              ->from('users')->where('type', 'group')->where('theme', 'Puma')
                              ->order_by('surname asc')
                              ->get();
            $groups = $query->result_array();
            $grouped_users = array();
            foreach ($groups as $group) {
                $grouped_users[$group['user_id']] = array('users'=>array(), 'name'=>$group['surname'], 'abbreviation'=>$group['abbreviation']);
                foreach ($users as $user) {
                    if ($user['group_id'] == $group['user_id']) {
                        $grouped_users[$group['user_id']]['users'][] = $user;
                    }
                }
            }
            $this->load->view('header', array('title'=>__('All Users')));
            $this->load->view('user/all', array('groups' => $grouped_users));
            $this->load->view('footer');
        } else {
            $user = $this->user_db->getByLogin($id);
            $userlogin = getUserLogin();
            if ($user == null) {
                appendErrorMessage(sprintf(__('User %s does not exist.'), h($id)));
                redirect('');
            } elseif ($action && ! in_array($action, array('contact', 'edit'))) {
                appendErrorMessage(sprintf(__('Unknown action requested: %s.'), h($action)));
                redirect('');
            } elseif ((!$userlogin->isLoggedIn() || $userlogin->isAnonymous()) && $id != 'admin') {
                appendErrorMessage(__('You must be logged in to view this user’s page.'));
                redirect('');
            }
            $groups = array();
            foreach ($user->group_ids as $gid) {
                $groups[] = $this->group_db->getByID($gid);
            }
            $user->groups = $groups;

            if (! $action) {
                $this->load->view('header', array('title'=>sprintf(__('User %s'), $id)));
                $this->load->view('user/full', array('user' => $user));
                $this->load->view('footer');
            } else {
                $this->$action($id, $user, $userlogin);
            }
        }
    }

    /**
     *
     */
    protected function contact ($id, $user, $userlogin) {
        if ((!$userlogin->isLoggedIn() || $userlogin->isAnonymous()) && $id != 'admin') {
            appendErrorMessage(__('Please log in to contact other users.'));
            redirect('');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_message('required', __('A %s is required.'));
        $this->form_validation->set_message('max_length', __('The %s may not exceed 511 characters.'));
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->form_validation->set_rules('message', __('message'), 'required|max_length[511]');

        $data = array('user' => $user);
        $data['success'] = $this->form_validation->run();

        if ($data['success']) {
            $this->load->library('email');
            $subject = $this->input->post('subject');
            if (! $subject) {
                $subject = sprintf(__('A message from Puma user %s %s'),
                                   $userlogin->preferences['firstname'],
                                   $userlogin->preferences['surname']);
            }

            $this->email->from($userlogin->preferences['email']);
            $this->email->to($user->email);
            $this->email->subject($subject);
            $this->email->message($this->input->post('message'));
            $data['success_send'] = $this->email->send();
        }

        $this->load->view('header', array('title'=>sprintf(__('Contact user %s'), $id)));
        $this->load->view('user/contact', $data);
        $this->load->view('footer');
    }

    /**
     *
     */
    protected function edit ($id, $user, $userlogin) {
        if ((!$userlogin->hasRights('user_edit_all'))
             &&
            (!$userlogin->hasRights('user_edit_self') || ($userlogin->userId() != $user->user_id))) {
            appendErrorMessage(__('You are not allowed to edit other users.'));
            redirect('');
        }
        $this->load->library('form_validation');
        $this->form_validation->set_message('required', __('The field %s is required.'));
        $this->form_validation->set_message('max_length', __('The field %s is too long.'));
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');

        $rules = array('password' => 'matches[password_check]',
                       'password_check' => 'matches[password]');
        $this->form_validation->set_rules('password', __('Password'), 'matches[password_check]');
        $this->form_validation->set_rules('password_check', __('Password repeat'), 'matches[password]');

        $data = array('user' => $user);
        $data['success'] = $this->form_validation->run();

        if ($data['success']) {
            if ($user->edit($_POST)) {
                appendMessage(__('The account was successfully updated.'));
            } else {
                appendErrorMessage(__('The changes could not be stored.'), 'severe');
            }
        } else {
            appendErrorMessage(validation_errors());
        }

        $this->load->view('header', array('title'=>sprintf(__('Edit user %s'), $id)));
        $this->load->view('user/edit', $data);
        $this->load->view('footer');
    }
    
    /**
     *
     */
    public function group($id) {
        $query = $this->db->select(array('user_id', 'surname', 'abbreviation'))
                          ->from('users')->where('type', 'group')->where('theme', 'Puma')
                          ->where('abbreviation', $id)->limit(1)
                          ->get();
        $group = $query->result_array();
        if (! $group || count($group) != 1) {
            appendErrorMessage(sprintf(__('Group %s does not exist.'), h($id)));
            redirect('');
        }
        $query = $this->db->from('users')->join('usergrouplink', 'users.user_id = usergrouplink.user_id')
                      ->where('users.type', 'normal')->where('usergrouplink.group_id', $group[0]['user_id'])
                      ->order_by('users.login asc')
                      ->get();
        $users = $query->result_array();
        $this->load->view('header', array('title'=>sprintf(__('Group %s'), $id), 'nav_current'=>'user'));
        $this->load->view('user/group', array('group'=>$group[0], 'users'=>$users));
        $this->load->view('footer');
    }

}

//__END__