<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Help extends Controller {

    function Help () {
        parent::Controller();
    }

    /**  */
    function index($topic='front') {
        $subnav_current = $topic=='front'? '/help/' : '/help/'.$topic;
        $this->load->view('header', array('title' => __('Help'),
            'subnav'=>array('/help/'=>__('Introduction'),
                '/help/about'=>__('About'),
                '/help/faq'=>__('FAQ'),
                '/help/tutorial'=>__('Video tutorial'),
            ),
            'subnav_current'=>$subnav_current));
        $this->load->view('help/header', array('topic' => $topic));
        $this->load->view('help/'.$topic);
        $this->load->view('footer');
    }


}

//__END__