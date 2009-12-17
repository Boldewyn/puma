<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Front extends Controller {

    function Front() {
        parent::Controller();
    }

    /** The front page */
    function index() {
        //the front controller reloads the config settings;
        //after that it redirects to the topic tree. Why is this? Because otherwise another user would not pick
        //up changed config settings (e.g. new file types) without loggin of, closing the browser and cleaning the session.
        $this->latesession->set('SITECONFIG',null);

        $this->load->view('header', array('title' => __('Start')));
        $this->load->view('site/stats', array('embed'=>1));
        $this->load->view('put', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }
}

