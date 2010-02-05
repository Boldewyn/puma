<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Explore extends Controller {

    function Explore() {
        parent::Controller();
        $this->load->vars(array('subnav' => array(
            '/explore/' => __('All'),
            '/explore/topic' => __('Topics'),
            '/explore/tag' => __('Tags'),
            '/explore/publication' => __('Publications'),
            '/explore/author' => __('Authors'),
        )));
    }

    /** initial find */
    function index() {
        $this->load->view('header', array('title' => __('Explore')));
        $this->load->view('explore/index', array('data' => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function publication($publication=Null) {
        $this->load->view('header', array('title' => __('Explore » Publication')));
        $this->load->view('explore/publication', array('data' => "Hallo Puma.&Phi;!"));
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
        if (is_user()) { $config['user'] = $userlogin->user(); }
        $root = $this->topic_db->getByID($topic? $topic:1, $config);
        if ($root == null) {
            appendErrorMessage($topic? __('Explore topics: non-existing id passed.'):
                                       __('Explore topics: no topics yet.'));
            redirect('/explore/');
        }
        $this->load->vars(array('subviews'  => array('topics/maintreerow'=>array('useCollapseCallback'=>True)),
                                'collapsed' => option_get_like('topic_collapsed_%')));
        $this->load->view('header', array('title' => __('Explore » Topic')));
        $this->load->view('explore/topic', array('topics' => $root->getChildren()));
        $this->load->view('footer');
    }

    /**
     *
     */
    function tag($tag=Null) {
        $this->load->view('header', array('title' => __('Explore » Tag')));
        $this->load->view('explore/tag', array('data' => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function author($author=Null) {
        $this->load->view('header', array('title' => __('Explore » Author')));
        $this->load->view('explore/author', array('data' => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

}

//__END__