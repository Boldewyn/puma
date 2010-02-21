<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Publications extends Controller {

    function Publications() {
        parent::Controller();
        $this->load->vars(array(
            'nav_current'=>'explore',
            'subnav' => array(
                '/explore/' => __('All'),
                '/topics' => __('Topics'),
                '/keywords' => __('Tags'),
                '/publications' => __('Publications'),
                '/authors' => __('Authors'),
            ),
            'subnav_current' => '/publications',
        ));
        $this->load->helper('publication');
    }

    /**
     * Default function: list publications
     */
    function index() {
        $this->showlist();
    }

    /**
     * Calls single publication view
     */
    function show($pub_id) {
        if (!is_numeric($pub_id)) {
            //retrieve publication ID
            $pub_id = $this->uri->segment(3);
        }
        $categorize = $this->uri->segment(4,'');
        if (!$pub_id) {
            redirect('/publications');
        }

        //load publication
        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            //attempt to retrieve by bibtex_id
            if ($pub_id == 'bibtex_id') {
                $bibtex_id = $this->uri->segment(4,'');
                $categorize = $this->uri->segment(5,'');
                if ($bibtex_id != '') {
                    $publication = $this->publication_db->getByBibtexID($bibtex_id);
                }
            }
            if ($publication == null) {
              appendErrorMessage(__('View publication: non-existing id passed.'));
              redirect('/publications');
            }
        }

        $this->load->view('header', array('title' => h($publication->title)));
        $this->load->view('publications/single', array(
            'publication' => $publication,
            'categorize'  => $categorize=='categorize'
        ));
        $this->load->view('footer');
    }

    /**
     * Calls single publication view for bibtex cite_id rather than pub_id
     *
     * No other parameters besides bibtex_id are possible (such as e.g. 'categorize')
     * because bibtex_id may contain slashes so we need to take all of the URI-remainder
     * For such, use 'show' controller.
     */
    function showcite() {
        $segments = $this->uri->segment_array();
        //remove first two elements
        array_shift($segments);
        array_shift($segments);
        $bibtex_id = implode('/',$segments);

        //load publication
        $publication = $this->publication_db->getByBibtexID($bibtex_id);
        if ($publication == null) {
            appendErrorMessage(sprintf(__('View publication: non-existing BibTeX id &ldquo;%s&rdquo; was passed.'), $bibtex_id));
            redirect('/publications');
        }

        $this->load->view('header', array('title' => h($publication->title)));
        $this->load->view('publications/single', array('publication' => $publication));
        $this->load->view('footer');
    }

    /**
     * Entry point for showing a list of publications.
     */
    function showlist() {
        $order = $this->uri->segment(3,'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
          $order='';
        }
        $page = $this->uri->segment(4,0);

        $userlogin = getUserLogin();
        $content = array('header' => __('All publications %s'));
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
                $content['header'] = sprintf($content['header'], __('sorted by author'));
                break;
            default:
                $content['header'] = sprintf($content['header'], '');
        }

        if ($userlogin->getPreference('liststyle') > 0) {
            //set these parameters when you want to get a good multipublication list display
            $content['multipage']       = True;
            $content['pubCount']        = $this->topic_db->getPublicationCountForTopic('1');
            $content['currentpage']     = $page;
            $content['multipageprefix'] = 'publications/showlist/'.$order.'/';
        }
        $content['publications']    = $this->publication_db->getForTopic('1',$order,$page);
        $content['order'] = $order;

        $this->load->view('header', array('title' => __('Publication list')));
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }

    /**
     * Entry point for showing a list of publications that are not assigned to a topic.
     */
    function unassigned() {
        $order   = $this->uri->segment(3,'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='';
        }
        $page   = $this->uri->segment(4,0);

        $userlogin = getUserLogin();
        $content['header'] = __('All publications not assigned to a topic %s');
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
                $content['header'] = sprintf($content['header'], __('sorted by author'));
                break;
            default:
                $content['header'] = sprintf($content['header'], '');
        }

        if ($userlogin->getPreference('liststyle') > 0) {
            //set these parameters when you want to get a good multipublication list display
            $content['multipage']       = True;
            $content['currentpage']     = $page;
            $content['multipageprefix'] = 'publications/unassigned/'.$order.'/';
        }
        $content['publications']    = $this->publication_db->getUnassigned($order);
        $content['order'] = $order;

        $this->load->view('header', array('title' => __('Publication list')));
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }

    /**
     * Calls an empty publication edit form
     */
    function add() {
        $this->edit();
    }

    /**
     * Call publication edit form. When no ID is given: new publicationform
     */
    function edit($publication = '') {
        $this->publication_db->suppressMerge = True;//note: in the edit form, we should NOT see the data from the crossreferenced publication, so suppress merging
        if (is_numeric($publication)) {
            $pub_id = $publication;
            $publication = $this->publication_db->getByID($pub_id);
            $publication->getKeywords();

            //set header data
            $edit_type = 'edit';
        } else if (empty($publication)) {
            //php4 compatiblity: new $this->publication won't work
            $publication = $this->publication;
            $edit_type = 'add';
        } else {
            //there was a publication post, retrieve the edit type from the post.
            $edit_type = $this->input->post('edit_type');
        }

        $userlogin  = getUserLogin();
        restrict_to_right((! $userlogin->hasRights('publication_edit') ||
                           ! $this->accesslevels_lib->canEditObject($publication)),
            __('Edit publication'), '/publications');

        switch ($edit_type) {
          case 'add':
            $header = array('title' => __('Add publication'));
            break;
          case 'edit':
            $header = array('title' => __('Edit publication'));
            break;
        }
        $content = array(
            'edit_type'   => $edit_type,
            'publication' => $publication);

        //get output
        $this->load->view('header', $header);
        $this->load->view('publications/edit', $content);
        $this->load->view('footer');
    }

    /*
     * Call publication import page
     * DR: is this controller ever called?
     */
    function import() {
        restrict_to_right('publication_edit', __('Import publication'), '/publications');
        $this->load->view('header', array('title' => __('Import publications')));
        $this->load->view('publications/import');
        $this->load->view('footer');
    }

    /**
     * Entry point for deleting a publication.
     */
    function delete() {
        $pub_id = $this->uri->segment(3);
        $publication = $this->publication_db->getByID($pub_id);
        $commit = $this->uri->segment(4,'');

        if ($publication==null) {
            appendErrorMessage(__('Delete publication: non-existing id passed.'));
            redirect('/publications');
        }

        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();
        restrict_to_right((! $userlogin->hasRights('publication_edit') ||
                           ! $this->accesslevels_lib->canEditObject($publication)),
            __('Delete publication'), '/publications');

        if ($commit=='commit') {
            //do delete, redirect somewhere
            if ($publication->delete()) {
                redirect('/publications');
            } else {
                redirect('publications/show/'.$publication->pub_id);
            }
        } else {
            $this->load->view('header', array('title' => __('Delete publication')));
            $this->load->view('publications/delete', array('publication'=>$publication));
            $this->load->view('footer');
        }
    }

    /*
     * Commit the posted publication to the database
     */
    function commit() {
        $publication = $this->publication_db->getFromPost();
        $oldpublication = $this->publication_db->getByID($publication->pub_id); //needed to check access levels, as post data may be rigged
        //check the submit type, if 'type_change', we redirect to the edit form
        $submit_type = $this->input->post('submit_type');

        if ($submit_type == 'type_change') {
            $this->edit($publication);
        } else {
            if (!$this->publication_db->validate($publication)) {
                //there were validation errors
                appendErrorMessage(__('There are validation errors with this entry. You may want to correct them.'));
            }
            $edit_type = $this->input->post('edit_type');
            $bReview = false;
            if ($submit_type != 'review') {
                $review = array(
                    'bibtex_id' => $this->publication_db->reviewBibtexID($publication),
                    'keywords'  => $this->keyword_db->review($publication->keywords),
                );

                if ($review['bibtex_id'] != null ||
                    $review['keywords']  != null) {
                    $bReview = true;
                    $review['edit_type'] = $edit_type;
                    //month: the field has been parsed to internal format, but the review form needs to contain the month in bibtex format
                    $publication->month = formatMonthBibtexForEdit($publication->month);
                    $this->review($publication, $review);
                }
            }
            if (!$bReview) {
                //do actual commit, depending on the edit_type, choose add or update
                $userlogin  = getUserLogin();
                restrict_to_right((!$userlogin->hasRights('publication_edit') ||
                                   ($oldpublication == null && $edit_type != 'add') ||
                                   (!$this->accesslevels_lib->canEditObject($oldpublication) && $oldpublication != null)),
                    __('Commit publication'),
                    '/publications');

                if ($edit_type == 'add') {
                    $publication = $this->publication_db->add($publication);
                } else {
                    $publication = $this->publication_db->update($publication);
                }

                //show publication
                redirect('publications/show/'.$publication->pub_id);
            }
        }
    }

    /**
     *
     */
    function review($publication, $review_data) {
        $oldpublication = $this->publication_db->getByID($publication->pub_id); //needed to check access levels, as post data may be rigged
        $userlogin      = getUserLogin();
        restrict_to_right((! $userlogin->hasRights('publication_edit') ||
                           ($oldpublication == null && $review_data['edit_type']!='add') ||
                           (!$this->accesslevels_lib->canEditObject($oldpublication) && $oldpublication != null)),
            __('Review publication'),
            '/publications');

        //get output
        $this->load->view('header', array('title' => __('Review publication')));
        $this->load->view('publications/review', array(
            'publication' => $publication,
            'review'      => $review_data,
        ));
        $this->load->view('footer');
    }

    /**
     * Subscribes a publication to a topic
     *
     * Is normally called async, without processing the
     * returned partial, by clicking a subscribe link in a topic tree rendered by
     * subview 'publicationsubscriptiontreerow'
     */
    function subscribe() {
        $topic_id = $this->uri->segment(3,-1);
        $pub_id = $this->uri->segment(4,-1);
        $error = false;

        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            $error = true;
        }

        $topic = $this->topic_db->getByID($topic_id, array('publicationId'=>$pub_id));
        if ($topic == null) {
            $error = true;
        }

        if ($error || ! $topic->subscribePublication()) {
            $msg = '<p class="error">'.__('Subscribe topic: non-existing id passed.').'</p>';
            if (is_ajax()) {
                echo $msg;
            } else {
                $this->load->view('header', array('title'=>__('Subscribe')));
                $this->load->view('put', array('data'=>$msg));
                $this->load->view('footer');
            }
        } else {
            if (is_ajax()) {
                echo '<div/>';
            } else {
                back_to_referer(__('Successfully subscribed publication.'), '/publications/show/'.$pub_id);
            }
        }
    }

    /**
     * Unsusbcribes a publication from a topic
     *
     * Is normally called async, without processing the
     * returned partial, by clicking a subscribe link in a topic tree rendered by
     * subview 'publicationsubscriptiontreerow'
     */
    function unsubscribe() {
        $topic_id = $this->uri->segment(3,-1);
        $pub_id = $this->uri->segment(4,-1);
        $error = false;

        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            $error = true;
        }

        $topic = $this->topic_db->getByID($topic_id, array('publicationId'=>$pub_id));
        if ($topic == null) {
            $error = true;
        }

        if ($error || ! $topic->unsubscribePublication()) {
            $msg = '<p class="error">'.__('Subscribe topic: non-existing id passed.').'</p>';
            if (is_ajax()) {
                echo $msg;
            } else {
                $this->load->view('header', array('title'=>__('Unsubscribe')));
                $this->load->view('put', array('data'=>$msg));
                $this->load->view('footer');
            }
        } else {
            if (is_ajax()) {
                echo '<div/>';
            } else {
                back_to_referer(__('Successfully unsubscribed publication.'), '/publications/show/'.$pub_id);
            }
        }
    }

    /**
     * marks a publication as read
     */
    function read() {
        restrict_to_rights('note_edit', __('Mark publication'), 'publications/show/'.$publication->pub_id);
        $pub_id = $this->uri->segment(3,-1);

        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            appendErrorMessage(__('Mark publication: non-existing id passed.'));
            redirect('/publications');
        }
        $mark = $this->input->post('mark', '');
        if ($mark==0) $mark='';
        $publication->read($mark);
        redirect('publications/show/'.$publication->pub_id);
    }

    /**
     * marks a publication as not-read
     */
    function unread() {
        restrict_to_rights('note_edit', __('Mark publication'), 'publications/show/'.$publication->pub_id);
        $pub_id = $this->uri->segment(3,-1);

        $publication = $this->publication_db->getByID($pub_id);
        if ($publication == null) {
            appendErrorMessage(__('Mark publication: non-existing id passed.'));
            redirect('/publications');
        }
        $publication->unread();
        redirect('publications/show/'.$publication->pub_id);
    }

    /**
     * Send the selected publication to the specified email address(es).
     */
    function exportEmail() {
        restrict_to_right('export_email', __('Export through email'), '/publications');
        $this->load->library('email_export');

        $email_pdf = $this->input->post('email_pdf');
        $email_bibtex = $this->input->post('email_bibtex');
        $email_ris = $this->input->post('email_ris');
        $email_address = $this->input->post('email_address');
        $email_formatted = $this->input->post('email_formatted');
        $order = 'year';
        $recipientaddress   = $this->uri->segment(4,-1);
        $pub_id   = $this->uri->segment(3,-1);
        $publications = array($this->publication_db->getByID($pub_id));

        if (!isset($pub_id) || $pub_id == -1 || count($publications) == 0) {
            appendErrorMessage(__('Export publication: non-existing id passed.'));
            redirect('/publications');
        }

        if(!(($email_pdf !='' || $email_bibtex !='' || $email_ris!='' || $email_formatted!='') && $email_address != '')) {
            /* IF the recipient's address is missing or if none of the data formats are selected THEN show the format selection form. */
            $content = array(
                'attachmentsize' => $this->email_export->attachmentSize($publications),
                'controller'     => 'publications/exportEmail/'.$pub_id,
            );
            if(isset($recipientaddress)) {
                $content['recipientaddress'] = str_replace(array('AROBA', 'KOMMA'), array('@', ','), $recipientaddress);;
            }

            $this->load->view('header', array('title'=>__('Select export format')));
            $this->load->view('export/chooseformatEmail', $content);
            $this->load->view('footer');
            return;
        } else {
            /* ELSE process the request and send the email. */
            $messageBody = __('Export from Aigaion');

            if($email_formatted || $email_bibtex) {
                $this->publication_db->enforceMerge = True;
                $publications = array($this->publication_db->getByID($pub_id));
                $splitpubs = $this->publication_db->resolveXref($publications,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported publication');
                $exportdata['exportEmail']   = true;
            }

            /* FORMATTED text is added first. HTML format is selected because this gave nice readable text without having to change or make any views. */
            if($email_formatted) {
                $messageBody .= "\n";
                $messageBody .= __('Formatted');
                $messageBody .= "\n";

                $exportdata['format'] = 'html';
                $exportdata['sort'] = $this->input->post('sort');
                $exportdata['style'] = $this->input->post('style');
                $messageBody .= strip_tags($this->load->view('export/formattedEmail', $exportdata, True));
            }

            /* BIBTEX added. */
            if($email_bibtex) {
                $messageBody .= "\n";
                $messageBody .= 'BibTeX';
                $messageBody .= "\n";
                $messageBody .= strip_tags($this->load->view('export/bibtexEmail', $exportdata, True));
            }
            /* RIS added. */
            if($email_ris) {
                $messageBody .= "\n";
                $messageBody .= 'RIS';
                $messageBody .= "\n";

                $this->publication_db->suppressMerge = False;
                $publications = array($this->publication_db->getByID($pub_id));
                $splitpubs = $this->publication_db->resolveXref($publications,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                #send to right export view
                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported publication');
                $exportdata['exportEmail']   = true;

                $messageBody .= strip_tags($this->load->view('export/risEmail', $exportdata, True));
            }

            /* If PDFs are not selected the publication array is removed and no attachments will be added. */
            if(! $email_pdf) {
                $publications = array();
            }

            /* Sending MAIL. */
            if($this->email_export->sendEmail($email_address, $messageBody, $publications)) {
                $this->load->view('header', array('title' => __('Export publication')));
                $this->load->view('put', array('data' => '<p class="info">'.__('Mail sent successfully').'</p>'));
                $this->load->view('footer');
            } else {
                appendErrorMessage(__('Something went wrong when exporting the publications. Did you input a correct email address?'));
                redirect('/publications');
            }
        }
    }
}

//__END__