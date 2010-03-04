<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bookmarklist extends Controller {

    function Bookmarklist() {
        parent::Controller();    
        $this->load->library('bookmarklist_db');
        $subnav = array(
            '/user/' => __('All users'),
            '/bookmarklist/' => __('My bookmarks'),
        );
        $userlogin = getUserLogin();
        $subnav['/user/'.$userlogin->loginName().'/edit'] = __('My preferences');
        $this->load->vars(array('subnav' => $subnav, 'subnav_current' => '/bookmarklist/'));
     }
    
    /**
     * Pass control to the bookmarklist/viewlist
     */
    function index() {
        $this->viewlist();
    }

    /**
     * Entry point for viewing the bookmark list of the logged user.
     */
    function viewlist($order='year', $page=0) {
        restrict_to_right('bookmarklist', __('View bookmarklist'), '');
        $userlogin = getUserLogin();
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }

        $this->load->helper('publication');

        $content = array('header' => sprintf(__('Bookmarklist of %s %%s'),$userlogin->loginName()));
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
        $userlogin = getUserLogin();
        if ($userlogin->getPreference('liststyle')>0) {
            //set these parameters when you want to get a good multipublication list display
            $content['currentpage']     = $page;
            $content['multipageprefix'] = 'bookmarklist/viewlist/'.$order.'/';
        }
        $content['publications']    = $this->publication_db->getForBookmarkList($order, $page);
        $content['pubCount']    = $this->bookmarklist_db->count();
        $content['order'] = $order;
        
        $this->load->view('header', array('title' => __('Bookmark list')));
        $this->load->view('bookmarklist/controls');
        $this->load->view('sort', array('sortPrefix'=>'/bookmarklist/viewlist/'));
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }    

    /** 
     * bookmarklist/addpublication
     */
    function addpublication($id=-1) {
        $publication = $this->publication_db->getByID($id);
        if ($publication == null) {
            if (is_ajax()) {
                header('Content-Type: text/javascript');
                $this->output->set_output('{"error":true}');
                return;
            } else {
                back_to_referer(__('Add publication to bookmarklist: non-existing id passed.'), '', True);
            }
        } else {
            $this->bookmarklist_db->addPublication($publication->pub_id);
            if (is_ajax()) {
                header('Content-Type: text/javascript');
                $this->output->set_output('{"src":"'.iconpath('bookmarked').'"}');
            } else {
                back_to_referer(__('Publication was successfully added to the bookmark list.'));
            }
        }
    }

    /** 
    bookmarklist/addtopic
    
    Entry point for adding all accessible publications from a give topic to the bookmark list of the logged user.
    
    Fails with error message when one of:
        adding nonexisting topic_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: topic_id
             
    Returns:
        to the view page of that topic
    */
    function addtopic() {
        $topic_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->addTopic function, no need to do it twice

        //load topic
        $config=array();
        $topic = $this->topic_db->getByID($topic_id,$config);
        if ($topic == null)
        {
            appendErrorMessage(__('Add topic to bookmarklist: non-existing id passed.'));
            redirect('');
        }
        
        $this->bookmarklist_db->addTopic($topic->topic_id);
        redirect('topics/single/'.$topic->topic_id);
      
    }
    

    /** 
    bookmarklist/addkeyword
    
    Entry point for adding all accessible publications from a given keyword to the bookmark list of the logged user.
    
    Fails with error message when one of:
        adding nonexisting keyword_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: keyword_id
             
    Returns:
        to the view page of that keyword
    */
    function addkeyword() {
        $keyword_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->addKeyword function, no need to do it twice

        //load topic
        $config=array();
        $keyword = $this->keyword_db->getByID($keyword_id);
        if ($keyword == null)
        {
            appendErrorMessage(__('Add keyword to bookmarklist: non-existing id passed.'));
            redirect('');
        }
        
        $this->bookmarklist_db->addKeyword($keyword->keyword_id);
        redirect('keywords/single/'.$keyword->keyword_id);
      
    }
    
    
    /** 
    bookmarklist/addauthor
    
    Entry point for adding all accessible publications from a given author to the bookmark list of the logged user.
    
    Fails with error message when one of:
        adding nonexisting author_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: author_id
             
    Returns:
        to the view page of that author
    */
    function addauthor() {
        $author_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->addAuthor function, no need to do it twice

        //load author
        $author = $this->author_db->getByID($author_id);
        if ($author == null)
        {
            appendErrorMessage(__("Add author to bookmarklist").": ".__("non-existing id passed").".<br/>");
            redirect('');
        }
        
        $this->bookmarklist_db->addAuthor($author->author_id);
        redirect('authors/show/'.$author->author_id);
      
    }    

    /** 
     * bookmarklist/removepublication
     */
    function removepublication($id=-1) {
        $publication = $this->publication_db->getByID($id);
        if ($publication == null) {
            if (is_ajax()) {
                header('Content-Type: text/javascript');
                $this->output->set_output('{"error":true}');
                return;
            } else {
                back_to_referer(__('Removing publication from bookmarklist: non-existing id passed.'), '', True);
            }
        } else {
            $this->bookmarklist_db->removePublication($publication->pub_id);
            if (is_ajax()) {
                header('Content-Type: text/javascript');
                $this->output->set_output('{"src":"'.iconpath('nonbookmarked').'"}');
            } else {
                back_to_referer(__('Publication was successfully removed from the bookmark list.'));
            }
        }
    }
    

    /** 
    bookmarklist/removetopic
    
    Entry point for removing all accessible publications of a topic from the bookmark list of the logged user.
    
    Fails with error message when one of:
        removing nonexisting topic_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: topic_id
             
    Returns:
        to the single view page of that topic
    */
    function removetopic() {
        $topic_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->removeTopic function, no need to do it twice

        //load topic
        $config=array();
        $topic = $this->topic_db->getByID($topic_id,$config);
        if ($topic == null)
        {
            appendErrorMessage(__('Removing topic from bookmarklist: non-existing id passed.'));
            redirect('');
        }
        
        $this->bookmarklist_db->removeTopic($topic->topic_id);
        redirect('topics/single/'.$topic->topic_id);      
    }

    /** 
    bookmarklist/removekeyword
    
    Entry point for removing all accessible publications of a keyword from the bookmark list of the logged user.
    
    Fails with error message when one of:
        removing nonexisting keyword_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: keyword_id
             
    Returns:
        to the single view page of that keyword
    */
    function removekeyword() {
        $keyword_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->removeAuthor function, no need to do it twice

        //load keyword
        $keyword = $this->keyword_db->getByID($keyword_id);
        if ($keyword == null)
        {
            appendErrorMessage(__('Removing keyword from bookmarklist: non-existing id passed.'));
            redirect('');
        }
        
        $this->bookmarklist_db->removeKeyword($keyword->keyword_id);
        redirect('keywords/single/'.$keyword->keyword_id);      
    }
    
    
    /** 
    bookmarklist/removeauthor
    
    Entry point for removing all accessible publications of an author from the bookmark list of the logged user.
    
    Fails with error message when one of:
        removing nonexisting author_id 
        insufficient rights
        
    Parameters passed via URL segments:
        3rd: author_id
             
    Returns:
        to the single view page of that author
    */
    function removeauthor() {
        $author_id   = $this->uri->segment(3,-1);

        //check rights is done in the $this->bookmarklist_db->removeAuthor function, no need to do it twice

        //load author
        $author = $this->author_db->getByID($author_id);
        if ($author == null)
        {
            appendErrorMessage(__("Removing author from bookmarklist").": ".__("non-existing id passed").".<br/>");
            redirect('');
        }
        
        $this->bookmarklist_db->removeAuthor($author->author_id);
        redirect('authors/show/'.$author->author_id);      
    }

    /** 
    bookmarklist/addtotopic
    
    Entry point for adding all publications in the bookmark list to a certain topic.
    
    Fails with error message when one of:
        insufficient rights
        nonexisting topic
        
    Parameters passed via POST:
        topic_id
             
    Redirects to the bookmarklist/view controller
        
    */
    function addtotopic() {
        //check rights is done in the $this->bookmarklist_db->removePublication function, no need to do it twice

        $topic_id = $this->input->post('topic_id');
        $userlogin  = getUserLogin();
        $user       = $this->user_db->getByID($userlogin->userID());
        $config = array('onlyIfUserSubscribed'=>True,
                         'user'=>$user,
                         'includeGroupSubscriptions'=>True
                        );
        $topic = $this->topic_db->getByID($topic_id, $config);
        if ($topic == null) {
            appendErrorMessage(__("Add bookmarked publications to topic").": ".__("non-existing id passed").".<br/>");
            redirect('bookmarklist/viewlist');
        } 
        $this->bookmarklist_db->addToTopic($topic);
        $this->viewlist();
    }

    /** 
    bookmarklist/maketopic
    
    Entry point for turning all publications in the bookmark list into a new topic.
    
    Fails with error message when one of:
        insufficient rights
        
    Parameters passed via POST:
        none
             
    Redirects to the bookmarklist/edit controller for the new topic
        
    */
    function maketopic() {
      $userlogin = getUserLogin();
        if (!$userlogin->hasRights('bookmarklist')) {
            appendErrorMessage(__('Making topic from bookmarklist').': '.__('insufficient rights').' (bookmarklist).<br/>');
            redirect('');
        }
        if (!$userlogin->hasRights('topic_edit')) {
            appendErrorMessage(__('Making topic from bookmarklist').': '.__('insufficient rights').' (topic_edit).<br/>');
            redirect('');
        }
        
        $topic = new Topic;
        $topic->name = __('-new from bookmarklist-');
        if (!$topic->add()) {
            appendErrorMessage(__('Error creating topic.').'<br/>');
            redirect('');
        }
        $this->bookmarklist_db->addToTopic($topic);
        redirect('topics/edit/'.$topic->topic_id);
    }

    /** 
    bookmarklist/removefromtopic
    
    Entry point for removing all publications in the bookmark list from a certain topic.
    
    Fails with error message when one of:
        insufficient rights
        nonexisting topic
        
    Parameters passed via POST:
        topic_id
             
    Redirects to the bookmarklist/view controller
        
    */
    function removefromtopic() {
        //check rights is done in the $this->bookmarklist_db->removePublication function, no need to do it twice

          $topic_id = $this->input->post('topic_id');
        $userlogin  = getUserLogin();
        $user       = $this->user_db->getByID($userlogin->userID());
        $config = array('onlyIfUserSubscribed'=>True,
                         'user'=>$user,
                         'includeGroupSubscriptions'=>True
                        );
        $topic = $this->topic_db->getByID($topic_id, $config);
        if ($topic == null) {
            appendErrorMessage(__("Remove bookmarked publications from topic").": ".__("non-existing id passed").".<br/>");
            redirect('bookmarklist/viewlist');
        } 
        $this->bookmarklist_db->removeFromTopic($topic);
        $this->viewlist();
    }

    /** 
    bookmarklist/deleteall
    
    Entry point for deleting all from the bookmarklist.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    deleting. 
    
    Fails with error message when one of:
        insufficient user rights
        
    Parameters passed via URL segments:
        4rd: if the 3rd segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified
             
    Returns:
        A full HTML page showing a 'request confirmation' form for the delete action, if no 'commit' was specified
        Redirects somewhere (bookmarklist page) after deleting, if 'commit' was specified
    */
    function deleteall()
    {
        $commit = $this->uri->segment(3,'');

        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();

        if (!$userlogin->hasRights('bookmarklist') || !$userlogin->hasRights('publication_edit')) {
            appendErrorMessage(__('Deleting publications from bookmarklist').': '.__('insufficient rights').'.<br/>');
            redirect('');
        }

        if ($commit=='commit') {
            //do delete, redirect somewhere
            $publications = $this->publication_db->getForBookmarkList('',-1);
            $nrdeleted = 0;
            $nrskipped = 0;
            foreach ($publications as $publication) {
                if ($this->accesslevels_lib->canEditObject($publication)) {
                    if ($publication->delete()) {
                        $nrdeleted++;
                    } else {
                        $nrskipped++;
                    }
                } else {
                    $nrskipped++;
                }
            }
            appendMessage(__('Deleted %d publications.'), $nrdeleted);
            if ($nrskipped>0)appendMessage(sprintf(__('Skipped %d publications due to insufficient rights.'),$nrskipped));
            redirect('bookmarklist');
        } else {
            $this->load->view('header', array('title' => __('Delete all from bookmarklist')));
            $this->load->view('bookmarklist/ask', array('target'=>'bookmarklist/deleteall/commit',
                              'question'=>__('Are you sure that you want to delete '.
                              'all publications on the bookmarklist from your database?')));
            $this->load->view('footer');
        }
    }  

    /** 
    bookmarklist/setpubaccesslevel
    
    Entry point for setting the READ access level for all publications on the bookmarklist.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    changing access levels. 
    
    Fails with error message when one of:
        insufficient user rights
        
    Parameters passed via URL segments:
        4rd: if the 3rd segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified

    Parameters passed via POST:
        accesslevel: (public|intern|private)
             
    Returns:
        A full HTML page showing a 'request confirmation' form for the action, if no 'commit' was specified
        Redirects somewhere (bookmarklist page) after setting access levels, if 'commit' was specified
    */
    function setpubaccesslevel()
    {
        $commit = $this->uri->segment(3,'');
        $accesslevel = $this->input->post('accesslevel');
        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();

        if (!$userlogin->hasRights('bookmarklist') || !$userlogin->hasRights('publication_edit')) {
            appendErrorMessage(__('Setting access levels of publications from bookmarklist').': '._('insufficient rights').'.<br/>');
            redirect('');
        }
        if (!in_array($accesslevel,array('public','intern','private'))) {
            appendErrorMessage(__('Setting access levels of publications from bookmarklist').': '.__('no existing access level specified').'.<br/>');
            redirect('bookmarklist');
        }
        if ($commit=='commit') {
            //do set levels, redirect somewhere
            $publications = $this->publication_db->getForBookmarkList('',-1);
            $nrchanged = 0;
            $nrskipped = 0;
            foreach ($publications as $publication) {
                if ($this->accesslevels_lib->canEditObject($publication)) {
                    if ($publication->read_access_level != $accesslevel) {
                        $this->accesslevels_lib->setReadAccessLevel('publication',$publication->pub_id,$accesslevel);
                        $nrchanged++;
                    } 
                } else {
                    $nrskipped++;
                }
            }
            appendMessage(sprintf(__('Set %s access level of %s publications to "%s"'),__('read'), $nrchanged,$accesslevel).'<br/>');
            if ($nrskipped>0)appendMessage(sprintf(__('Skipped %s publications due to insufficient rights.'),$nrskipped).'<br/>');
            redirect('bookmarklist');
        } else {
            $this->load->view('header', array('title'=>__('Set access level')));
            $this->load->view('bookmarklist/ask', array('accesslevel'=>$accesslevel,
                              'target' => 'bookmarklist/setpubaccesslevel/commit',
                              'question' => __('Are you sure that you want to set the '.
                              'read access level for all publications on the bookmarklist '.
                              'to &ldquo;%s&rdquo;?')));
            $this->load->view('footer');
        }
    }    

    /** 
    bookmarklist/setattaccesslevel
    
    Entry point for setting the READ access level for all attachments of publications on the bookmarklist.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    changing access levels. 
    
    Fails with error message when one of:
        insufficient user rights
        
    Parameters passed via URL segments:
        4rd: if the 3rd segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified

    Parameters passed via POST:
        accesslevel: (public|intern|private)
             
    Returns:
        A full HTML page showing a 'request confirmation' form for the action, if no 'commit' was specified
        Redirects somewhere (bookmarklist page) after setting access levels, if 'commit' was specified
    */
    function setattaccesslevel()
    {
        $commit = $this->uri->segment(3,'');
        $accesslevel = $this->input->post('accesslevel');
        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();

        if (!$userlogin->hasRights('bookmarklist') || !$userlogin->hasRights('attachment_edit')) {
            appendErrorMessage(__('Setting access levels of attachments from bookmarklist').': '._('insufficient rights').'.<br/>');
            redirect('');
        }
        if (!in_array($accesslevel,array('public','intern','private'))) {
            appendErrorMessage(__('Setting access levels of attachments from bookmarklist').': '.__('no existing access level specified').'.<br/>');
            redirect('bookmarklist');
        }
        if ($commit=='commit') {
            //do set levels, redirect somewhere
            $publications = $this->publication_db->getForBookmarkList('',-1);
            $nrchanged = 0;
            $nrskipped = 0;
            foreach ($publications as $publication) {
                foreach ($publication->getAttachments() as $attachment) {
                    if ($this->accesslevels_lib->canEditObject($attachment)) {
                        if ($attachment->read_access_level != $accesslevel) {
                            $this->accesslevels_lib->setReadAccessLevel('attachment',$attachment->att_id,$accesslevel);
                            $nrchanged++;
                        } 
                    } else {
                        $nrskipped++;
                    }
                }
            }
            appendMessage(sprintf(__('Set %s access level of %s attachments to "%s".'),__('read'),$nrchanged,$accesslevel).'<br/>');
            if ($nrskipped>0)appendMessage(sprintf(__('Skipped %s attachments due to insufficient rights.'),$nrskipped).'<br/>');
            redirect('bookmarklist');
        } else {
            $this->load->view('header', array('title' => __('Set access level')));
            $this->load->view('bookmarklist/ask',
                               array('accesslevel'=>$accesslevel, 
                               'target' => 'bookmarklist/setattaccesslevel/commit',
                               'question' => __('Are you sure that you want to set the '.
                               'read access level for all attachments of publications on '.
                               'the bookmarklist to &ldquo;%s&rdquo;?')));
            $this->load->view('footer');
        }
    }    

    /** 
    bookmarklist/seteditpubaccesslevel
    
    Entry point for setting the edit access level for all publications on the bookmarklist.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    changing access levels. 
    
    Fails with error message when one of:
        insufficient user rights
        
    Parameters passed via URL segments:
        4rd: if the 3rd segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified

    Parameters passed via POST:
        editaccesslevel: (public|intern|private)
             
    Returns:
        A full HTML page showing a 'request confirmation' form for the action, if no 'commit' was specified
        Redirects somewhere (bookmarklist page) after setting access levels, if 'commit' was specified
    */
    function seteditpubaccesslevel()
    {
        $commit = $this->uri->segment(3,'');
        $editaccesslevel = $this->input->post('accesslevel');
        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();

        if (!$userlogin->hasRights('bookmarklist') || !$userlogin->hasRights('publication_edit')) {
            appendErrorMessage(__('Setting access levels of publications from bookmarklist').': '._('insufficient rights').'.<br/>');
            redirect('');
        }
        if (!in_array($editaccesslevel,array('public','intern','private'))) {
            appendErrorMessage(__('Setting access levels of publications from bookmarklist').': '.__('no existing access level specified').'.<br/>');
            redirect('bookmarklist');
        }
        if ($commit=='commit') {
            //do set levels, redirect somewhere
            $publications = $this->publication_db->getForBookmarkList('',-1);
            $nrchanged = 0;
            $nrskipped = 0;
            foreach ($publications as $publication) {
                if ($this->accesslevels_lib->canEditObject($publication)) {
                    if ($publication->edit_access_level != $editaccesslevel) {
                        $this->accesslevels_lib->setEditAccessLevel('publication',$publication->pub_id,$editaccesslevel);
                        $nrchanged++;
                    } 
                } else {
                    $nrskipped++;
                }
            }
            appendMessage(sprintf(__('Set %s access level of %s publications to "%s"'),__('edit'),$nrchanged,$editaccesslevel));
            if ($nrskipped>0)appendMessage(sprintf(__('Skipped %s publications due to insufficient rights.'),$nrskipped));
            redirect('bookmarklist');
        } else {
            $this->load->view('header', array('title' => __('Set edit access level')));
            $this->load->view('bookmarklist/ask',
                               array('accesslevel'=>$editaccesslevel,
                               'target' => 'bookmarklist/seteditpubaccesslevel/commit',
                               'question' => __('Are you sure that you want to set the '.
                               'edit access level for all publications on the bookmarklist '.
                               'to &ldquo;%s&rdquo;?')));
            $this->load->view('footer');
        }
    }    

    /** 
    bookmarklist/seteditattaccesslevel
    
    Entry point for setting the edit access level for all attachments of publications on the bookmarklist.
    Depending on whether 'commit' is specified in the url, confirmation may be requested before actually
    changing access levels. 
    
    Fails with error message when one of:
        insufficient user rights
        
    Parameters passed via URL segments:
        4rd: if the 3rd segment is the string 'commit', no confirmation is requested.
             if not, a confirmation form is shown; upon choosing 'confirm' this same controller will be 
             called with 'commit' specified

    Parameters passed via POST:
        accesslevel: (public|intern|private)
             
    Returns:
        A full HTML page showing a 'request confirmation' form for the action, if no 'commit' was specified
        Redirects somewhere (bookmarklist page) after setting access levels, if 'commit' was specified
    */
    function seteditattaccesslevel()
    {
        $commit = $this->uri->segment(3,'');
        $editaccesslevel = $this->input->post('accesslevel');
        //besides the rights needed to READ this publication, checked by publication_db->getByID, we need to check:
        //edit_access_level and the user edit rights
        $userlogin  = getUserLogin();

        if (!$userlogin->hasRights('bookmarklist') || !$userlogin->hasRights('attachment_edit')) {
            appendErrorMessage(sprintf(__('Setting %s access levels of attachments from bookmarklist: %s'), __('edit'), __('insufficient rights.')));
            redirect('');
        }
        if (!in_array($editaccesslevel,array('public','intern','private'))) {
            appendErrorMessage(sprintf(__('Setting %s access levels of attachments from bookmarklist: %s'), __('edit'), __('no existing access level specified.')));
            redirect('bookmarklist');
        }
        if ($commit=='commit') {
            //do set levels, redirect somewhere
            $publications = $this->publication_db->getForBookmarkList('',-1);
            $nrchanged = 0;
            $nrskipped = 0;
            foreach ($publications as $publication) {
                foreach ($publication->getAttachments() as $attachment) {
                    if ($this->accesslevels_lib->canEditObject($attachment)) {
                        if ($attachment->edit_access_level != $editaccesslevel) {
                            $this->accesslevels_lib->setEditAccessLevel('attachment',$attachment->att_id,$editaccesslevel);
                            $nrchanged++;
                        } 
                    } else {
                        $nrskipped++;
                    }
                }
            }
            appendMessage(sprintf(__('Set %s access level of %s attachments to &ldquo;%s&rdquo;.'),__('edit'), $nrchanged,$editaccesslevel));
            if ($nrskipped>0)appendMessage(sprintf(__('Skipped %s attachments due to insufficient rights.'),$nrskipped));
            redirect('bookmarklist');
        } else {
            $this->load->view('header', array('title' => __('Set access level')));
            $this->load->view('bookmarklist/ask',
                               array('accesslevel'=>$editaccesslevel,
                               'target' => 'bookmarklist/seteditattaccesslevel/commit',
                               'question' => __('Are you sure that you want to set the '.
                               'edit access level for all attachments of publications on '.
                               'the bookmarklist to &ldquo;%s&rdquo;?')));
            $this->load->view('footer');
        }
    }    

    /** 
    bookmarklist/clear
    
    Clear bookmarklist
    
    Fails with error message when one of:
        insufficient rights
        
    Parameters passed via POST:
        none
             
    Redirects to the bookmarklist/view controller
        
    */
    function clear() {
      $userlogin = getUserLogin();
        if (!$userlogin->hasRights('bookmarklist')) {
            appendErrorMessage(__('Clear bookmarklist: insufficient rights.'));
            redirect('');
        }
        $this->bookmarklist_db->clear();
        $this->viewlist();
    }

    /**
    bookmarklist/exportEmail

    Sends the publications in the bookmark list to the spesified email address(es).

    Fails with error message when one of:
        insufficient rights

    Parameters passed via POST segments:
        email_pdf
        email_bibtex
        email_ris
        email_address
        email_formatted

    Returns:
            A full HTML page with the list of bookmarked publications
    */
    function exportEmail() {
        restrict_to_right('export_email', __('Export through email'), '/bookmarklist');
        restrict_to_right('bookmarklist', __('View bookmarklist'), '');
        $this->load->library('email_export');
            
        $email_pdf = $this->input->post('email_pdf');
        $email_bibtex = $this->input->post('email_bibtex');
        $email_ris = $this->input->post('email_ris');
        $email_address = $this->input->post('email_address');
        $email_formatted = $this->input->post('email_formatted');
        $order='year';
        $recipientaddress   = $this->uri->segment(3,-1);
        $publications = $this->publication_db->getForBookmarkList($order);



        /*
            IF the recipient's address is missing or if none of the data formats are selected THEN show the format selection form.
        */
        if(!(($email_pdf !='' || $email_bibtex !='' || $email_ris!='' || $email_formatted!='') && $email_address != ''))
        {
            $header ['title']       = __('Select export format');

            $content['attachmentsize']  = $this->email_export->attachmentSize($publications);
            $content['controller']    ='bookmarklist/exportEmail';
            if(isset($recipientaddress))
            {
                $replace = array('AROBA', 'KOMMA');
                $with   = array('@', ',');
                $content['recipientaddress'] = str_replace($replace, $with, $recipientaddress);;
            }

            //get output
            $this->load->view('header', $header);
            $this->load->view('export/chooseformatEmail', $content);
            $this->load->view('footer');
            return;
        }
        /*
            ELSE process the request and send the email.
        */
        else
        {
            //get output
            $this->load->helper('publication');

            $content['header']          = __('Export by email');
            $content['publications']    = $publications;
            $content['order'] = $order;

            $messageBody = sprintf(__('Export from %s'), 'Puma.Phi');

            if($email_formatted || $email_bibtex)
            {
                $this->publication_db->enforceMerge = True;
                $publicationMap = $this->publication_db->getForBookmarkListAsMap();
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported from bookmarklist');
                $exportdata['exportEmail']   = true;
            }


            /*
                FORMATTED text is added first. HTML format is selected because this gave nice readable text without having to change or make any views.
            */
            if($email_formatted)
            {
                $messageBody .= "\n";
                $messageBody .= __('Formatted');
                $messageBody .= "\n";

                $exportdata['format'] = 'html';
                $exportdata['sort'] = $this->input->post('sort');
                $exportdata['style'] = $this->input->post('style');
                $messageBody .= strip_tags($this->load->view('export/'.'formattedEmail', $exportdata, True));
            }

            /*
                BIBTEX added.
            */
            if($email_bibtex)
            {
                $messageBody .= "\n";
                $messageBody .= 'BibTeX';
                $messageBody .= "\n";
                $messageBody .= strip_tags($this->load->view('export/'.'bibtexEmail', $exportdata, True));
            }
            /*
                RIS added.
            */
            if($email_ris)
            {
                $messageBody .= "\n";
                $messageBody .= 'RIS';
                $messageBody .= "\n";

                $this->publication_db->suppressMerge = False;
                $publicationMap = $this->publication_db->getForBookmarkListAsMap();
                $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
                $pubs = $splitpubs[0];
                $xrefpubs = $splitpubs[1];

                #send to right export view
                $exportdata['nonxrefs'] = $pubs;
                $exportdata['xrefs']    = $xrefpubs;
                $exportdata['header']   = __('Exported from bookmarklist');
                $exportdata['exportEmail']   = true;

                $messageBody .= strip_tags($this->load->view('export/'.'risEmail', $exportdata, True));

            }


            /*
                If PDFs are not selected the publication array is removed and no attachments will be added.
            */
            if(!$email_pdf)
            {
                $publications = array();
            }

            /*
                Sending MAIL.
            */
            if(! $this->email_export->sendEmail($email_address, $messageBody, $publications)) {
                appendErrorMessage(__('Something went wrong when exporting the publications. Did you input a correct email address?'));
            }
            
            $headerdata = array(
                'title' => __('Bookmark list'),
                'sortPrefix' => '/bookmarklist/viewlist/',
                'exportCommand' => 'export/bookmarklist/',
                'exportName' => __('Export bookmarklist'));
            $this->load->view('header', $headerdata);
            $this->load->view('put', array('data' => __('Mail sent successfully')));
            $this->load->view('footer');
        }
    }

}

//__END__