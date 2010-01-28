<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keywords extends Controller {

	function Keywords()
	{
		parent::Controller();
		
		$this->load->helper('publication');
	}
	
  /** Default function: list publications */
  function index()
	{
    $this->_keywordlist();
	}

  /** List all keywords of one topic in a keyword cloud.
    When no parameter is given, display all publications
  **/
  function _keywordlist()
  {
    $this->load->helper('form');

//    $topic_id = $this->uri->segment(3,-1);
//    if ($topic_id == -1)
//      $topic_id = 1; //no topic? display all
//    $config = array();
//    $topic = $this->topic_db->getByID($topic_id,$config);
//
//    if ($topic==null) {
//        appendErrorMessage(__('Keywords for topic').': '.__('non-existing id passed').'<br/>');
//        redirect('');
//    }
//    
//    $keywordList = $topic->getKeywords();
    $keywordList = $this->keyword_db->getAllKeywords();
    
    //set header data
    $header ['title']         = __('Keywords');
    $header ['javascripts']   = array('prototype.js');
    //$content['header']        = sprintf(__("Keywords for topic %s"),anchor('topics/single/'.$topic->topic_id,$topic->name));
    $content['header']        = "All keywords in the database";
    $content['keywordList']   = $keywordList;
    $content['searchbox']     = True;
    
    //get output
    $output  = $this->load->view('header',              $header,  true);
    $output .= $this->load->view('keywords/list',        $content, true);
    
    $output .= $this->load->view('footer',              '',       true);
    
    //set output
    $this->output->set_output($output);
  }
    
  function searchlist()
  {
    $keyword = $this->input->post('keyword_search');
    if ($keyword) // user pressed show, so redirect to single keyword page
    {
      $keywordList     = $this->keyword_db->getKeywordsLike($keyword);
      if (sizeof($keywordList) > 0)
      {
        $this->single($keywordList[0]->keyword_id);
      }     
    }
    else
    {
      $keyword                = $this->uri->segment(3);
      
      $content['keywordList'] = $this->keyword_db->getKeywordsLike($keyword);
      $content['useHeaders']  = true;
      
      echo $this->load->view('keywords/list_items', $content, true);
    }
  }
  
  function li_keywords($fieldname = "")
  {
    if ($fieldname == "")
      $fieldname = 'keywords';
    
    $keyword = $this->input->post($fieldname);
    if ($keyword != "")
    {
      $content['keywordList'] = $this->keyword_db->getKeywordsLike($keyword);
      $content['useHeaders']  = false;
      
      echo $this->load->view('keywords/list_items', $content, true);
    }
  }
  
  /** 
  single
  
  Entry point for showing a list of publications that have been assigned the given keyword
  
  fails with error message when one of:
    non existing keyword_id
	    
  Parameters passed via segments:
      3rd:  keyword_id
      4rth: sort order
	  5th:  page number
	         
  Returns:
      A full HTML page with all a list of all publications that have been assigned the given keyword
  */
  function single($keyword_id)
  {
    $order = '';
    if (!is_numeric($keyword_id))
    {
      $keyword_id   = $this->uri->segment(3);
      $order   = $this->uri->segment(4,'year');
    }
    if (!in_array($order,array('year','type','recent','title','author'))) {
      $order='year';
    }
    $page   = $this->uri->segment(5,0);
    
    //load keyword
    $keyword = $this->keyword_db->getByID($keyword_id);
    //$keyword = $keywordResult[$keyword_id];
    if ($keyword == null)
    {
      appendErrorMessage(__("View publications for keyword").": ".__("non-existing id passed").".<br/>");
      redirect('');
    }
    $keywordContent ['keyword'] = $keyword;
    
    $this->load->helper('publication');
    
    $userlogin = getUserLogin();
    
    //set header data
    $header ['title']       = __('Keyword').': "'.$keyword->keyword.'"';
    $header ['javascripts'] = array('tree.js','prototype.js','scriptaculous.js','builder.js');
    $header ['sortPrefix']       = 'publications/keyword/'.$keyword->keyword_id.'/';
    $header ['exportCommand']    = '';//'export/keyword/'.$keyword_id.'/';
    $header ['exportName']    = __('Export for keyword');

    //set data
    $publicationContent['header']       = sprintf(__('Publications for keyword "%s"'),$keyword->keyword);
    switch ($order) {
        case 'type':
            $publicationContent['header']          = sprintf(__('Publications for keyword "%s"'),$keyword->keyword).' '.__('sorted by journal and type');
            break;
        case 'recent':
            $publicationContent['header']          = sprintf(__('Publications for keyword "%s"'),$keyword->keyword).' '.__('sorted by recency');
            break;
        case 'title':
            $publicationContent['header']          = sprintf(__('Publications for keyword "%s"'),$keyword->keyword).' '.__('sorted by title');
            break;
        case 'author':
            $publicationContent['header']          = sprintf(__('Publications for keyword "%s"'),$keyword->keyword).' '.__('sorted by first author');
            break;
    }
    if ($userlogin->getPreference('liststyle')>0) {
        //set these parameters when you want to get a good multipublication list display
        $publicationContent['multipage']       = True;
        $publicationContent['currentpage']     = $page;
        $publicationContent['multipageprefix'] = 'publications/keyword/'.$keyword->keyword_id.'/'.$order.'/';
    }    
    $publicationContent['publications'] = $this->publication_db->getForKeyword($keyword,$order);
    $publicationContent['order'] = $order;

    
    //get output
    $output  = $this->load->view('header',              $header,              true);
    $output .= $this->load->view('keywords/single',     $keywordContent,      true);
    if ($publicationContent['publications'] != null) {
      $output .= $this->load->view('publications/list', $publicationContent,  true);
    }
    else
      $output .= "<div class='messagebox'>".__("No publications found using this keyword.")."</div>";
    
    $output .= $this->load->view('footer',              '',             true);
    
    //set output
    $this->output->set_output($output);  
  }  
}
?>