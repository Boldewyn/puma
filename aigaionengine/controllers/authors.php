<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authors extends Controller {

    function Authors() {
        parent::Controller();
         $this->load->vars(array(
            'nav_current'=>'explore',
            'subnav_current' => '/authors',
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
     * Show all authors together with a filter form
     */
    function index() {
        $this->load->view('header', array('title' => __('Authors')));
        $this->load->view('authors/list', array(
            'header'     => __("All authors in the database"),
            'authorlist' => $this->author_db->getAllAuthors(),
            'searchbox'  => True));
        $this->load->view('footer');
    }

    /**
     * Call single author overview
     */
    function show($author_id) {
        if (!is_numeric($author_id)) {
            $author_id   = $this->uri->segment(3);
        }
        $order   = $this->uri->segment(4,'year');
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }
        $page   = $this->uri->segment(5,0);

        //load author
        $author = $this->author_db->getByID($author_id);
        if ($author == null) {
            appendErrorMessage(__('View Author: non-existing id passed.'));
            redirect('');
        }
        $this->load->helper('publication');
        $userlogin = getUserLogin();

        $publicationContent = array('header' => sprintf(__('Publications of %s'), h($author->getName())).' %s');
        switch ($order) {
            case 'type':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by journal and type'));
                break;
            case 'recent':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by recency'));
                break;
            case 'title':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by title'));
                break;
            case 'author':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by first author'));
                break;
            default:
                $publicationContent['header'] = sprintf($publicationContent['header'], '');
        }
        if ($userlogin->getPreference('liststyle')>0) {
            //set these parameters when you want to get a good multipublication list display
            $publicationContent['multipage']       = True;
            $publicationContent['currentpage']     = $page;
            $publicationContent['pubCount']        = $this->author_db->getPublicationCount($author_id);
            $publicationContent['multipageprefix'] = 'authors/show/'.$author_id.'/'.$order.'/';
        }
        $publicationContent['publications'] = $this->publication_db->getForAuthor($author_id,$order,$page);
        $publicationContent['order'] = $order;
        $publicationContent['sortPrefix'] = 'authors/show/'.$author_id.'/%s';

        $this->load->view('header', array('title' => h($author->getName())));
        $this->load->view('authors/single', array('author' => $author));
        if ($publicationContent['publications'] != null) {
            $this->load->view('publications/list', $publicationContent);
        }
        $this->load->view('footer');
    }

    /**
     * A controller that should return only the basic contents of the single author publication listing.
     */
    function embed($author_id, $order='year', $page=0) {
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }

        //load author
        $author = $this->author_db->getByID($author_id);
        if ($author == null) {
            appendErrorMessage(__('View Author: non-existing id passed.'));
            redirect('');
        }
        $this->load->helper('publication');
        $userlogin = getUserLogin();

        $publicationContent = array('header' => sprintf(__('Publications of %s'), h($author->getName())).' %s');
        switch ($order) {
            case 'type':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by journal and type'));
                break;
            case 'recent':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by recency'));
                break;
            case 'title':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by title'));
                break;
            case 'author':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by first author'));
                break;
            default:
                $publicationContent['header'] = sprintf($publicationContent['header'], '');
        }
        if ($userlogin->getPreference('liststyle')>0) {
            //set these parameters when you want to get a good multipublication list display
            $publicationContent['multipage']       = True;
            $publicationContent['currentpage']     = $page;
            $publicationContent['multipageprefix'] = 'authors/embed/'.$author_id.'/'.$order.'/';
        }
        $publicationContent['publications'] = $this->publication_db->getForAuthor($author_id,$order);
        $publicationContent['order'] = $order;
        $publicationContent['noBookmarkList'] = True;
        $publicationContent['sortPrefix'] = 'authors/embed/'.$author_id.'/%s';

        $this->load->view('authors/embed', array('author' => $author));
        if ($publicationContent['publications'] != null) {
            $this->load->view('publications/list', $publicationContent);
        }
    }

    /**
     * A controller that should return only the basic contents of the single author publication listing.
     */
    function embedClean() {
        $author_id   = $this->uri->segment(3);
        $order   = $this->uri->segment(4,'year');
        if (!in_array($order,array('year','type','recent','title','author','msc'))) {
            $order='year';
        }
        $author = $this->author_db->getByID($author_id);
        if ($author == null) {
          appendErrorMessage(__('View Author').": ".__('non-existing id passed').".<br/>");
          redirect('');
        }

        $this->load->helper('publication');
        $userlogin = getUserLogin();

        $publicationContent = array('header' => sprintf(__('Publications of %s'), h($author->getName())).' %s');
        switch ($order) {
            case 'type':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by journal and type'));
                break;
            case 'recent':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by recency'));
                break;
            case 'title':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by title'));
                break;
            case 'author':
                $publicationContent['header'] = sprintf($publicationContent['header'], __('sorted by first author'));
                break;
            default:
                $publicationContent['header'] = sprintf($publicationContent['header'], '');
        }
        $publicationContent['publications'] = $this->publication_db->getForAuthor($author_id,$order);
        $publicationContent['order'] = $order;

        if ($publicationContent['publications'] == null) {
            $this->load->view('put', array('data' => __('No publications found.')));
        } else {
            $this->load->view('publications/listClean', $publicationContent);
        }
    }

    /**
     * Calls an empty author edit form
     */
    function add() {
        $this->edit();
    }

    /**
     * Call author edit form. When no ID is given: new authorform
     */
    function edit($author = '') {
        restrict_to_right('publication_edit', __('Edit author'), '/authors');
        if (is_numeric($author)) {
            $author_id  = $author;
            $author     = $this->author_db->getByID($author_id);
            $edit_type = 'edit';
            $title = __('Edit Author');
        } else if (empty($author)) {
            //php4 compatiblity: new $this->author won't work
            $author     = $this->author;
            $edit_type  = 'new';
            $title = __('New Author');
        } else {
            //there was a author post, retrieve the edit type from the post.
            $edit_type = $this->input->post('edit_type');
        }

        $this->load->vars(array(
            'subnav' => array(
                '/import/' => __('Import'),
                '/publications/add' => __('Publication'),
                '/topics/add' => __('Topic'),
                '/authors/add' => __('Author'),
            ),
            'subnav_current' => '/authors/add',
            'nav_current' => 'create',
        ));
        $this->load->view('header', array('title' => $title));
        $this->load->view('authors/edit', array(
            'edit_type' => $edit_type,
            'author' => $author));
        $this->load->view('footer');
    }

    /**
     * Call author merge form.
     */
    function merge($author_id, $simauthor_id) {
        restrict_to_right('publication_edit', __('Cannot merge authors'), '/authors');
        $author = $this->author_db->getByID($author_id);
        $simauthor = $this->author_db->getByID($simauthor_id);
        if ($author==null || $simauthor==null) {
            appendErrorMessage(__('Cannot merge authors: missing parameters.'));
            redirect('/authors');
        }

        $this->load->view('header', array('title' => 'Authors: Merge'));
        $this->load->view('authors/merge', array(
            'author' => $author,
            'simauthor' => $simauthor));
        $this->load->view('footer');
    }

    /**
     * Do merge commit
     */
    function mergecommit() {
        restrict_to_right('publication_edit', __('Cannot merge authors'), '/authors');
        $author = $this->author_db->getFromPost();
        $simauthor_id = $this->input->post('simauthor_id');
        $simauthor = $this->author_db->getByID($simauthor_id);
        if ($author==null || $simauthor==null) {
            appendErrorMessage(__('Cannot merge authors: missing parameters.'));
            redirect('/authors');
        }

        //so... actually, we should now test whether the user has edit access on all involved publications!!!
        $author->update(); //this updates the new name info into the author
        $author->merge($simauthor_id);
        redirect('authors/show/'.$author->author_id);
    }

    /**
     * Entry point for deleting an author.
     */
    function delete($author_id, $commit='') {
        restrict_to_right('publication_edit', __('Delete author'), '/authors');
        $author = $this->author_db->getByID($author_id);
        if ($author==null) {
            appendErrorMessage(__('Delete author: author does not exist.'));
            redirect('/authors');
        }

        if ($commit == 'commit') {
            //do delete, redirect somewhere
            $author->delete();
            redirect('/authors');
        } else {
            $this->load->view('header', array('title' => __('Delete Author')));
            $this->load->view('confirm', array(
                'url' => 'authors/delete/'.$author->author_id.'/commit',
                'question' => sprintf(__('Are you sure, that you want to delete the author &ldquo;%s&rdquo;?'), h($author->getName())),
                'cancel_url' => 'authors/show/'.$author->author_id,
            ));
            $this->load->view('footer');
        }
    }

    /**
     * Commit the posted author to the database
     */
    function commit() {
        restrict_to_right('publication_edit', __('Edit author'), '/authors');
        $author = $this->author_db->getFromPost();
        //check the submit type, if 'type_change', we redirect to the edit form
        $submit_type = $this->input->post('submit_type');
        if ($this->author_db->validate($author)) {
            $bReview = false;
            if ($submit_type != 'review') {
                //review author for similar authors
                list($review['author'],$similar_ids)   = $this->author_db->review(array($author));
                if ($review['author'] != null) {
                    $bReview = true;
                    $this->review($author, $review);
                }
            }
            if (!$bReview) {
                //do actual commit, depending on the edit_type, choose add or update
                $edit_type = $this->input->post('edit_type');
                if ($edit_type == 'new') {
                    //note: the author_db review method will not give an error if ONE EXACT MATCH EXISTS
                    //so we should still check that here
                    if ($this->author_db->getByExactName($author->firstname,$author->von,$author->surname,$author->jr) != null) {
                        appendMessage(sprintf(__('Author &ldquo;%s&rdquo; already exists in the database.'), $author->getName('lvf')));
                        redirect('authors/add');
                    } else {
                        $author = $this->author_db->add($author);
                    }
                } else {
                    $author = $this->author_db->update($author);
                }
                redirect('authors/show/'.$author->author_id);
            }
        } else {
            //there were validation errors, edit the publication once again
            $this->edit($author);
        }
    }

    /**
     * Review an author
     */
    function review($author, $review_message) {
        restrict_to_right('publication_edit', __('Edit author'), '/authors');
        $this->load->view('header', array('title' => __('Review author')));
        $this->load->view('authors/edit', array(
            'edit_type' => $this->input->post('edit_type'),
            'author'    => $author,
            'review'    => $review_message,
        ));
        $this->load->view('footer');
    }

    /**
     * List authors for a given topic
     */
    function fortopic($topic_id = -1) {
        $config = array();
        $topic = $this->topic_db->getByID($topic_id,$config);
        if ($topic==null) {
            appendErrorMessage(__('Authors for topic: non-existing id passed'));
            redirect('/authors');
        }
        $this->load->view('header', array('title' => __('Authors')));
        $this->load->view('authors/list', array(
            'header'     => sprintf(__('Authors for topic %s'), anchor('topics/single/'.$topic->topic_id, $topic->name)),
            'authorlist' => $topic->getAuthors(),
        ));
        $this->load->view('footer');
    }

    /**
     *
     */
    function searchlist() {
        $author_search = $this->input->post('author_search');
        if ($author_search) { // user pressed show, so redirect to single author page
            $authorList = $this->author_db->getAuthorsLike($author_search);
            if (sizeof($authorList) > 0) {
                redirect('authors/show/'.$authorList[0]->author_id);
            }
        } else {
            $author_search  = $this->uri->segment(3);
            $this->load->view('authors/list_items', array(
                'authorlist' => $this->author_db->getAuthorsLike($author_search)));
        }
    }

    /**
     * create a new author from the text in the post value 'authorname'
     */
    function quickcreate() {
        require_once(APPPATH.'include/utf8/trim.php');
        $this->load->helper('encode');
        $name = $this->input->post('authorname');
        if (utf8_trim($name) == '') {
            echo '';
            return;
        }
        $this->load->library('parsecreators');
        $authors_array    = $this->parsecreators->parse($name);
        if (count($authors_array) > 0) {
            $existingauthor = $this->author_db->getByExactName(
                  $authors_array[0]['firstname'], $authors_array[0]['von'],
                  $authors_array[0]['surname'], $authors_array[0]['jr']);
            if ($existingauthor==null) {
                $newauthor = $this->author_db->setByName(
                      $authors_array[0]['firstname'], $authors_array[0]['von'],
                      $authors_array[0]['surname'], $authors_array[0]['jr']);
                $result = $this->author_db->add($newauthor);
                echo $result->author_id.';'.$result->getName('vlf');
            } else {
                echo '';
            }
        } else {
            echo '';
        }
    }

    /**
     * Sends the publications for the selected author to the specified email address(es)
     */
    function exportEmail($author_id = -1, $recipientaddress = -1) {
        restrict_to_right('export_email', __('Export through email'), '/authors');
        $this->load->library('email_export');
        $email_pdf = $this->input->post('email_pdf');
        $email_bibtex = $this->input->post('email_bibtex');
        $email_ris = $this->input->post('email_ris');
        $email_address = $this->input->post('email_address');
        $email_formatted = $this->input->post('email_formatted');
        $order='year';
        $publications = $this->publication_db->getForAuthor($author_id);
        if (!isset($author_id)) {
            appendErrorMessage(__('No author selected for export.'));
            redirect('');
        }

        if(!(($email_pdf !='' || $email_bibtex !='' || $email_ris!='' || $email_formatted!='') && $email_address != '')) {
            /* IF the recipient's address is missing or if none of the data formats are selected THEN show the format selection form. */
            $content = array(
                'attachmentsize' => $this->email_export->attachmentSize($publications),
                'controller'     => 'authors/exportEmail/'.$author_id);
            if(isset($recipientaddress)) {
                $content['recipientaddress'] = str_replace(array('AROBA', 'KOMMA'), array('@', ','), $recipientaddress);
            }

            //get output
            $this->load->view('header', array('title' => __('Select export format')));
            $this->load->view('export/chooseformatEmail', $content);
            $this->load->view('footer');
            return;
        } else {
            /*  ELSE process the request and send the email. */
            //get output
            $this->load->helper('publication');
            $messageBody = sprintf(__('Export from %s'), 'Puma.Phi');
            $exportdata = array();
            if($email_formatted || $email_bibtex) {
                $this->publication_db->enforceMerge = True;
                $publicationMap = $this->publication_db->getForAuthorAsMap($author_id);
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported for author');
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
                $messageBody .= strip_tags($this->load->view('export/'.'formattedEmail', $exportdata, True));
            }
            /* BIBTEX added. */
            if($email_bibtex) {
                $messageBody .= "\n";
                $messageBody .= 'BibTex';
                $messageBody .= "\n";
                $messageBody .= strip_tags($this->load->view('export/'.'bibtexEmail', $exportdata, True));
            }
            /* RIS added. */
            if($email_ris) {
                $messageBody .= "\n";
                $messageBody .= 'RIS';
                $messageBody .= "\n";

                $this->publication_db->suppressMerge = False;
                $publicationMap = $this->publication_db->getForAuthorAsMap($author_id);
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                //send to right export view
                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported for author');
                $exportdata['exportEmail']   = true;

                $messageBody .= strip_tags($this->load->view('export/'.'risEmail', $exportdata, True));
            }
            /* If PDFs are not selected the publication array is removed and no attachments will be added. */
            if(! $email_pdf) {
                $publications = array();
            }

            /* Sending MAIL. */
            if($this->email_export->sendEmail($email_address, $messageBody, $publications)) {
                $this->load->view('header', array('title' => __('Export Author')));
                $this->load->view('put', array('data' => '<p class="info">'.__('Mail sent successfully').'</p>'));
                $this->load->view('footer');
            } else {
                appendErrorMessage(__('Something went wrong when exporting the publications. Did you input a correct email address?'));
                redirect('/authors');
            }
        }
    }
}

//__END__
