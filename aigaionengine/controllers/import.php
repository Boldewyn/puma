<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import extends Controller {

    function Import() {
        parent::Controller();
        restrict_to_right('publication_edit', __('Import'));
        $this->load->helper('publication');
    }
    
    /** Default function: list publications */
    function index() {
        $this->viewform();
    }

    function viewform($import_data = '') {
        $this->load->library('import_lib');
        $this->load->view('header', array('title' => __('Import publications')));
        $this->load->view('import/importform', array('content'=>$import_data));
        $this->load->view('footer');
    }
  
    
    /**
     * import/submit - Submit a posted publication to the database
     */
    function submit() {
        $this->load->library('parser_import');
        $this->load->library('import_lib');

        $markasread   = $this->input->post('markasread')=='markasread'; // true iff all imported entries should be marked as 'read' for the user
        
        $import_data  = $this->input->post('import_data');    
        if ($import_data == '') {
            appendErrorMessage(__('Import: no import data entered.'));
            $this->viewform();
            return;
        }
        $type = $this->input->post('format');
        if (!isset($type)||($type==null))$type='auto';
        //is the type known?
        if (($type!='auto') && ($type!='') && !in_array($type,$this->import_lib->getAvailableImportTypes())) {
            appendErrorMessage(sprintf(__('Unknown import format specified (&ldquo;%s&rdquo;). Attempting to automatically identify proper format.'),$type));
            $type = 'auto';
        }
        //try to determine type automatically?
        if ($type=='auto') {
          $type = $this->import_lib->determineImportType($import_data);
          if ($type == 'unknown') {
            appendErrorMessage(__('Import: Can&rsquo;t automatically figure out import data format; please specify correct format.'));
            $this->viewform($import_data);
            return;
          } else {
            appendMessage(sprintf(__('Import: Data automatically identified as format &ldquo;%s&rdquo;.'),$type));
          }
        }
        
        switch ($type) {
          case 'BibTeX':
            $this->load->library('parseentries');
            $this->parser_import->loadData(getConfigurationSetting('BIBTEX_STRINGS_IN')."\n".$import_data);
            $this->parser_import->parse($this->parseentries);
            $publications = $this->parser_import->getPublications();
            break;
          case 'ris':
            $this->load->library('parseentries_ris');
            $this->parser_import->loadData($import_data);
            $this->parser_import->parse($this->parseentries_ris);
            $publications = $this->parser_import->getPublications();
            break;
          case 'refer':
            $this->load->library('parseentries_refer');
            $this->parser_import->loadData($import_data);
            $this->parser_import->parse($this->parseentries_refer);
            $publications = $this->parser_import->getPublications();
            break;
          default:
        }

        if (count($publications)==0) {
          appendErrorMessage(__('Import: Could not extract any valid publication entries from the import data. Please verify the input.').'<br/>'.
                     __('If the input is correct, please verify the contents of the &ldquo;BibTeX strings&rdquo; setting under '.
                                '&ldquo;In- and output settings&rdquo; in the site configuration screen.'), 'severe');
          $this->viewform($import_data);
          return;
        }
              
        $reviewed_publications  = array();
        $review_messages        = array();
        $count                  = 0;
        foreach ($publications as $publication) {
            //get review messages
            
            //review title
            $review['title']     = $this->publication_db->reviewTitle($publication);
            
            //review bibtex_id
            $review['bibtex_id'] = $this->publication_db->reviewBibtexID($publication);
            
            //review keywords
            $review['keywords']  = $this->keyword_db->review($publication->keywords);
            
            //review authors and editors
            $review['authors']   = $this->author_db->review($publication->authors); //each item consists of an array A with A[0] a review message, and A[1] an array of arrays of the similar author IDs
            $review['editors']   = $this->author_db->review($publication->editors); //each item consists of an array A with A[0] a review message, and A[1] an array of arrays of the similar author IDs
            
            $reviewed_publications[$count] = $publication;
            $review_messages[$count]       = $review;
            $count++;
            unset($review);
          }
          $this->review($reviewed_publications, $review_messages,$markasread);
    }

    
  /**
   * import/commit - Commit the (parsed & reviewed) publication(s) to the database
   */
  function commit() {
    $this->load->library('import_lib');

    $import_count = $this->input->post('import_count');
    if ($import_count===False)$import_count = 0;
    
    $markasread   = $this->input->post('markasread')=='markasread'; // true iff all imported entries should be marked as 'read' for the user

    if ($import_count == 0) {
      appendErrorMessage(__('Import: Commit: no publications committed.'));
      $this->viewform();
      return;
    }

    $to_import = array();
    $old_bibtex_ids = array();
    $count = 0;
    for ($i = 0; $i < $import_count; $i++) {

      if ($this->input->post('do_import_'.$i) == 'CHECKED') {
        $count++;
        $publication = $this->publication_db->getFromPost('_'.$i,True);
        $publication->actualyear = $this->input->post('actualyear_'.$i); //note that the actualyear is a field that normally is derived on update or add, but in the case of import, it has been set through the review form!
        $to_import[] = $publication;
        $old_bibtex_ids[$this->input->post('old_bibtex_id_'.$i)] = $count-1;
      }
    }
    $last_id = -1;
    foreach ($to_import as $pub_to_import) {
      //if necessary, change crossref (if reffed pub has changed bibtex_id)
      if (trim($pub_to_import->crossref)!= '') {
	        if (array_key_exists($pub_to_import->crossref,$old_bibtex_ids)) {
	            $pub_to_import->crossref = $to_import[$old_bibtex_ids[$pub_to_import->crossref]]->bibtex_id;
	            //appendMessage('changed crossref entry:'.$publication->bibtex_id.' crossref:'.$publication->crossref);
	        }
      }            
      $pub_to_import = $this->publication_db->add($pub_to_import);
      if ($markasread)$pub_to_import->read('');
      $last_id = $pub_to_import->pub_id;
    }
    appendMessage(sprintf(__('Succesfully imported %d publications.'),$count));
    if ($count == 1) {
      redirect('publications/show/'.$last_id);
    } else {
      redirect('publications/showlist/recent');
    }
  }
  

    /**
     *
     */
    function review($publications, $review_data,$markasread) {
        $this->load->view('header', array('title' => __('Review publication')));
        $this->load->view('import/review', array(
            'publications' => $publications,
            'reviews' => $review_data,
            'markasread' => $markasread,
        ));
        $this->load->view('footer');
    }

 
}

//__END__
