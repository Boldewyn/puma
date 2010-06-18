<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Controller {

    function Users() {
        parent::Controller();
        //restrict_to_admins();
    }

    /** Pass control to the users/edit/(logged user) controller */
    function index() {
        $userlogin = getUserLogin();
        redirect('users/edit/'.$userlogin->userId());
    }

    /**
    users/manage

    Entry point for managing user accounts.

    Fails with error message when one of:
        insufficient user rights

    Parameters passed via URL segments:
        none

    Returns:
        A full HTML page with all a list of all users and groups
    */
    function manage() {
        restrict_to_right('user_edit_all', __('Manage accounts'));

        $this->load->view('header', array('title' => __('User')));

        $output = '<div class="optionbox">['.anchor('users/add',__('add a new user'))."]</div>";
        $output .= "
            <h3>".__("Users")."</h3>
            <ul>
            ";
        foreach ($this->user_db->getAllNormalUsers() as $user) {
            $output .= "<li>".$this->load->view('users/summary',
                                          array('user'   => $user),
                                          true)."</li>";
        }
        foreach ($this->user_db->getAllExternalUsers() as $user) {
            $output .= "<li>".$this->load->view('users/summary',
                                          array('user'   => $user),
                                          true)."</li>";
        }
        foreach ($this->user_db->getAllAnonUsers() as $user) {
            $output .= "<li>".$this->load->view('users/summary',
                                          array('user'   => $user),
                                          true)."</li>";
        }
        $output .= '</ul>';
        $output .= '<div class="optionbox">['.anchor('groups/add',__('add a new group'))."]</div>";
        $output .= "<h3>".__("Groups")."</h3>
            <ul>";
        foreach ($this->group_db->getAllGroups() as $group) {
            $output .= "<li>".$this->load->view('groups/summary',
                                          array('group'   => $group),
                                          true)."</li>";
        }
        $output .= "</ul>";
        $output .= '<div class="optionbox">['.anchor('rightsprofiles/add',__('add a new rightsprofile'))."]</div>";
        $output .= "<h3>".__("Rights profiles")."</h3>
            <ul>";
        foreach ($this->rightsprofile_db->getAllRightsprofiles() as $rightsprofile) {
            $output .= "<li>".$this->load->view('rightsprofiles/summary',
                                          array('rightsprofile'   => $rightsprofile),
                                          true)."</li>";
        }
        $output .= "</ul>";

        $this->load->view('put', array('data' => $output));
        $this->load->view('footer');
    }

    /**
    users/single

    Entry point for viewing one user account.

    Fails with error message when one of:
        a non-existing user_id requested
        insufficient user rights

    Parameters passed via URL segments:
        3rd: user_id, the id of the user to be viewed

    Returns:
        A full HTML page with all information about the user
    */
    function single($user_id=-1) {
        $user = $this->user_db->getByID($user_id);
        if ($user==null) {
            appendErrorMessage(__('View user: non-existing id passed.'));
            redirect('');
        }
        //no additional rights check. Only, in the view the edit links may be suppressed depending on the user rights
        $this->load->view('header', array('title' => __('User')));
        $this->load->view('users/full', array('user' => $user));
        $this->load->view('footer');
    }


    /**
    users/add

    Entry point for adding a user account.

    Fails with error message when one of:
        insufficient user rights

    Parameters passed via URL segments:
        none

    Returns:
        A full HTML page with an 'add user' form
    */
    function add() {
        restrict_to_right('user_edit_all', __('Add user'));

        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        $this->load->view('header', array('title' => __('User')));
        $this->load->view('users/edit');
        $this->load->view('footer');
    }

    /**
    users/edit

    Entry point for editing a user account.

    Fails with error message when one of:
        non-existing user_id requested
        insufficient user rights

    Parameters passed via URL segments:
        3rd: user_id, the id of the user to be edited

    Returns:
        A full HTML page with an 'edit user' form
    */
    function edit($user_id=-1) {
        $user = $this->user_db->getByID($user_id);
        if ($user==null) {
            appendErrorMessage(__('Edit user: non-existing id passed.'));
            redirect('');
        }

        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        //check user rights
        $userlogin = getUserLogin();
        if (!$userlogin->hasRights('user_edit_all') &&
            (!$userlogin->hasRights('user_edit_self') || $userlogin->userId() != $user->user_id)) {
            appendErrorMessage(__('Edit account: insufficient rights.'));
            redirect('');
        }

        $this->load->view('header', array('title' => __('User')));
        $this->load->view('users/edit', array('user'=>$user));
        $this->load->view('footer');
    }

    /**
    users/delete

    Entry point for deleting a user.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    deleting.

    Fails with error message when one of:
        delete requested for non-existing user
        insufficient user rights

    Parameters passed via URL segments:
        3rd: user_id, the id of the to-be-deleted-user
        4th: if the 4th segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be
             called with 'commit' specified

    Returns:
        A full HTML page showing a 'request confirmation' form for the delete action, if no 'commit' was specified
        Redirects somewhere (?) after deleting, if 'commit' was specified
    */
    function delete($user_id, $commit='') {
        $user = $this->user_db->getByID($user_id);
        if ($user==null) {
            appendErrorMessage(__('Delete user: non-existing id passed.'));
            redirect('');
        }

        //check user rights
        $userlogin = getUserLogin();
        if (! $userlogin->hasRights('user_edit_all')) {
            appendErrorMessage(__('Delete account: insufficient rights.'));
            redirect('');
        }

        if ($commit=='commit') {
            //do delete, redirect somewhere
            $user->delete();
            redirect('users/manage');
        } else {
            $this->load->view('header',array('title' => __('User'))); 
            $this->load->view('confirm', array(
                'url' => 'users/delete/'.$user->user_id.'/commit',
                'question' => sprintf(__('Are you sure, that you want to delete the user &ldquo;%s&rdquo;?'), h($user->login)),
            ));
            $this->load->view('footer');
        }
    }

    /**
    users/commit

    Fails with error message when one of:
        edit-commit requested for non-existing user
        insufficient user rights

    Parameters passed via POST:
        action = (add|edit)
        and a lot others...

    Redirects to somewhere (?) if the commit was successfull
    Redirects to the edit or add form if the validation of the form values failed
    */
    function commit() {
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="errormessage">'.__('Changes not committed:').' ', '</p>');

        //get data from POST
        $user = $this->user_db->getFromPost();

        //check if fail needed: was all data present in POST?
        if ($user == null) {
            appendErrorMEssage(__('Commit user: no data to commit.'));
            redirect ('');
        }

        //check user rights
        $userlogin = getUserLogin();
        if (!$userlogin->hasRights('user_edit_all') &&
            (!$userlogin->hasRights('user_edit_self') || $userlogin->userId() != $user->user_id)) {
            appendErrorMessage(__('Edit account: insufficient rights.'));
            redirect('');
        }

        //validate form values;
        //validation rules:
        //  -no user with the same login and a different ID can exist
        //  -login is required (non-empty)
        //  -password should match password_check
        $rules = array( 'login'    => 'required',
                        'password' => 'matches[password_check]',
                        'password_check' => 'matches[password]'
                       );
        if (    ($this->input->post('action')=='add')
             && ($this->input->post('type')=='normal')
             && ($this->input->post('disableaccount') != 'disableaccount')) {
            $rules['password'] = 'required';
        }
        $this->validation->set_rules($rules);
        $this->validation->set_fields(array( 'login'    => __('Login Name'),
                                             'password' => __('First Password'),
                                             'password_check' => __('Second Password')
                                           )
                                     );

        if ($this->validation->run() == FALSE) {
            //return to add/edit form if validation failed
            $this->load->view('header', array('title' => __('User')));
            $this->load->view('users/edit', array('user'   => $user,
                                                  'action' => $this->input->post('action')));
            $this->load->view('footer');
        } else {
            //if validation was successfull: add or change.
            $success = False;
            if ($this->input->post('action') == 'edit') {
                //do edit
                $success = $user->update();
            } else {
                //do add
                $success = $user->add();
            }
            if (!$success) {
                //this is quite unexpected, I think this should not happen if we have no bugs.
                appendErrorMessage(sprintf(__('Commit user: an error occurred at &ldquo;%s&rdquo;.'), $this->input->post('action')), 'severe');
                redirect('');
            }
            //redirect somewhere if commit was successfull
            redirect('users/edit/'.$user->user_id);
        }
    }

    /**
    users/topicreview

    Entry point for editing the topic subscriptions for a user

    Fails with error message when one of:
        non-existing user_id requested
        insufficient user rights

    Parameters passed via URL segments:
        3rd: optional user_id of the user to be edited (default: logged user)

    Returns:
        A full HTML page with a 'topic subscription tree'
    */
    function topicreview($user_id=False) {
        $userlogin  = getUserLogin();
        if ($user_id === False) {
            $user_id = $userlogin->userId();
        }
        $user = $this->user_db->getByID($user_id);

        if ($user==null) {
            appendErrorMessage(__('Topic review').': '.__('non-existing id passed').'.<br/>');
            redirect('');
        }

        //check user rights
        $userlogin = getUserLogin();
        if (! $userlogin->hasRights('topic_subscription') ||
            (!$userlogin->hasRights('user_edit_all') && $userlogin->userId() != $user->user_id)) {
            appendErrorMessage(__('Topic subscription: insufficient rights.'));
            redirect('');
        }

        //get output
        $headerdata = array();
        $headerdata['title'] = __('Topic subscription');
        $headerdata['javascripts'] = array('tree.js','prototype.js','scriptaculous.js','builder.js');

        $output = $this->load->view('header', $headerdata, true);

        $config = array('user'=>$user,'includeGroupSubscriptions'=>True);
        $root = $this->topic_db->getByID(1,$config);
        $this->load->vars(array('subviews'  => array('topics/usersubscriptiontreerow'=>array('allCollapsed'=>True))));
        $output .= "<p class='header'>".sprintf(__("Topic subscription for %s (%s)"),$user->login,$user->firstname." ".$user->betweenname." ".$user->surname)."</p>";
        $output .= "<div class='message'>".__("Subscribed topics are highlighted in boldface.")."<br/>".__("To subscribe or unsubscribe a topic and its descendants, click on the topic.")."</div>";
        $output .= "<div id='topictree-holder'>\n<ul class='topictree-list'>\n"
                    .$this->load->view('topics/tree',
                                      array('topics'   => $root->getChildren(),
                                            'showroot'  => True,
                                            'depth'     => -1
                                            ),
                                      true)."</ul>\n</div>\n";

        $output .= $this->load->view('footer','', true);

        //set output
        $this->output->set_output($output);
    }


    /**
    users/subscribe

    Susbcribes a user to a topic. Is normally called async, without processing the
    returned partial, by clicking a subscribe link in a topic tree rendered by
    subview 'usersubscriptiontreerow'

    Fails with error message when one of:
        susbcribe requested for non-existing topic or user
        insufficient user rights

    Parameters passed via URL:
        3rd segment: topic_id
        4rd segment: optional user_id (default: logged user)

    Returns a partial html fragment:
        an empty div if successful
        an div containing an error message, otherwise

    */
    function subscribe($topic_id=-1, $user_id=False) {
        $userlogin = getUserLogin();
        if ($user_id === False) {
            $user_id = $userlogin->userId();
        }

        $error = '';
        $user = $this->user_db->getByID($user_id);
        if ($user == null) {
            $error = __('Subscribe topic: non-existing id passed');
        } else {
            //check user rights
            $userlogin = getUserLogin();
            if (! $userlogin->hasRights('topic_subscription') ||
                (! $userlogin->hasRights('user_edit_all') && $userlogin->userId() != $user->user_id)) {
                $error = __('Topic subscription: insufficient rights');
            } else {
                $config = array('user'=>$user);
                $topic = $this->topic_db->getByID($topic_id,$config);
                if ($topic == null) {
                    $error = __("Subscribe topic: non-existing id passed.");
                } else {
                    $topic->subscribeUser();
                }
            }
        }

        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript');
            if ($error) {
                $this->output->set_output('{"result":false,"message":"'.$error.'"}');
            } else {
                $this->output->set_output('{"result":true}');
            }
        } else {
            if ($error) {
                back_to_referrer($error, '', True);
            } else {
                back_to_referrer(__('The topic has been unsubscribed.'));
            }
        }
    }


    /**
    users/unsubscribe

    Unsusbcribes a user to a topic. Is normally called async, without processing the
    returned partial, by clicking an unsubscribe link in a topic tree rendered by
    subview 'usersubscriptiontreerow'

    Fails with error message when one of:
        unsusbcribe requested for non-existing topic or user
        insufficient user rights

    Parameters passed via URL:
        3rd segment: topic_id
        4rd segment: optional user_id (default: logged user)

    Returns a partial html fragment:
        an empty div if successful
        an div containing an error message, otherwise

    */
    function unsubscribe($topic_id, $user_id=False) {
        $userlogin = getUserLogin();
        if ($user_id === False) {
            $user_id = $userlogin->userId();
        }

        $error = '';
        $user = $this->user_db->getByID($user_id);
        if ($user == null) {
            $error = __('Unsubscribe topic: non-existing id passed');
        } else {
            //check user rights
            $userlogin = getUserLogin();
            if (! $userlogin->hasRights('topic_subscription') ||
                (! $userlogin->hasRights('user_edit_all') && $userlogin->userId() != $user->user_id)) {
                $error = __('Topic subscription: insufficient rights');
            } else {
                $config = array('user'=>$user);
                $topic = $this->topic_db->getByID($topic_id,$config);
                if ($topic == null) {
                    $error = __("Unsubscribe topic: non-existing id passed.");
                } else {
                    $topic->unsubscribeUser();
                }
            }
        }

        if (is_ajax()) {
            $this->output->set_header('Content-Type: text/javascript');
            if ($error) {
                $this->output->set_output('{"result":false,"message":"'.$error.'"}');
            } else {
                $this->output->set_output('{"result":true}');
            }
        } else {
            if ($error) {
                back_to_referrer($error, '', True);
            } else {
                back_to_referrer(__('The topic has been unsubscribed.'));
            }
        }
    }

}

//__END__
