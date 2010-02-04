<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends Controller {

    function Search() {
        parent::Controller();
        $this->subnav = array(
            '/search/' => __('Advanced Search'),
            '/search/external' => __('External Search'),);
    }

    /** Default: advanced search form */
    function index() {
        $headerdata = array('title' => __('Advanced search'),
          'subnav' => $this->subnav,);
        $this->load->view('header', $headerdata);
        $this->load->view('search/advanced');
        $this->load->view('footer');
    }

    /** external search */
    function external() {
        $headerdata = array('title' => __('External search'),
          'subnav' => $this->subnav,
        );
        $this->load->view('header', $headerdata);
        $this->load->view('search/external', array('searchengines' => array(
            array('name' => 'Google Scholar', 'url' => 'http://scholar.google.com/scholar',
                'parameters' => 'a:1:{s:1:"q";s:7:"{query}";}',
                'active' => 1, 'image' => 'http://scholar.google.com/favicon.ico', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => __('ePub Server (Uni)'), 'url' => 'http://epub.uni-regensburg.de/cgi/search',
                'parameters' => 'a:5:{s:1:"q";s:7:"{query}";s:14:"_action_search";s:6:"Search";s:6:"_order";s:7:"bytitle";s:14:"basic_srchtype";s:3:"ALL";s:11:"_satisfyall";s:3:"ALL";}',
                'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => 'arXiv.org', 'url' => 'http://arxiv.org/search', 'parameters' => 'a:1:{s:5:"query";s:7:"{query}";}',
                'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => 'WorldWideScience', 'url' => 'http://worldwidescience.org/wws/search.html',
                'parameters' => 'a:1:{s:10:"expression";s:7:"{query}";}', 'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => 'Inspec', 'url' => 'http://web5.silverplatter.com/webspirs/doAndShowSearch.ws',
                'parameters' => 'a:3:{s:2:"ss";s:7:"{query}";s:4:"orig";s:6:"search";s:10:"basicLimit";s:2:"-1";}',
                 'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'post'),
            array('name' => 'Spires', 'url' => 'http://www.slac.stanford.edu/spires/find/hep/www',
                'parameters' => 'a:3:{s:6:"rawcmd";s:7:"{query}";s:6:"FORMAT";s:3:"WWW";s:8:"SEQUENCE";s:0:"";}', 
                'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => 'Amazon', 'url' => 'http://www.amazon.de/s/ref=nb_ss_w',
                'parameters' => 'a:3:{s:10:"__mk_de_DE";s:10:"ÅMÅZÕÑ";s:3:"url";s:16:"search-alias=aps";s:14:"field-keywords";s:7:"{query}";}',
                'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'get'),
            array('name' => 'PubMed', 'url' => 'http://www.ncbi.nlm.nih.gov/sites/entrez',
                'parameters' => 'a:3:{s:43:"EntrezSystem2.PEntrez.Pubmed.SearchBar.Term";s:7:"{query}";s:41:"EntrezSystem2.PEntrez.Pubmed.SearchBar.Db";s:6:"pubmed";s:41:"EntrezSystem2.PEntrez.Pubmed.SearchBar.Go";s:1:"1";}',
                'active' => 1, 'image' => '', 'charset' => 'utf-8', 'method' => 'post'),
        )));
        $this->load->view('footer');
    }

    /**
    search/quicksearch

    Fails not

    Parameters:
        search query through form value

    Returns a full html page with a search result. */
    function quicksearch() {
        $query = $this->input->post('q');
        if (trim($query)=='') {
            appendErrorMessage(__('Search: No query.'));
            redirect('');
        }
        $this->load->library('search_lib');
        $searchresults = $this->search_lib->simpleSearch($query,null);

        //get output: search result page
        $headerdata = array('title' => __('Search results'),
          'subnav' => $this->subnav,);
        $this->load->view('header', $headerdata);
        $this->load->view('search/results',
                           array('quicksearch'=>True, 'searchresults'=>$searchresults, 'query'=>$query));
        $this->load->view('footer');
    }

    /**
    search/advancedresults

    Fails not

    Parameters:
        search query through form values

    Returns a full html page with a search result. */
    function advancedresults()
    {
        if ($this->input->post('formname')!='advancedsearch') {
            $this->index();
            return;
        }
      //process query
      $query = $this->input->post('searchstring');
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
          $topic = $this->topic_db->getById($topic_id,$config);
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
      if (($query == '')&& ((count($doConditions)>0)||(count($dontConditions)>0))) {
        //appendMessage("No query, but some topic restrictions: interpret as a search for ALL publications within topics; don't query for all authors, topics or keywords");
        $query="*";
      } else if ($query == '') {
        appendMessage(__("No query at all: please give at least a search term or a topic condition"));
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
        $searchresults = $this->search_lib->topicConditionSearch($query,$searchoptions,$doConditions,$dontConditions,$anyAll);
      } else {
          $searchresults = $this->search_lib->simpleSearch($query,$searchoptions,"");
        }

        //get output: search result page
        $headerdata = array('title' => __('Advanced search results'),
          'subnav' => $this->subnav,);

        $this->load->view('header', $headerdata);

        $this->load->view('search/results',
                           array('searchresults'=>$searchresults, 'query'=>$query));

        $this->load->view('search/advanced',
                           array('query'=>$query,'options'=>$searchoptions));

        $this->load->view('footer');
    }
}

//__END__