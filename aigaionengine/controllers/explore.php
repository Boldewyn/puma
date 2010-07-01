<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Explore extends Controller {

    function Explore() {
        parent::Controller();
        $this->load->vars(array('subnav' => array(
            '/explore/' => __('All'),
            '/topics' => __('Topics'),
            '/keywords' => __('Tags'),
            '/publications' => __('Publications'),
            '/authors' => __('Authors'),
        )));
    }

    /** initial view */
    function index() {
        //the front controller reloads the config settings;
        //after that it redirects to the topic tree. Why is this? Because otherwise another user would not pick
        //up changed config settings (e.g. new file types) without loggin of, closing the browser and cleaning the session.
        $this->latesession->set('SITECONFIG',null);

        $content = array('order' => 'recent');

        $Q = $this->db->query("SELECT DISTINCT ".AIGAION_DB_PREFIX."publication.* FROM ".AIGAION_DB_PREFIX."publication, ".AIGAION_DB_PREFIX."topicpublicationlink
        WHERE ".AIGAION_DB_PREFIX."publication.pub_id = ".AIGAION_DB_PREFIX."topicpublicationlink.pub_id
        ORDER BY pub_id DESC LIMIT 0,10");
        $result = array();
        foreach ($Q->result() as $row) {
          $next = $this->publication_db->getFromRow($row);
          if ($next != null) {
            $result[] = $next;
          }
        }
        $content['publications'] = $result;

        $Q = $this->db->query("SELECT ".AIGAION_DB_PREFIX."topics.* FROM ".AIGAION_DB_PREFIX."topics, ".AIGAION_DB_PREFIX."topictopiclink
        WHERE ".AIGAION_DB_PREFIX."topictopiclink.source_topic_id=".AIGAION_DB_PREFIX."topics.topic_id
        ORDER BY topic_id DESC LIMIT 0,10");
        $result = array();
        $configuration = array();
        foreach ($Q->result() as $row) {
            $c = $this->topic_db->getFromRow($row,$configuration);
            if ($c != null) {
                $result[] = $c;
            }
        }
        $content['topics'] = $result;


        $this->load->helper('form');
        $keywordList = $this->keyword_db->getKeywordCloud();
        $keywordContent = array( 'keywordList' => $keywordList,);
         $keywordContent['isCloud'] = true;

        $this->load->view('header', array('title' => __('Explore'), 'nav_current' => 'explore', 'subnav_current' => '/explore/'));
        $this->load->view('site/stats', array('embed'=>1));
        $this->load->view('explore', array('content' => $content));
        $this->load->view('keywords/list_items', $keywordContent);
        $this->load->view('publications/list', $content);
        $this->load->view('footer');
    }

}

//__END__
