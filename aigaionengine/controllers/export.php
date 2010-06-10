<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Export extends Controller {

    function Export() {
        parent::Controller();
        $this->load->library('parsecreators');
        $this->load->library('parsemonth');
        $this->load->library('parsepage');
    }
    
    /** Pass control to the export/all/ */
    function index() {
        $this->all();
    }
    

    /** 
    export/all
    
    Export all (accessible) entries in the database
    
    Fails with error message when one of:
        never
        
    Parameters passed via URL segments:
        3rd: type (bibtex|ris|email)
             
    Returns:
        A clean text page with exported publications
    */
    function all($type='') {
        if ($type == 'email') { redirect('topics/exportEmail/1'); }
        if (!in_array($type,array('bibtex','ris','formatted','email'))) {
            $this->load->view('header', array('title' => __('Select export format')));
            $this->load->view('export/chooseformat', array('header'=>__('Export all publications'),'exportCommand'=>'export/all/'));
            $this->load->view('footer');
            return;
        }
        $exportdata = $this->_get_exportdata($type, $this->publication_db->getAllPublicationsAsMap());
        $exportdata['header']   = __('All publications');

        $this->load->view('export/'.$type, $exportdata);
    }    

    /** 
    export/topic
    
    Export all (accessible) entries from one topic
    
    Fails with error message when one of:
        non existing topic_id requested
        
    Parameters passed via URL segments:
        3rd: topic_id
        4rth: type (bibtex|ris|email)
             
    Returns:
        A clean text page with exported publications
    */
    function topic($topic_id=-1, $type='') {
        if ($type == 'email') { redirect('topics/exportEmail/'.$topic_id); }
        $config = array();
        $topic = $this->topic_db->getByID($topic_id,$config);
        if ($topic==null) {
            appendErrorMessage(__('Export requested for non existing topic.'));
            redirect('');
        }
        if (!in_array($type,array('bibtex','ris','formatted','email'))) {
            $this->load->view('header', array('title'=>__('Select export format')));
            $this->load->view('export/chooseformat',  array('header'=>sprintf(__('Export all for topic %s'),$topic->name),'exportCommand'=>'export/topic/'.$topic->topic_id.'/'));
            $this->load->view('footer');
            return;
        }
        $exportdata = $this->_get_exportdata($type, $this->publication_db->getForTopicAsMap($topic->topic_id));
        $exportdata['header']   = sprintf(__('All publications for topic &ldquo;%s&rdquo;',$topic->name));
        $this->load->view('export/'.$type, $exportdata);
    }        

    /** 
    export/author
    
    Export all (accessible) entries from one author
    
    Fails with error message when one of:
        non existing author_id requested
        
    Parameters passed via URL segments:
        3rd: author_id
        4rth: type (bibtex|ris|email)
             
    Returns:
        A clean text page with exported publications
    */
    function author($author_id=-1, $type='') {
        if ($type == 'email') { redirect('authors/exportEmail/'.$author_id); }
        $author = $this->author_db->getByID($author_id);
        if ($author==null) {
            appendErrorMessage(__('Export requested for non existing author.'));
            redirect('');
        }
        if (!in_array($type,array('bibtex','ris','formatted','email'))) {
            $this->load->view('header', array('title'=>__('Select export format')));
            $this->load->view('export/chooseformat',  array('header'=>sprintf(__('Export all for author %s'),$author->getName()),'exportCommand'=>'export/author/'.$author->author_id.'/'));
            $this->load->view('footer');
            return;
        }
        $exportdata = $this->_get_exportdata($type, $this->publication_db->getForAuthorAsMap($author->author_id));
        $exportdata['header']   = sprintf(__('All publications for %s'),$author->getName());

        $this->load->view('export/'.$type, $exportdata);
    }       

    /** 
    export/bookmarklist
    
    Export all (accessible) entries from the bookmarklist of this user
    
    Fails with error message when one of:
        insufficient rights
        
    Parameters passed via URL segments:
        3rth: type (bibtex|ris|email)
             
    Returns:
        A clean text page with exported publications
    */
    function bookmarklist($type='') {
        restrict_to_right('bookmarklist', __('Export bookmarklist'));
        if ($type == 'email') { redirect('/bookmarklist/exportEmail'); }
        if (!in_array($type,array('bibtex','ris','formatted'))) {
            $this->load->view('header', array('title'=>__('Select export format')));
            $this->load->view('export/chooseformat',  array('header'=>__('Export all publications on bookmarklist'),'exportCommand'=>'export/bookmarklist/'));
            $this->load->view('footer');
            return;
        }
        $exportdata = $this->_get_exportdata($type, $this->publication_db->getForBookmarkListAsMap());
        $exportdata['header']   = __('Exported from bookmarklist');

        $this->load->view('export/'.$type, $exportdata);
    }        
        
    /** 
    export/publication
    
    Export one publication
    
    Fails with error message when one of:
        non existing pub_id requested
        
    Parameters passed via URL segments:
        3rd: pub_id
        4rth: type (bibtex|ris|email)
             
    Returns:
        A clean text page with exported publications
    */
    function publication($pub_id=-1, $type='') {
        if (!in_array($type,array('bibtex','ris','formatted'))) {
            $this->load->view('header', array('title'=>__('Select export format')));
            $this->load->view('export/chooseformat',  array('header'=>__('Export one publication'),'exportCommand'=>'export/publication/'.$publication->pub_id.'/'));
            $this->load->view('footer');
            return;
        }
        $publication = $this->publication_db->getByID($pub_id);
        if ($publication==null) {
            appendErrorMessage(__('Export requested for non existing publication.'));
            redirect('');
        }
        $exportdata = $this->_get_exportdata($type, array($publication->pub_id=>$publication));

        $this->load->view('export/'.$type, $exportdata);
    }

    /**
     *
     */
    protected function _get_exportdata($type, $publicationMap) {
        //for export, bibtex should NOT merge crossrefs; ris SHOULD merge crossrefs
        $exportdata = array();
        switch ($type) {
            case 'bibtex':
                $this->publication_db->suppressMerge = True;
                break;
            case 'ris':
                $this->publication_db->enforceMerge = True; //although the crossreferenced publications are STILL exported...
                break;
            case 'formatted':
                $this->publication_db->enforceMerge = True;
                $exportdata['format'] = $this->input->post('format');
                $exportdata['sort'] = $this->input->post('sort');
                $exportdata['style'] = $this->input->post('style');
                break;
            default:
                break;
        }
        // split into publications and crossreffed publications, adding crossreffed publications as needed
        $splitpubs = $this->publication_db->resolveXref($publicationMap,false);
        $exportdata['nonxrefs'] = $splitpubs[0];
        $exportdata['xrefs']    = $splitpubs[1];
        return $exportdata;
    }
}

//__END__
