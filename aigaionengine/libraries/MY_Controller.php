<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom error handling controller
 * @source http://codeigniter.com/forums/viewthread/90613/P0/
 */
class MY_Controller extends Controller {

    /**
     * Constructor
     */
    public function __construct() {
        parent::Controller();
    }

    /**
     * @param   string          $method the method CI would usually call
     */
    public function _remap($method) {
        global $URI;

        if (method_exists($this, $method)) {
            call_user_func_array(array(&$this, $method), array_slice($URI->rsegments, 2));
        } else {
            $this->_handle_404();
        }
    }

    /**
     * Handle 404 using a custom controller. Will call default show_404() when it cannot resolve to a valid method.
     */
    protected function _handle_404() {
        $errorconfig = $this->config->item('error');

        if (!$errorconfig) {
            show_404();
        }

        $path = APPPATH . 'controllers/' . $errorconfig['directory'] . '/' . $errorconfig['controller'] . EXT;
        if (!file_exists($path)) {
            show_404();
        }

        require_once $path;
        if (!class_exists($errorconfig['controller'])) {
            show_404();
        }

        $class = new $errorconfig['controller'];
        if (!method_exists($class, $errorconfig['method'])) {
            show_404();
        }

        call_user_func(array(&$class, $errorconfig['method']));
    }
}

//__END__
