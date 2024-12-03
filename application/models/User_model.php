<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function register_user($data) {
        // Menyimpan data ke dalam tabel 'users'
        return $this->db->insert('users', $data);
    }

    public function get_user_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get('users');
        return $query->row();
    }
    public function validate_login($email, $password) {
        // Mengambil user berdasarkan email
        $this->db->where('email', $email);
        $query = $this->db->get('users');  // Asumsi tabel users sudah ada
        
        // Jika user ditemukan
        if ($query->num_rows() == 1) {
            $user = $query->row();

            // Verifikasi password
            if (password_verify($password, $user->password)) {
                return array(
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                );
            }
        }
        return false;  // Jika email atau password salah
    }
}
