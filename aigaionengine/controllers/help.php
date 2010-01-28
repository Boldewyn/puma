<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Help extends Controller {

    function Help () {
        parent::Controller();
    }

    /**  */
    function index($topic="front") {
        $this->load->view('header', array('title' => __('Help')));
        $this->load->view('help/header');
        $this->load->view('help/'.$topic);
        $this->load->view('footer');
    }


}

//__END__