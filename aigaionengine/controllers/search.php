<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller {

    private $query = '';

    public function Search() {
        parent::Controller();
        $this->load->vars(array('subnav' => array(
            '/search/' => __('Advanced Search'),
            '/search/external' => __('External Search'),
            )));
        if (isset($_REQUEST['q'])) {
            $this->query = $_REQUEST['q'];
            $this->load->vars(array('query' => $_REQUEST['q']));
        } elseif ($this->input->post('q')) {
            $this->query = $this->input->post('q');
            $this->load->vars(array('query' => $this->input->post('q')));
        } else {
            $this->load->vars(array('query' => ''));
        }
    }

    /** Default: advanced search form */
    public function index() {
        $this->load->view('header', array('title' => __('Advanced search'), 'subnav_current' => '/search/'));
        $this->load->view('search/advanced');
        $this->load->view('footer');
    }

    /** external search */
    public function external() {
        $Q = $this->db->get_where('external_search', array('active' => 1));
        $searchengines = $Q->result_array();
        $this->load->view('header', array('title' => __('External search')));
        $this->load->view('search/external', array('searchengines' => $searchengines));
        $this->load->view('footer');
    }

    /**
     * search/quicksearch
     * Returns a full html page with a search result.
     */
    public function quicksearch() {
        if (trim($this->query)=='') {
            back_to_referrer(__('Search: no query.'), '', True);
        }
        $this->load->library('search_lib');
        $searchresults = $this->search_lib->simpleSearch($this->query,null);

        $this->load->view('header', array('title' => __('Search results')));
        $this->load->view('search/results',
                           array('quicksearch'=>True, 'searchresults'=>$searchresults));
        $this->load->view('footer');
    }

    /**
     * search/advancedresults
     * Returns a full html page with a search result.
     */
    public function advancedresults()
    {
        if ($this->input->post('formname')!='advancedsearch') {
            $this->index();
            return;
        }
      //process query
      $anyAll = $this->input->post('anyAll');
      $doConditions = array();
      $dontConditions = array();
      $config = array('onlyIfUserSubscribed'=>False,
                'includeGroupSubscriptions'=>False);
      for ($i = 1; $i <= $this->input->post('numberoftopicconditions'); $i++) {
          //parse condition. they start with 1!
          $do = $this->input->post('doOrNot'.$i);
          $topic_id = $this->input->post('topicSelection'.$i);
          if ($topic_id=='header')continue;
          $topic = $this->topic_db->getByID($topic_id,$config);
          if ($topic==null) {
            appendMessage(__('Nonexisting topic ID in advanced search condition'));
            continue;
          }
          if ($do=='True') {
            $doConditions[] = $topic;
          } else {
            $dontConditions[] = $topic;
          }
      }
      if ((trim($this->query) == '')&& ((count($doConditions)>0)||(count($dontConditions)>0))) {
        //appendMessage("No query, but some topic restrictions: interpret as a search for ALL publications within topics; don't query for all authors, topics or keywords");
        $this->query='*';
      } else if (trim($this->query) == '') {
        appendMessage(__('No query at all: please give at least a search term or a topic condition'));
        $this->index();
        return;
      }
      $searchoptions = array('advanced');
      if ($this->input->post('return_authors')=='return_authors')
        $searchoptions[] = 'authors';
      if ($this->input->post('return_topics')=='return_topics')
        $searchoptions[] = 'topics';
      if ($this->input->post('return_keywords')=='return_keywords')
        $searchoptions[] = 'keywords';
      if ($this->input->post('return_publications')=='return_publications') {
        $searchoptions[] = 'publications';
        if ($this->input->post('search_publications_titles')=='search_publications_titles')
          $searchoptions[] = 'publications_titles';
        if ($this->input->post('search_publications_notes')=='search_publications_notes')
          $searchoptions[] = 'publications_notes';
        if ($this->input->post('search_publications_bibtex_id')=='search_publications_bibtex_id')
          $searchoptions[] = 'publications_bibtex_id';
        if ($this->input->post('search_publications_abstracts')=='search_publications_abstracts')
          $searchoptions[] = 'publications_abstracts';
      }


      $this->load->library('search_lib');
      if ((count($doConditions>0))||(count($dontConditions>0))) {
        $searchresults = $this->search_lib->topicConditionSearch($this->query,$searchoptions,$doConditions,$dontConditions,$anyAll);
      } else {
          $searchresults = $this->search_lib->simpleSearch($this->query,$searchoptions,'');
        }

        $this->load->view('header', array('title' => __('Advanced search results'), 'subnav_current' => '/search/'));
        $this->load->view('search/results', array('searchresults'=>$searchresults));
        $this->load->view('search/advanced', array('options'=>$searchoptions));
        $this->load->view('footer');
    }
}

//__END__
