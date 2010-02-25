<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Wiki extends Controller {

    
    public function Wiki() {
        parent::Controller();
        $this->isDispatched = False;
        $this->load->model('wiki_model', 'wiki');
        $this->load->vars(array('body_id' => 'wiki'));
    }

    /** Wiki */
    public function index() {
        $this->load->view('header', array(
            'title' => __('Wiki'),
            'subnav'=>array('/wiki/'=>__('Main Page'), '/wiki/Special:All_Pages'=>__('All Pages')),
            'subnav_current'=>'/wiki/'));
        $this->load->view('wiki/index', array('entries' => $this->wiki->get_latest(20)));
        $this->load->view('footer');
    }
    
    public function item($item) {
        $item = $this->_get_item();
        $this->_get_subnav($item);
        
        $content = $this->wiki->get($item);
        if (! $content) {
            appendErrorMessage(sprintf(__('Page &ldquo;%s&rdquo; does not exist yet.'), h($item)));
            redirect('wiki/Edit:'.$item);
        }
        $this->load->view('header');
        $this->load->view('put', array('data' => '<h2>'.h($item).'</h2><div class="wiki_page">'.$content.'</div>'));
        $this->load->view('footer');
    }
    
    public function dispatch($method, $item) {
        if (substr($method, 0, 1) != '_' && method_exists($this, strtolower($method))) {
            $method = strtolower($method);
            $this->isDispatched = True;
            $this->$method($item);
        } else {
            $this->item($method.':'.$item);
        }
    }
    
    public function edit($item, $discussion=False) {
        $item = $this->_get_item();
        restrict_to_users(__('You must be logged in to edit the wiki.'), '/wiki/'.$item);
        $this->_get_subnav($item, $discussion? 'Edit_Discussion' : 'edit');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('content', __('Content'), 'required');
        if ($discussion) {
            $current_content = $this->wiki->get('Discussion:'.$item, True);
        } else {
            $current_content = $this->wiki->get($item, True);
        }
        $notedited = False;
        if ($this->input->post('content') == $current_content && count($current_content) > 0) {
            $notedited = True;
        }
        if ($this->form_validation->run() == FALSE || $this->input->post('preview') || $notedited) {
            if ($this->input->post('content')) {
                $content = $this->input->post('content');
            } else {
                $content = $current_content;
            }
            $this->load->view('header');
            $this->load->view('wiki/edit', array('original_content' => $content,
                                                 'preview' => $this->input->post('preview')? $this->wiki->preview($this->input->post('content')) : '',
                                                 'description' => $this->input->post('description'),
                                                 'discussion' => $discussion));
            $this->load->view('footer');
        } else {
            if ($discussion) {
                $item = 'Discussion:'.$item;
            }
            if ($this->wiki->set($item, $this->input->post('content'),
                                 $this->input->post('description')) === False) {
                appendErrorMessage(__('Could not edit the wiki page. Do you have edit rights?'));
            }
            redirect('wiki/'.$item);
        }
    }

    public function edit_discussion($item) {
        $this->edit($item, True);
    }

    public function discussion($item) {
        $item = $this->_get_item();
        $this->_get_subnav($item, 'discussion');

        $content = $this->wiki->get('Discussion:'.$item);
        if (! $content) {
            appendErrorMessage(sprintf(__('Discussion for &ldquo;%s&rdquo; does not exist yet.'), h($item)));
            redirect('wiki/Edit_Discussion:'.$item);
        }
        $this->load->view('header');
        $this->load->view('put', array('data' => 
            '<h2>'.sprintf(__('Discussion: &ldquo;%s&rdquo;'), h($item)).'</h2>'.
            '<div class="wiki_page wiki_discussion">'.$content.'</div>'));
        $this->load->view('footer');
    }

    public function history($item) {
        $item = $this->_get_item();
        restrict_to_users(__('You must be logged in to view the history of wiki items.'));
        $this->_get_subnav($item, 'history');
        $data = $this->wiki->get_history($item);
        $this->load->view('header');
        $this->load->view('wiki/history', array('data' => $data));
        $this->load->view('footer');
    }
    
    public function show_history($id) {
        restrict_to_users(__('You must be logged in to view the history of wiki items.'));
        $data = $this->wiki->get_version($id);
        $item = $data->item;
        $this->load->vars(array('item' => $item));
        $this->load->vars(array('title' => sprintf(__('Wiki » %s'), $item)));
        $this->_get_subnav($item, 'history');
        $this->load->view('header');
        $this->load->view('put', array('data' => 
            '<h2>'.sprintf(__('Old Version: &ldquo;%s&rdquo;'), h($item)).'</h2>'.
            '<p class="info">'.sprintf(__('This is an old version of &ldquo;%s&rdquo;. Creation date: %s.'), anchor('wiki/'.$data->item, $data->item), '<em>'.$data->created.'</em>').'</p>'.
            '<div class="wiki_page wiki_discussion">'.$data->content.'</div>'));
        $this->load->view('footer');
    }
    
    public function special($id) {
        restrict_to_users(__('Special pages are viewable for logged in users only.'));
        $this->load->vars(array('title' => __('Wiki » Special page')));
        $this->load->vars(array(
            'subnav' => array('/wiki/'=>__('Main Page'), 'wiki/Special:'.h($id)=>__('Special page')),
            'subnav_current' => 'wiki/Special:'.h($id)));
        switch (strtolower($id)) {
            case 'all_pages':
                $data = '<ul>';
                foreach ($this->wiki->get_all() as $page) {
                    $data .= '<li>'.anchor('wiki/'.h($page), h($page)).'</li>';
                }
                $data .= '</ul>';
                break;
            default:
                $data = '';
        }
        $this->load->view('header');
        $this->load->view('put', array('data' => 
            '<h2>'.sprintf(__('Special Page: %s'), h($id)).'</h2>'.
            '<div class="wiki_page wiki_special">'.$data.'</div>'));
        $this->load->view('footer');
    }
    
    protected function _get_subnav($item, $method='') {
        $method = $method? ucfirst($method).':' : '';
        $d = (strpos($method, 'iscussion') !== FALSE? '_Discussion': '');
        $subnav = array(
            '/wiki/'.h($item) => __('View'),
            '/wiki/Discussion:'.h($item) => __('Discussion'),
            '/wiki/Edit'.$d.':'.h($item) => __('Edit'),
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
    
    protected function _item_exists($item) {
        return ($this->wiki->get($item) !== False);
    }

}

//__END__