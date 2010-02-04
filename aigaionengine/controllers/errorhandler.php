<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class ErrorHandler extends MY_Controller {
    function index() {
        $headerdata = array('title' => __('Error: Resource not found'));
        $this->output->set_status_header('404');
        $this->load->view('header', $headerdata);
        $this->load->view('error/404');
        $this->load->view('footer');
    }
}

//__END__