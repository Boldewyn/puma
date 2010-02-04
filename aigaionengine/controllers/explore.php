<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Explore extends Controller {

    function Explore() {
        parent::Controller();
    }

    /** initial find */
    function index() {
        $this->load->view('header', array('title' => __('Explore')));
        $this->load->view('explore/index', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function publication($publication=Null) {
        $this->load->view('header', array('title' => __('Explore » Publication')));
        $this->load->view('explore/publication', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function topic($topic=Null) {
        $userlogin = getUserLogin();
        $user = $this->user_db->getByID($userlogin->userId());
        $config = array('onlyIfUserSubscribed'=>($topic? True : False),
                        'flagCollapsed'=>True,
                        'user'=>$user,
                        'includeGroupSubscriptions'=>True
                        );
        $root = $this->topic_db->getByID($root_id, $config);
        if ($root == null) {
            appendErrorMessage(__("Browse topics: non-existing id passed."));
            redirect('');
        }
        $this->load->view('header', array('title' => __('Explore » Topic')));
        $this->load->view('explore/topic', array('topics' => $root->getChildren()));
        $this->load->view('footer');
    }

    /**
     *
     */
    function tag($tag=Null) {
        $this->load->view('header', array('title' => __('Explore » Tag')));
        $this->load->view('explore/tag', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function author($author=Null) {
        $this->load->view('header', array('title' => __('Explore » Author')));
        $this->load->view('explore/author', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

}

//__END__