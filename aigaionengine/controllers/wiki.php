<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends Controller {

    function Wiki() {
        parent::Controller();
    }

    /** Wiki */
    function index() {
        $this->load->view('header', array('title' => __('Wiki'), 'subnav'=>array('/wiki/'=>__('View')), 'subnav_current'=>'/wiki/'));
        $this->load->view('put', array('data' => 'wiki index'));
        $this->load->view('footer');
    }
    
    function item($item) {
        list($subnav, $subnav_current) = $this->_get_subnav($item);
        $this->load->view('header', array('title' => __('Wiki » %s'), 'subnav'=>$subnav, 'subnav_current'=>$subnav_current));
        $this->load->view('put', array('data' => 'wiki item '.$item));
        $this->load->view('footer');
    }
    
    function edit($item, $discussion=False) {
        restrict_to_users(__('You must be logged in to edit the wiki.'), '/wiki/'.$item);
        list($subnav, $subnav_current) = $this->_get_subnav($item, 'edit');
        $this->load->view('header', array('title' => __('Wiki » %s'), 'subnav'=>$subnav, 'subnav_current'=>$subnav_current));
        if ($discussion == 1) {
            $this->load->view('put', array('data' => 'wiki edit discussion '.$item));
        } else {
            $item .= '/'.$discussion;
            $this->load->view('put', array('data' => 'wiki edit '.$item));
        }
        $this->load->view('footer');
    }

    function discussion($item) {
        list($subnav, $subnav_current) = $this->_get_subnav($item, 'discussion');
        $this->load->view('header', array('title' => __('Wiki » %s'), 'subnav'=>$subnav, 'subnav_current'=>$subnav_current));
        $this->load->view('put', array('data' => 'wiki discussion '.$item));
        $this->load->view('footer');
    }

    function history($item) {
        list($subnav, $subnav_current) = $this->_get_subnav($item, 'history');
        $this->load->view('header', array('title' => __('Wiki » %s'), 'subnav'=>$subnav, 'subnav_current'=>$subnav_current));
        $this->load->view('put', array('data' => 'wiki history '.$item));
        $this->load->view('footer');
    }
    
    protected function _get_subnav($item, $method='') {
        $method = $method? ucfirst($method).':' : '';
        $subnav = array(
            '/wiki/'.$item => __('View'),
            '/wiki/Discussion:'.$item => __('Discussion'),
            '/wiki/Edit:'.$item => __('Edit'),
            '/wiki/History:'.$item => __('History'),
        );
        $subnav_current = '/wiki/'.$method.$item;
        return array($subnav, $subnav_current);
    }

}

