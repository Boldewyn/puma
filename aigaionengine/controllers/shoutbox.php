<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Shoutbox extends Controller {

    public function Shoutbox() {
        parent::Controller();
        restrict_to_users();
        $this->output->set_header('Content-Type: text/javascript');
    }

    public function index() {
        $this->get(0);
    }

    public function get($offset=0, $count=5) {
        $offset = (int)$offset;
        $count = (int)$count;
        $userlogin = getUserLogin();
        $Q = $this->db->order_by('created', 'desc')
            ->where('at', NULL)->or_where('at', $userlogin->userId())
            ->get('shoutbox', $offset, $offset+$count);
        $this->output->set_output(json_encode(array('items'=>$Q->result())));
    }

    public function pull($after=1) {
        $userlogin = getUserLogin();
        $Q = $this->db->order_by('created', 'desc')
            ->where('id >', $after)
            ->where('at', NULL)->or_where('at', $userlogin->userId())
            ->get('shoutbox');
        $this->output->set_output(json_encode(array('items'=>$Q->result())));
    }

    public function create() {
        $userlogin = getUserLogin();
        $error = "";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('content', __('Content'), 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->output->set_output('{"result":false,"message":"'.$error.'"}');
        } else {
            $content = $this->input->post('shoutbox_content');
            $at = NULL;
            if ($content[0] == "@") {
                $abbr = preg_replace('/^@(.+?)(:|\s).*$/', '$1', $content);
                $at_user = $this->user_db->getByAbbreviation($abbr);
                if ($at_user) {
                    $at = $at_user->user_id;
                }
            }
            $this->db->insert('shoutbox', array(
                'user_id' => $userlogin->userId(),
                'at' => $at,
                'content' => $content
            ));
            $this->output->set_output('{"result":true}');
        }
    }

}


//__END__
