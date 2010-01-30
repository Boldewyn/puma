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
        $this->load->view('header', array('title' => __('Explore &raquo; Publication')));
        $this->load->view('explore/publication', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function topic($topic=Null) {
        $this->load->view('header', array('title' => __('Explore &raquo; Topic')));
        $this->load->view('explore/topic', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function tag($tag=Null) {
        $this->load->view('header', array('title' => __('Explore &raquo; Tag')));
        $this->load->view('explore/tag', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

    /**
     *
     */
    function author($author=Null) {
        $this->load->view('header', array('title' => __('Explore &raquo; Author')));
        $this->load->view('explore/author', array("data" => "Hallo Puma.&Phi;!"));
        $this->load->view('footer');
    }

}

//__END__