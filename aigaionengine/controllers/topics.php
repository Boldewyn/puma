<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Topics extends Controller {

    function Topics() {
        parent::Controller();
        $this->load->vars(array(
            'nav_current' => 'explore',
            'subnav_current' => '/topics',
            'subnav' => array(
                '/explore/' => __('All'),
                '/topics' => __('Topics'),
                '/keywords' => __('Tags'),
                '/publications' => __('Publications'),
                '/authors' => __('Authors'),
            ),
        ));
    }

    /**
     * Pass control to the topics/browse/ controller
     */
    function index() {
        $this->browse();
    }

    /** Simple browse page for Topics.
        This controller returns a full web page of the subscribed topics
        Third parameter selects root topic_id for tree (default:1) */
    function browse($root_id=1) {
        //no rights check here: anyone can (try) to browse topics (though not all topics may be visible)
        $userlogin = getUserLogin();
        $user = $this->user_db->getByID($userlogin->userId());
        $config = array('onlyIfUserSubscribed'=>True,
                         'flagCollapsed'=>True,
                         'user'=>$user,
                         'includeGroupSubscriptions'=>True
                        );
        $root = $this->topic_db->getByID($root_id, $config);
        if ($root == null) {
            appendErrorMessage(__('Browse topics: non-existing id passed.'));
            redirect('');
        }
        $this->load->view('header', array('title' => __('Browse topic tree')));
        $this->load->view('site/stats', array('embed' => True));
        $this->load->vars(array('subviews'  => array('topics/maintreerow'=>array('useCollapseCallback'=>True)),
                          'subscribed' => True));
        $this->load->view('topics/index', array('all' => false, 'topics' => $root->getChildren()));
        $this->load->view('footer');
    }

    /** Simple browse page for Topics.
        This controller returns a full web page of ALL available topics
        Third parameter selects root topic_id for tree (default:1) */
    function all($root_id=1) {
        //no rights check here: anyone can (try) to browse topics (though not all topics may be visible)
        $userlogin = getUserLogin();
        $user = $this->user_db->getByID($userlogin->userId());
        $config = array('onlyIfUserSubscribed'=>False,
                         'flagCollapsed'=>True,
                         'user'=>$user,
                         'includeGroupSubscriptions'=>True);
        $root = $this->topic_db->getByID($root_id, $config);
        if ($root == null) {
            appendErrorMessage(__('Browse topics: non-existing id passed.'));
            redirect('/topics');
        }
        $this->load->view('header', array('title' => __('Browse topic tree (include all topics)')));
        $this->load->view('site/stats', array('embed' => 'true'));
        $this->load->vars(array('subviews'  => array('topics/maintreerow'=>array('useCollapseCallback'=>True)),
                          'subscribed' => False));
        $this->load->view('topics/index', array('all' => true, 'topics' => $root->getChildren()));
        $this->load->view('footer');
    }
    /**
     * Entry point for deleting a topic.
     *
     * Depending on whether 'commit' is specified in the url, confirmation may
     * be requested before actually deleting.
     */
    function delete($topic_id, $commit='') {
        $config = array();
        $topic = $this->topic_db->getByID($topic_id, $config);
        if ($topic==null) {
            appendErrorMessage(__('Delete topic: non-existing id passed.'));
            redirect('/topics');
        }

        //besides the rights needed to READ this topic, checked by topic_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();
        restrict_to_right(($userlogin->hasRights('topic_edit') || $this->accesslevels_lib->canEditObject($topic)),
            __('Delete topic'), '/topics');

        if ($commit=='commit') {
            //do delete, redirect somewhere
            $topic->delete();
            redirect('/topics');
        } else {
            $this->load->view('header', array('title' => __('Delete topic')));
            $this->load->view('confirm', array(
                'url' => 'topics/delete/'.$topic->topic_id.'/commit',
                'question' => sprintf(__('Are you sure, that you want to delete the topic &ldquo;%s&rdquo;?'), h($topic->name)),
                'cancel_url' => 'topics/single/'.$topic->topic_id,
            ));
            $this->load->view('footer');
        }
    }

    /**
     * Entrypoint for adding a topic. Shows the necessary form.
     */
    function add($parent_id=-1) {
        restrict_to_right('topic_edit', __('Add topic'), '/topics');
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="error">'.__('Changes not committed: '), '</p>');
        $config = array();
        $parent = $this->topic_db->getByID($parent_id, $config);

        $this->load->vars(array(
            'subnav' => array(
                '/import/' => __('Import'),
                '/publications/add' => __('Publication'),
                '/topics/add' => __('Topic'),
                '/authors/add' => __('Author'),
            ),
            'subnav_current' => '/topics/add',
            'nav_current' => 'create',
        ));
        $this->load->view('header', array('title' => __('New Topic')));
        $this->load->view('topics/edit' , array('parent'=>$parent));
        $this->load->view('footer');
    }

    /**
     * Entrypoint for editing a category. Shows the necessary form.
     */
    function edit($topic_id=1) {
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="error">'.__('Changes not committed: '), '</p>');
        if ($topic_id==1) {
            redirect('/topics');
        }
        $config = array();
        $topic = $this->topic_db->getByID($topic_id, $config);
        if ($topic==null) {
            appendErrorMessage(__('Edit topic: non-existing id passed.'));
            redirect('/topics');
        }
        //besides the rights needed to READ this topic, checked by topic_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();
        restrict_to_right(($userlogin->hasRights('topic_edit') || $this->accesslevels_lib->canEditObject($topic)),
            __('Edit topic'), '/topics');

        $this->load->vars(array(
            'subnav' => array(
                '/import/' => __('Import'),
                '/publications/add' => __('Publication'),
                '/topics/add' => __('Topic'),
                '/authors/add' => __('Author'),
            ),
            'subnav_current' => '/topics/add',
            'nav_current' => 'create',
        ));
        $this->load->view('header', array('title' => __('Edit topic')));
        $this->load->view('topics/edit' , array('topic'=>$topic));
        $this->load->view('footer');
    }

    /** Simple view page for single topic.
        This controller returns a full web page.
        Third parameter selects topic_id (default:1)
        or topic name path... for the latter, also see libraries/topic_db#getTopicIDFromNames()
        If topic 1 is chosen, user is redirected to browse/ controller */
    function single($topic_name=1) {
        $topic_structure = array();
        $url_segment = 3;

        //Checks if the controller is given a topic_id or a topic structure
        if(is_numeric($topic_name)) {
            $topic_id = $topic_name;
        } else {
            //breaks down parts of the url into an array of topics and sub topics. STOPS when either the order (e.g. year, type etc) is reached or when the whole url is parsed.
            while($topic_name != '' && $topic_name != 'year' &&
                  $topic_name != 'type' && $topic_name != 'recent' &&
                  $topic_name != 'title' && $topic_name != 'author') {
                $topic_structure[] = $topic_name;
                $url_segment++;
                $topic_name = $this->uri->segment($url_segment, '');
            }

            //gets the topicID(s) and checks if it exists, is unique or is a duplicate.
            //If it is a duplicate or if it does not exist, the method fails and outputs and error message.
            $topic_ids = $this->topic_db->getTopicIDFromNames($topic_structure, array());
            if(count($topic_ids) == 1) {
                $topic_id = $topic_ids[0];
            } elseif(count($topic_ids) > 1) {
                appendErrorMessage(sprintf(__('Topic structure is not unique in %s.'), site_title()));
                redirect('/topics');
            } else {
                appendErrorMessage(sprintf(__('Topic structure does not exist in %s.'), site_title()));
                redirect('/topics');
            }
        }

        $order   = $this->uri->segment($url_segment+1, 'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }
        $page   = $this->uri->segment($url_segment+2,0);

        if ($topic_id==1) {
            redirect('/topics');
        }
        $config = array();
        $topic = $this->topic_db->getByID($topic_id, $config);
        $userlogin=getUserLogin();
        if ($topic==null) {
            appendErrorMessage(__('Show topic: non-existing id passed.'));
            redirect('/topics');
        }

        //no additional rights check beyond those in the topic_db->getbyID, as anyone can view topics as long
        // as he has the right access levels
        $this->load->helper('publication');

        $content = array('header' => sprintf(__('Publications for topic &ldquo;%s&rdquo; %%s'), $topic->name));
        switch ($order) {
            case 'type':
                $content['header'] = sprintf($content['header'], __('sorted by journal and type'));
                break;
            case 'recent':
                $content['header'] = sprintf($content['header'], __('sorted by recency'));
                break;
            case 'title':
                $content['header'] = sprintf($content['header'], __('sorted by title'));
                break;
            case 'author':
                $content['header'] = sprintf($content['header'], __('sorted by first author'));
                break;
            default:
                $content['header'] = sprintf($content['header'], '');
        }
        //get keyword list
        if ($userlogin->getPreference('liststyle')>0) {
            //set these parameters when you want to get a good multipublication list display
            $content['multipage']       = True;
            $content['pubCount']        = $this->topic_db->getPublicationCountForTopic($topic_id);
            $content['currentpage']     = $page;
            $content['multipageprefix'] = 'topics/single/'.$topic_id.'/'.$order.'/';
        }
        $content['publications']    = $this->publication_db->getForTopic($topic_id,$order,$page);
        $content['order'] = $order;
        $content['sortPrefix'] = 'topics/single/'.$topic_id.'/%s';

        $this->load->view('header', array('title' => sprintf(__('Topic: %s'), h($topic->name))));
        $this->load->view('topics/full', array('topic' => $topic));
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }

    /*
        This controller takes the topic either (1) as url containing the names of the topics and subtopics
        as for instance [aigion_root]/index.php/topics/embedClean/top/subtopic_1/.../subtopic_n
        or (2) the topicID, for instance: [aigion_root]/index.php/topics/embedClean/18

        Topics CANNOT be named: year, type, recent, title, author, msc or any number. The controller could fail if these topic names are present.

        The method topic_db->getTopicID is used to translate this url into a unique topic ID.

        Contribution by {\O}yvind
    */
    function embedClean($topic_name=1) {
        $topic_structure = array();
        $url_segment = 3;

        //Checks if the controller is given a topic_id or a topic structure
        if(is_numeric($topic_name)) {
            $topic_id = $topic_name;
        } else {
            //breaks down parts of the url into an array of topics and sub topics. STOPS when either the order (e.g. year, type etc) is reached or when the whole url is parsed.
            while($topic_name != '' && $topic_name != 'year' &&
                  $topic_name != 'type' && $topic_name != 'recent' &&
                  $topic_name != 'title' && $topic_name != 'author') {
                $topic_structure[] = $topic_name;
                $url_segment++;
                $topic_name = $this->uri->segment($url_segment,'');
            }

            //gets the topicID(s) and checks if it exists, is unique or is a duplicate.
            //If it is a duplicate or if it does not exist, the method fails and outputs and error message.
            $topic_ids = $this->topic_db->getTopicIDFromNames($topic_structure, array());
            if(count($topic_ids) == 1) {
                $topic_id = $topic_ids[0];
            } elseif(count($topic_ids) > 1) {
                exit(sprintf(__('Topic structure is not unique in %s.'), site_title()));
            } else {
                exit(sprintf(__('Topic structure is not unique in %s.'), site_title()));
            }
        }

        $order   = $this->uri->segment($url_segment,'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }
        $page   = $this->uri->segment($url_segment+1,0);
        $topic = $this->topic_db->getByID($topic_id, array());
        $userlogin=getUserLogin();
        if ($topic==null) {
            exit(__('Embed topic: non-existing id passed.'));
        }

        $this->load->helper('publication');

        $content = array('header' => sprintf(__('Publications for topic &ldquo;%s&rdquo; %%s'), $topic->name));
        switch ($order) {
            case 'type':
                $content['header'] = sprintf($content['header'], __('sorted by journal and type'));
                break;
            case 'recent':
                $content['header'] = sprintf($content['header'], __('sorted by recency'));
                break;
            case 'title':
                $content['header'] = sprintf($content['header'], __('sorted by title'));
                break;
            case 'author':
                $content['header'] = sprintf($content['header'], __('sorted by first author'));
                break;
            default:
                $content['header'] = sprintf($content['header'], '');
        }
        $content['publications']    = $this->publication_db->getForTopic($topic_id,$order);
        $content['order'] = $order;

        $this->load->view('topics/clean', array('topic' => $topic));
        $this->load->view('publications/listClean', $content);
    }

    /**
     * Commit changes to a topic
    */
    function commit() {
        $this->load->library('validation');
        $this->validation->set_error_delimiters('<p class="error">'.__('Changes not committed: '), '</p>');

        //get data from POST
        $topic = $this->topic_db->getFromPost();

        //check if fail needed: was all data present in POST?
        if ($topic == null) {
            appendErrorMessage(__('Commit topic: no data to commit.'));
            redirect('/topics');
        }

//             the access level checks are of course not tested here,
//             but in the commit action, as the client can have sent 'wrong' form data

        //validate form values;
        //validation rules:
        //  -no topic with the same name and a different ID can exist
        //  -name is required (non-empty)
        $this->validation->set_rules(array('name' => 'required'));
        $this->validation->set_fields(array('name' => __('Topic Name')));

        if ($this->validation->run() == FALSE) {
            //return to add/edit form if validation failed
            $this->load->view('header', array('title' => __('Topic')));
            $this->load->view('topics/edit', array('topic' => $topic,
                                                   'action' => $this->input->post('action')));
            $output .= $this->load->view('footer');
        } else {
            //if validation was successfull: add or change.
            $success = False;
            if ($this->input->post('action') == 'edit') {
                //do edit
                $success = $topic->update();
            } else {
                //do add
                $success = $topic->add();
            }
            if (!$success) {
                //this is quite unexpected, I think this should not happen if we have no bugs.
                appendErrorMessage(__('Commit topic: an error occurred.'), 'severe');
            }
            redirect('topics/single/'.$topic->topic_id);
        }
    }

    /**
     * Collapses or expands a topic for the logged user
     *
     * Is normally called async, without processing the returned partial,
     * by clicking one of the collapse or expand buttons in a topic tree
     * rendered by subview 'maintreerow' with argument 'useCollapseCallback'=>True
     */
    function collapse($topic_id=-1, $collapse='1') {
        $config = array();
        $topic = $this->topic_db->getByID($topic_id, $config);
        if ($topic == null) {
            echo '<p class="error">',__('Collapse topic: non-existing id passed.'),'</p>';
        } else {
            //do collapse
            if ($collapse == '1') {
                $topic->collapse();
            } else {
                $topic->expand();
            }
            echo '<div></div>';
        }
    }

    /**
     * Sends the publications for the selected topic to the spesified email address(es).
     */
    function exportEmail() {
        restrict_to_right('export_email', __('Export through email'), '/topics');
        $this->load->library('email_export');

        $email_pdf = $this->input->post('email_pdf');
        $email_bibtex = $this->input->post('email_bibtex');
        $email_ris = $this->input->post('email_ris');
        $email_address = $this->input->post('email_address');
        $email_formatted = $this->input->post('email_formatted');
        $order='year';

        $recipientaddress   = $this->uri->segment(4,-1);
        $topic_id   = $this->uri->segment(3,-1);
        $publications = $this->publication_db->getForTopic($topic_id);

        if (!isset($topic_id) || $topic_id == -1) {
            appendErrorMessage(__('Export topic: non-existing id passed.'));
            redirect('/topics');
        }

        if(!(($email_pdf !='' || $email_bibtex !='' || $email_ris!='' || $email_formatted!='') && $email_address != '')) {
            /* IF the recipient's address is missing or if none of the data formats are selected THEN show the format selection form. */
            $content = array(
                'attachmentsize' => $this->email_export->attachmentSize($publications),
                'controller'     => 'topics/exportEmail/'.$topic_id);
            if(isset($recipientaddress)) {
                $content['recipientaddress'] = str_replace(array('AROBA', 'KOMMA'), array('@', ','), $recipientaddress);
            }

            $this->load->view('header', array('title' => __('Select export format')));
            $this->load->view('export/chooseformatEmail', $content);
            $this->load->view('footer');
            return;
        } else {
            /* ELSE process the request and send the email. */
            $this->load->helper('publication');

            $content = array(
                'header' => __('Export by email'),
                'publications' => $publications,
                'order' => $order);

            $messageBody = sprintf(__('Export from %s'), site_title());

            if($email_formatted || $email_bibtex) {
                $this->publication_db->enforceMerge = True;
                $publicationMap = $this->publication_db->getForTopicAsMap($topic_id);
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported from topic');
                $exportdata['exportEmail']   = true;
            }
            if($email_formatted) {
                /* FORMATTED text is added first. HTML format is selected because this gave nice readable text without having to change or make any views. */
                $messageBody .= "\n";
                $messageBody .= __('Formatted');
                $messageBody .= "\n";

                $exportdata['format'] = 'html';
                $exportdata['sort'] = $this->input->post('sort');
                $exportdata['style'] = $this->input->post('style');
                $messageBody .= strip_tags($this->load->view('export/'.'formattedEmail', $exportdata, True));
            }
            if($email_bibtex) {
                /* BIBTEX added. */
                $messageBody .= "\n";
                $messageBody .= 'BibTeX';
                $messageBody .= "\n";
                $messageBody .= strip_tags($this->load->view('export/'.'bibtexEmail', $exportdata, True));
            }
            if($email_ris) {
                /* RIS added. */
                $messageBody .= "\n";
                $messageBody .= 'RIS';
                $messageBody .= "\n";

                $this->publication_db->suppressMerge = False;
                $publicationMap = $this->publication_db->getForTopicAsMap($topic_id);
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                #send to right export view
                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported from topic');
                $exportdata['exportEmail']   = true;

                $messageBody .= strip_tags($this->load->view('export/'.'risEmail', $exportdata, True));
            }

            /* If PDFs are not selected the publication array is removed and no attachments will be added. */
            if(!$email_pdf) {
                $publications = array();
            }

            /* Sending MAIL. */
            if($this->email_export->sendEmail($email_address, $messageBody, $publications)) {
                $this->load->view('header', array('title' => __('Topic export')));
                $this->load->view('put', array('data' => '<p class="info">'.__('Mail sent successfully').'</p>'));
                $this->load->view('footer');
            } else {
                appendErrorMessage(__('Something went wrong when exporting the publications. Did you input a correct email address?'));
                redirect('/topics');
            }
        }
    }

}

//__END__
