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

        $this->load->view('header', array('title' => __('Explore'), 'nav_current' => 'explore', 'subnav_current' => '/explore/'));
        $this->load->view('site/stats', array('embed'=>1));
        $this->load->view('explore', array('data' => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

}

//__END__
