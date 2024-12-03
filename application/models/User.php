<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model
{
    // Explicitly declare the $userTbl property
    protected $userTbl = 'user';

    public function __construct()
    {
        parent::__construct();

        // Load the database library
        $this->load->database();
    }

    /*
     * Get rows from the users table
     */
    public function getRows($params = array())
{
    $this->db->select('*');
    $this->db->from($this->userTbl);

    // Fetch data by conditions
    if (array_key_exists("conditions", $params)) {
        foreach ($params['conditions'] as $key => $value) {
            $this->db->where($key, $value);
        }
    }

    if (array_key_exists("returnType", $params)) {
        if ($params['returnType'] == 'all') {
            // Return all users
            $query = $this->db->get();
            $result = $query->result_array();  // Return all results as an array
        } elseif ($params['returnType'] == 'count') {
            // Return count of rows
            $result = $this->db->count_all_results();
        } elseif ($params['returnType'] == 'single') {
            // Return a single result
            $query = $this->db->get();
            $result = ($query->num_rows() > 0) ? $query->row_array() : false;
        }
    } else {
        // Default behavior (return all rows if no returnType specified)
        $query = $this->db->get();
        $result = ($query->num_rows() > 0) ? $query->result_array() : false;
    }

    return $result;
}


    /*
     * Insert user data
     */
    public function insert($data)
    {
        // Add created and modified date if not exists
        if (!array_key_exists("tanggal_input", $data)) {
            $data['tanggal_input'] = date("Y-m-d H:i:s");
        }
        if (!array_key_exists("role_id", $data)) {
            $data['role_id'] = 2;
        }

        if (!array_key_exists("is_active", $data)) {
            $data['is_active'] = 1;
        }

        // Insert user data into the users table
        $insert = $this->db->insert($this->userTbl, $data);

        // Return the status
        return $insert ? $this->db->insert_id() : false;
    }

    /*
     * Update user data
     */
    public function update($data, $id)
    {
        // Add modified date if not exists
        if (!array_key_exists('modified', $data)) {
            $data['modified'] = date("Y-m-d H:i:s");
        }

        // Update user data in users table
        $update = $this->db->update($this->userTbl, $data, array('iduser' => $id));

        // Return the status
        return $update ? true : false;
    }

    /*
     * Delete user data
     */
    public function delete($id)
    {
        // Delete user from the users table
        $delete = $this->db->delete($this->userTbl, array('iduser' => $id));

        // Return the status
        return $delete ? true : false;
    }
}
