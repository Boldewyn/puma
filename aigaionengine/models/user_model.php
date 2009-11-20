<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Model {

    protected $table = "users";
    
    protected $fields = array("user_id", "theme", "newwindowforatt", "summarystyle",
                              "authordisplaystyle", "liststyle", "login", "password",
                              "initials", "firstname", "betweenname", "surname", "csname",
                              "abbreviation", "email", "u_rights", "lastreviewedtopic",
                              "type", "lastupdatecheck", "exportinbrowser", "utf8bibtex",
                              "modified");

    /**
     *
     */
    public function __construct () {
        parent::Model();
    }
    
    
    /**
     * Get all data for a certain user
     */
    public function get($id) {
        return $this->get_field($id, "*", FALSE);
    }
    
    
    /**
     * Get a certain field from a user entry
     */
    public function get_field($id, $field, $switch=TRUE) {
        $query = $this->db->select($field, $switch)->from($this->table)->where('user_id =', $id)->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    
    
    /**
     * Create a new user
     */
    public function create($data) {
        if (! isset($data['login'])) {
            throw new Exception(__("Login data missing."));
        }
        if ($this->get_id_by_username($data['login'])) {
            throw new Exception(__("User already exists."));
        }
        $query = $this->db->insert($this->table, $data);
    }
    
    
    /**
     * Modify an existing user
     */
    public function modify($id, $data) {
        $query = $this->db->where('user_id', $id)->update($this->table, $data);
        return $query->num_rows();
    }
    
    
    /**
     * Delete a user
     */
    public function delete($id) {
        $query = $this->db->delete($this->mytable, array('user_id' => $id));
        return $query->num_rows();
    }
    
    
    /**
     * Update last modified status
     */
    public function touch($id) {
        $query = $this->db->set('modified', 'NOW()', FALSE)->where('user_id =', $id)->update($this->table);
        return $query->num_rows();
    }
    
    
    /**
     * Get the last modification as UNIX timestamp
     */
    public function get_modified($id) {
        return $this->get_field($id, "UNIX_TIMESTAMP(`modified`)", FALSE);
    }
    
    
    /**
     *
     */
    public function get_id_by_username($username) {
        $query = $this->db->select("user_id")->from($this->table)->where("login =", $username).get();
        if ($query->num_rows() > 0) {
            return $query->row()->user_id;
        } else {
            return null;
        }
    }
    
    
    /**
     * Authenticate a user against the password in the db
     */
    public function authenticate($username, $password) {
        $query = $this->db->from($this->table)->where('login =', $username)->where('password =', md5($password))->get();
        return ($query->num_rows() == 1);
    }
    
    
    /**
     * Change the password of a user
     */
    public function change_password($id, $password) {
        return $this->modify($id, array("password" => md5($password)));
    }
    
    
}


//__END__