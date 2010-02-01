<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Create extends Controller {

    function Create() {
        parent::Controller();
    }

    /** initial find */
    function index() {
        $this->load->view('header', array('title' => __('Create')));
        $this->load->view('create/index', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function upload() {
        $this->load->view('header', array('title' => __('Create » Upload')));
        $this->load->view('create/upload', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function publication($publication=Null) {
        $this->load->view('header', array('title' => __('Create » Publication')));
        $this->load->view('create/publication', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function tag($tag=Null) {
        $this->load->view('header', array('title' => __('Create » Tag')));
        $this->load->view('create/tag', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function topic($topic=Null) {
        $this->load->view('header', array('title' => __('Create » Topic')));
        $this->load->view('create/topic', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function author($author=Null) {
        $this->load->view('header', array('title' => __('Create » Author')));
        $this->load->view('create/author', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

}

//__END__