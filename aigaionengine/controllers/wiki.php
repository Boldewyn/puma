<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends Controller {

    
    function Wiki() {
        parent::Controller();
        $this->isDispatched = False;
        $this->load->model('wiki_model', 'wiki');
        $this->load->vars(array('body_id' => 'wiki'));
    }

    /** Wiki */
    function index() {
        $this->load->view('header', array('title' => __('Wiki'), 'subnav'=>array('/wiki/'=>__('View')), 'subnav_current'=>'/wiki/'));
        $this->load->view('wiki/index', array('entries' => $this->wiki->get_latest(20)));
        $this->load->view('footer');
    }
    
    function item($item) {
        $item = $this->_get_item();
        list($subnav, $subnav_current) = $this->_get_subnav($item);
        
        $content = $this->wiki->get($item);
        if (! $content) {
            appendErrorMessage(sprintf(__('Page &ldquo;%s&rdquo; does not exist yet.'), h($item)));
            redirect('wiki/Edit:'.$item);
        }
        $this->load->view('header');
        $this->load->view('put', array('data' => '<h2>'.h($item).'</h2><div class="wiki_page">'.$content.'</div>'));
        $this->load->view('footer');
    }
    
    function dispatch($method, $item) {
        if (substr($method, 0, 1) != '_' && method_exists($this, strtolower($method))) {
            $method = strtolower($method);
            $this->isDispatched = True;
            $this->$method($item);
        } else {
            $this->item($method.':'.$item);
        }
    }
    
    function edit($item, $discussion=False) {
        $item = $this->_get_item();
        restrict_to_users(__('You must be logged in to edit the wiki.'), '/wiki/'.$item);
        list($subnav, $subnav_current) = $this->_get_subnav($item, $discussion? 'Edit_Discussion' : 'edit');
        if ($discussion) {
            $this->load->view('header');
            $this->load->view('put', array('data' => 'wiki edit discussion '.$item));
            $this->load->view('footer');
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('content', __('Content'), 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('header');
                $this->load->view('wiki/edit', array('original_content' => $this->wiki->get($item, True)));
                $this->load->view('footer');
            } else {
                $this->wiki->set($item);
                redirect('wiki/'.$item);
            }
        }
    }

    function edit_discussion($item) {
        $this->edit($item, True);
    }

    function discussion($item) {
        $item = $this->_get_item();
        list($subnav, $subnav_current) = $this->_get_subnav($item, 'discussion');

        $content = $this->wiki->get('Discussion:'.$item);
        if (! $content) {
            appendErrorMessage(sprintf(__('Discussion for &ldquo;%s&rdquo; does not exist yet.'), h($item)));
            redirect('wiki/Edit_Discussion:'.$item);
        }
        $this->load->view('header');
        $this->load->view('put', array('data' => '<h2>'.sprintf('Discussion: %s', h($item)).'</h2><div class="wiki_page wiki_discussion">'.$content.'</div>'));
        $this->load->view('footer');
    }

    function history($item) {
        $item = $this->_get_item();
        list($subnav, $subnav_current) = $this->_get_subnav($item, 'history');
        $this->load->view('header');
        $this->load->view('put', array('data' => 'wiki history '.$item));
        $this->load->view('footer');
    }
    
    protected function _get_subnav($item, $method='') {
        $method = $method? ucfirst($method).':' : '';
        $subnav = array(
            '/wiki/'.h($item) => __('View'),
            '/wiki/Discussion:'.h($item) => __('Discussion'),
            '/wiki/Edit:'.h($item) => __('Edit'),
            '/wiki/History:'.h($item) => __('History'),
        );
        if ($method == 'Edit_discussion:') { $method = 'Discussion:'; }
        $subnav_current = '/wiki/'.$method.h($item);
        $this->load->vars(array('subnav' => $subnav, 'subnav_current' => $subnav_current));
        return array($subnav, $subnav_current);
    }
    
    protected function _get_item() {
        $segments = $this->uri->rsegment_array();
        array_shift($segments);
        array_shift($segments);
        if ($this->isDispatched) {
            array_shift($segments);
        }
        $item = implode('/',$segments);
        $this->load->vars(array('item' => $item));
        $this->load->vars(array('title' => sprintf(__('Wiki » %s'), $item)));
        return $item;
    }

}

