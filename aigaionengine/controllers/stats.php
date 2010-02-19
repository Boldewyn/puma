<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends Controller {

    function Stats() {
        parent::Controller();
        $this->load->library('statistics');
        $this->data = $this->statistics->get();
    }

    /** Statistics */
    function index() {
        $html = '<table><thead><tr><th>User</th><th>with</th><th>speaking</th>'.
                '<th>wants</th><th>at</th></tr></thead><tbody>';
        foreach ($this->data as $d) {
            $html .= sprintf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
                h($d['user']), h($d['with']), h($d['speaking']), h($d['wants']), h($d['at'])
            );
        }
        $html .= '</tbody></table>';
        $this->load->view('header', array('title' => __('Statistics')));
        $this->load->view('put', array('data' => $html));
        $this->load->view('footer');
    }
    
    function csv() {
        $csv = '';
        foreach ($this->data as $d) {
            $csv .= sprintf('%s,%s,%s,%s,%s'."\n",
                h($d['user']), h($d['with']), h($d['speaking']), h($d['wants']), h($d['at'])
            );
        }
        header('Content-Type: text/csv');
        $this->load->view('put', array('data' => $csv));
    }

}

