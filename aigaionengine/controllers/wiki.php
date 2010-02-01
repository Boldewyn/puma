<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends Controller {

    function Wiki() {
        parent::Controller();
    }

    /** Wiki */
    function index() {
        $this->load->view('header', array('title' => __('Wiki')));
        $this->load->view('put', array('data' => 'wiki index'));
        $this->load->view('footer');
    }
    
    function item($item) {
        $this->load->view('header', array('title' => __('Wiki » %s')));
        $this->load->view('put', array('data' => 'wiki item '.$item));
        $this->load->view('footer');
    }
    
    function discussion($item) {
        $this->load->view('header', array('title' => __('Wiki » %s')));
        $this->load->view('put', array('data' => 'wiki discussion '.$item));
        $this->load->view('footer');
    }

    function history($item) {
        $this->load->view('header', array('title' => __('Wiki » %s')));
        $this->load->view('put', array('data' => 'wiki history '.$item));
        $this->load->view('footer');
    }

}

