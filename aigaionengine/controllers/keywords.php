<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Keywords extends Controller {

    function Keywords() {
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
            'subnav_current' => '/keywords',
        ));
        $this->load->helper('publication');
    }
    
    /** Default function: list publications */
    function index() {
        $this->load->helper('form');
        $keywordList = $this->keyword_db->getAllKeywords('keyword');
        
        //set header data
        $header = array('title' => __('Keywords'));
        $content = array('header' => __('All keywords in the database'),
            'keywordList' => $keywordList,
            'searchbox'   => True,
        );
        
        $this->load->view('header', $header);
        $this->load->view('keywords/list', $content);
        $this->load->view('footer');
    }
    
    function searchlist() {
        $keyword = $this->input->post('keyword_search');
        if ($keyword) { // user pressed show, so redirect to single keyword page
            $keywordList = $this->keyword_db->getKeywordsLike($keyword);
            if (sizeof($keywordList) > 0) {
                $this->single($keywordList[0]->keyword_id);
            }
        } else {
            $keyword = $this->uri->segment(3);
            $content = array('keywordList' => $this->keyword_db->getKeywordsLike($keyword),
                             'useHeaders'  => true,
                             'columnize' => true);
            $this->load->view('keywords/list_items', $content);
        }
    }
  
    function li_keywords($fieldname = '') {
        if ($fieldname == '') {
            $fieldname = 'keywords';
        }
        
        $keyword = $this->input->post($fieldname);
        if ($keyword != '') {
            $content = array('keywordList' => $this->keyword_db->getKeywordsLike($keyword),
                             'useHeaders'  => false);
            //$this->load->view('keywords/list_items', $content);
            $this->output->set_header('Content-Type: text/javascript');
            $r = array();
            foreach ($content['keywordList'] as $key) {
                $r[] = $key->keyword;
            }
            $this->output->set_output(json_encode($r));
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
    function single($keyword_id) {
        $order = '';
        if (!is_numeric($keyword_id)) {
            $keyword_id   = $this->uri->segment(3);
            $order   = $this->uri->segment(4,'year');
        }
        if (!in_array($order,array('year','type','recent','title','author'))) {
            $order='year';
        }
        $page   = $this->uri->segment(5,0);
        
        //load keyword
        $keyword = $this->keyword_db->getByID($keyword_id);
        if ($keyword == null) {
            appendErrorMessage(__('View publications for keyword: non-existing id passed.'));
            redirect('/keywords');
        }
        $keywordContent ['keyword'] = $keyword;
        
        $this->load->helper('publication');
        
        $userlogin = getUserLogin();
        
        //set header data
        $header = array('title' => sprintf(__('Keyword: &ldquo;%s&rdquo;'), $keyword->keyword));

        //set data
        $publicationContent = array('header' => sprintf(__('Publications for keyword &ldquo;%s&rdquo;'),$keyword->keyword).' %s');
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
            $publicationContent['multipageprefix'] = 'publications/keyword/'.$keyword->keyword_id.'/'.$order.'/';
        }    
        $publicationContent['publications'] = $this->publication_db->getForKeyword($keyword,$order);
        $publicationContent['order'] = $order;

        
        //get output
        $this->load->view('header',          $header);
        $this->load->view('keywords/single', $keywordContent);
        if ($publicationContent['publications'] != null) {
            $this->load->view('publications/list', $publicationContent);
        } else {
            $this->load->view('put', array('data' => '<p class="error">'.__('No publications found using this keyword.').'</p>'));
        }
        $this->load->view('footer');
    }
}

//__END__
