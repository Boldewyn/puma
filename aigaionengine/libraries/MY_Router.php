<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom error handling router
 * @source http://codeigniter.com/forums/viewthread/90613/P0/
 */
class MY_Router extends CI_Router {

    /**
     * Validates the supplied segments.  Attempts to determine the path to
     * the controller. This is an extension so we can support 404 handlers
     *
     * @access    private
     * @param    array
     * @return    array
     */    
    function _validate_request($segments) {
        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {        
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);
            
            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                {
                    return $this->_custom_404();    
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');
            
                // Does the default controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }
            
            }
                
            return $segments;
        }
    
        // Can't find the requested controller...
        return $this->_custom_404();    
    }

    function _custom_404() {
        $errorconfig = $this->config->item('error');

        if ($errorconfig) {

            $path = APPPATH . 'controllers/' . $errorconfig['directory'] . '/' . $errorconfig['controller'] . EXT;
            if (file_exists($path)) {
                $this->set_directory($errorconfig['directory']);
                $this->set_class($errorconfig['controller']);
                $this->set_method($errorconfig['method']);
            } else {
                show_404();
            }
        } else {
            show_404();
        }

        return array();
    }
}

//__END__