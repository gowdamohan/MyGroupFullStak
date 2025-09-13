<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Authenticate user with email and password
     * 
     * @param string $email User email
     * @param string $password User password
     * @return array|boolean User data if authenticated, false otherwise
     */
    public function authenticate($email, $password) {
        // Get user by email
        $query = $this->db->select('id, email, password, first_name, last_name, active, group_id')
                          ->from('users')
                          ->where('email', $email)
                          ->limit(1)
                          ->get();
        
        if ($query->num_rows() === 1) {
            $user = $query->row_array();
            
            // Verify password (assuming passwords are hashed with password_hash)
            if (password_verify($password, $user['password'])) {
                // Get user groups
                $groups_query = $this->db->select('g.id, g.name, g.description')
                                        ->from('users_groups ug')
                                        ->join('groups g', 'ug.group_id = g.id')
                                        ->where('ug.user_id', $user['id'])
                                        ->get();
                
                $user['groups'] = $groups_query->result_array();
                
                // Return user data without password
                unset($user['password']);
                return $user;
            }
        }
        
        return false;
    }
    
    /**
     * Get user by ID
     * 
     * @param int $user_id User ID
     * @return array|null User data or null if not found
     */
    public function get_user_by_id($user_id) {
        $query = $this->db->select('id, email, first_name, last_name, active, group_id, phone, company')
                          ->from('users')
                          ->where('id', $user_id)
                          ->limit(1)
                          ->get();
        
        return ($query->num_rows() === 1) ? $query->row_array() : null;
    }
    
    /**
     * Get user by email
     * 
     * @param string $email User email
     * @return array|null User data or null if not found
     */
    public function get_user_by_email($email) {
        $query = $this->db->select('id, email, first_name, last_name, active, group_id, phone, company')
                          ->from('users')
                          ->where('email', $email)
                          ->limit(1)
                          ->get();
        
        return ($query->num_rows() === 1) ? $query->row_array() : null;
    }
    
    /**
     * Create a new user
     * 
     * @param array $user_data User data
     * @return int|boolean User ID if created, false otherwise
     */
    public function create_user($user_data) {
        // Hash password
        $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
        
        // Insert user
        $this->db->insert('users', $user_data);
        
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }
    
    /**
     * Update user
     * 
     * @param int $user_id User ID
     * @param array $user_data User data
     * @return boolean True if updated, false otherwise
     */
    public function update_user($user_id, $user_data) {
        // If password is being updated, hash it
        if (isset($user_data['password'])) {
            $user_data['password'] = password_hash($user_data['password'], PASSWORD_DEFAULT);
        }
        
        $this->db->where('id', $user_id);
        $this->db->update('users', $user_data);
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Delete user
     * 
     * @param int $user_id User ID
     * @return boolean True if deleted, false otherwise
     */
    public function delete_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->delete('users');
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Get all user groups
     * 
     * @return array Array of groups
     */
    public function get_groups() {
        return $this->db->get('groups')->result_array();
    }
    
    /**
     * Add user to group
     * 
     * @param int $user_id User ID
     * @param int $group_id Group ID
     * @return boolean True if added, false otherwise
     */
    public function add_to_group($user_id, $group_id) {
        $data = array(
            'user_id' => $user_id,
            'group_id' => $group_id
        );
        
        $this->db->insert('users_groups', $data);
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Remove user from group
     * 
     * @param int $user_id User ID
     * @param int $group_id Group ID
     * @return boolean True if removed, false otherwise
     */
    public function remove_from_group($user_id, $group_id) {
        $this->db->where('user_id', $user_id);
        $this->db->where('group_id', $group_id);
        $this->db->delete('users_groups');
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Check if user is in group
     * 
     * @param int $user_id User ID
     * @param int|string $group Group ID or name
     * @return boolean True if in group, false otherwise
     */
    public function is_in_group($user_id, $group) {
        if (is_numeric($group)) {
            $this->db->where('group_id', $group);
        } else {
            $this->db->join('groups g', 'ug.group_id = g.id');
            $this->db->where('g.name', $group);
        }
        
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('users_groups ug');
        
        return ($query->num_rows() > 0);
    }
    
    /**
     * Update last login time
     * 
     * @param int $user_id User ID
     * @return boolean True if updated, false otherwise
     */
    public function update_last_login($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('last_login' => date('Y-m-d H:i:s')));
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Reset password
     * 
     * @param string $email User email
     * @param string $new_password New password
     * @return boolean True if reset, false otherwise
     */
    public function reset_password($email, $new_password) {
        $this->db->where('email', $email);
        $this->db->update('users', array(
            'password' => password_hash($new_password, PASSWORD_DEFAULT),
            'remember_code' => null
        ));
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Activate user
     * 
     * @param int $user_id User ID
     * @return boolean True if activated, false otherwise
     */
    public function activate_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('active' => 1));
        
        return ($this->db->affected_rows() > 0);
    }
    
    /**
     * Deactivate user
     * 
     * @param int $user_id User ID
     * @return boolean True if deactivated, false otherwise
     */
    public function deactivate_user($user_id) {
        $this->db->where('id', $user_id);
        $this->db->update('users', array('active' => 0));
        
        return ($this->db->affected_rows() > 0);
    }
}
