<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Model
{
    protected $userTbl = 'user';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Function to insert user data
    public function insert($data)
    {
        if (!array_key_exists("tanggal_input", $data)) {
            $data['tanggal_input'] = date("Y-m-d H:i:s");
        }
        if (!array_key_exists("role_id", $data)) {
            $data['role_id'] = 2;  // Default role
        }
        if (!array_key_exists("is_active", $data)) {
            $data['is_active'] = 1;  // Active by default
        }

        $insert = $this->db->insert($this->userTbl, $data);
        return $insert ? $this->db->insert_id() : false;
    }

    // Function to get rows from the users table
    public function getRows($params = array())
    {
        $this->db->select('*');
        $this->db->from($this->userTbl);

        if (array_key_exists("conditions", $params)) {
            foreach ($params['conditions'] as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if (array_key_exists("iduser", $params)) {
            $this->db->where('iduser', $params['iduser']);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit'], $params['start']);
            } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                $this->db->limit($params['limit']);
            }
            $query = $this->db->get();
            return ($query->num_rows() > 0) ? $query->result_array() : false;
        }
    }
}
