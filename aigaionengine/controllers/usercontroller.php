<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usercontroller extends Controller {

    public function Usercontroller() {
        parent::Controller();
        $subnav = array(
            '/user/' => __('All users'),
            '/bookmarklist' => __('My bookmarks'),
        );
        $userlogin = getUserLogin();
        $subnav['/user/'.$userlogin->loginName().'/edit'] = __('My preferences');
        $this->load->vars(array('subnav' => $subnav));
    }

    /**
     *
     */
    public function index ($id=False, $action=False) {
        if ($id == False) {
            restrict_to_users(__('You must be logged in to view the users’ page.'));
            $query = $this->db->from('users')->join('usergrouplink', 'users.user_id = usergrouplink.user_id')
                          ->where('users.type', 'external')
                          ->order_by('usergrouplink.group_id asc')->order_by('users.login asc')
                          ->get();
            $users = $query->result_array();
            $query = $this->db->select(array('user_id', 'surname', 'firstname', 'abbreviation'))
                              ->from('users')->where('type', 'group')->where('theme', 'puma')
                              ->order_by('surname asc')
                              ->get();
            $groups = $query->result_array();
            $grouped_users = array();
            foreach ($groups as $group) {
                $grouped_users[$group['user_id']] = array('users'=>array(), 'name'=>$group['surname'],
                    'firstname'=>$group['firstname'], 'abbreviation'=>$group['abbreviation']);
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
            if ($user == null) {
                appendErrorMessage(sprintf(__('User %s does not exist.'), h($id)));
                redirect('');
            } elseif ($action && ! in_array($action, array('contact', 'edit', 'is_online', 'publications'))) {
                appendErrorMessage(sprintf(__('Unknown action requested: %s.'), h($action)));
                redirect('');
            } elseif ($id != 'admin') {
                restrict_to_users(__('You must be logged in to view this user’s page.'));
            }
            $groups = array();
            foreach ($user->group_ids as $gid) {
                $groups[] = $this->group_db->getByID($gid);
            }
            $user->groups = $groups;

            if (! $action) {
                $Q = $this->db->distinct('*')->limit(20)
                     ->where('user_id', $user->user_id)->get('publication');
                $pubs = array();
                foreach ($Q->result() as $row) {
                  $next = $this->publication_db->getFromRow($row);
                  if ($next != null) {
                    $pubs[] = $next;
                  }
                }
            
                $this->load->view('header', array('title'=>sprintf(__('User %s'), $id)));
                $this->load->view('user/full', array('user' => $user, 'publications' => $pubs));
                $this->load->view('footer');
            } else {
                $this->$action($id, $user);
            }
        }
    }

    /**
     *
     */
    protected function publications($id, $user) {
        if ($id != 'admin') {
            restrict_to_users(__('Please log in to contact other users.'));
        }
        $order = $this->uri->segment(4,'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
          $order='';
        }
        $page = $this->uri->segment(5,0);

        $userlogin = getUserLogin();
        $content = array('header' => sprintf(__('All publications uploaded by %s %%s'), $user->abbreviation));
        $orderby='actualyear DESC, cleantitle ASC';
        switch ($order) {
            case 'type':
                $content['header'] = sprintf($content['header'], __('sorted by journal and type'));
                $orderby='pub_type ASC, cleanjournal ASC, actualyear DESC, cleantitle ASC'; //funny thing: article is lowest in alphabetical order, so this ordering is enough...
                break;
            case 'recent':
                $content['header'] = sprintf($content['header'], __('sorted by recency'));
                $orderby='pub_id DESC';
                break;
            case 'title':
                $content['header'] = sprintf($content['header'], __('sorted by title'));
                $orderby='cleantitle ASC';
                break;
            case 'author':
                $content['header'] = sprintf($content['header'], __('sorted by author'));
                $orderby='cleanauthor ASC, actualyear DESC';
                break;
            default:
                $content['header'] = sprintf($content['header'], '');
        }

        $limitOffset = False;
        if ($userlogin->getPreference('liststyle') > 0) {
            //set these parameters when you want to get a good multipublication list display
            $content['multipage']       = True;
            $content['pubCount']        = $this->topic_db->getPublicationCountForTopic('1');
            $content['currentpage']     = $page;
            $content['multipageprefix'] = 'user/'.$id.'/publications/'.$order.'/';
            $limitOffset = $userlogin->getPreference('liststyle') * $page;
        }
        $content['order'] = $order;
        $content['sortPrefix'] = 'user/'.$id.'/publications/%s';

        $this->db->distinct('*')->limit(20)->order_by($orderby)->where('user_id', $user->user_id);
        if ($limitOffset) {
          $this->db->limit($userlogin->getPreference('liststyle'), $limitOffset);
        }
        $Q = $this->db->get('publication');
        $content['publications'] = array();
        foreach ($Q->result() as $row) {
            $next = $this->publication_db->getFromRow($row);
            if ($next != null) {
                $content['publications'][] = $next;
            }
        }

        $this->load->view('header', array('title' => sprintf(__('Publications Uploaded by %2'), $user->abbreviation)));
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }

    /**
     *
     */
    protected function contact ($id, $user) {
        if ($id != 'admin') {
            restrict_to_users(__('Please log in to contact other users.'));
        }
        $this->load->library('form_validation');
        $this->form_validation->set_message('required', __('A %s is required.'));
        $this->form_validation->set_message('max_length', __('The %s may not exceed 511 characters.'));
        $this->form_validation->set_error_delimiters('<p class="error">', '</p>');
        $this->form_validation->set_rules('message', __('message'), 'required|max_length[511]');

        $data = array('user' => $user);
        $data['success'] = $this->form_validation->run();

        if ($data['success']) {
            $userlogin = getUserLogin();
            $this->load->library('email');
            $config = array();
            $config['protocol'] = 'sendmail';
            $config['mailpath'] = '/usr/sbin/sendmail';
            $this->email->initialize($config);
            $subject = $this->input->post('subject');
            if (! $subject) {
                $subject = sprintf(__('A message from Puma user %s %s'),
                                   $userlogin->preferences['firstname'],
                                   $userlogin->preferences['surname']);
            }

            $to = $user->email;
            if (! $to) { $to = 'manuel.strehl@physik.uni-r.de'; }
            $from = $this->input->post('email');
            if ($from == '') { $from = $userlogin->preferences['email']; }
            $this->email->from($from, $this->input->post('name'));
            $this->email->to($to);
            $this->email->subject($subject);
            $this->email->message($this->input->post('message'));
            if (! $data['success_send'] = $this->email->send()) {
                appendErrorMessage(__('There was an error trying to send the e-Mail.'), 'severe');
            }
        }

        $this->load->view('header', array('title'=>sprintf(__('Contact user %s'), $id)));
        $this->load->view('user/contact', $data);
        $this->load->view('footer');
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

        $this->load->vars(array('subnav_current' => '/user/'.$user->login.'/edit'));
        $this->load->view('header', array('title'=>sprintf(__('Edit user %s'), $id)));
        $this->load->view('user/edit', $data);
        $this->load->view('footer');
    }
    
    /**
     * Check if a user was online in the last 3 minutes
     */
    protected function is_online ($id, $user) {
        restrict_to_users('');
        $this->output->set_header('Content-Type: text/javascript; charset=utf-8');
        $this->output->set_output($user->is_online()? 'true' : 'false');
    }
    
    /**
     *
     */
    public function option($method, $key, $value=Null) {
        restrict_to_users();
        $option = option_get($key);
         if ($method == 'set') {
            option_set($key, $value);
        }
        if (is_numeric($option)) { $option = (float)$option; }
        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript; charset=utf-8');
            $this->output->set_output(json_encode($option));
        } else {
            back_to_referrer(__('The option was successfully set.'));
        }
    }
    
    /**
     *
     */
    public function group($id) {
        $query = $this->db->select(array('user_id', 'surname', 'firstname', 'abbreviation'))
                          ->from('users')->where('type', 'group')->where('theme', 'puma')
                          ->where('abbreviation', $id)->limit(1)
                          ->get();
        $group = $query->result_array();
        if (! $group || count($group) != 1) {
            appendErrorMessage(sprintf(__('Group %s does not exist.'), h($id)));
            redirect('');
        }
        $query = $this->db->from('users')->join('usergrouplink', 'users.user_id = usergrouplink.user_id')
                      ->where('users.type', 'external')->where('usergrouplink.group_id', $group[0]['user_id'])
                      ->order_by('users.login asc')
                      ->get();
        $users = $query->result_array();
        $this->load->vars(array('nav_current'=>'user', 'subnav_current' => '/user/'));
        $this->load->view('header', array('title'=>sprintf(__('Group %s'), $id)));
        $this->load->view('user/group', array('group'=>$group[0], 'users'=>$users));
        $this->load->view('footer');
    }

}

//__END__
