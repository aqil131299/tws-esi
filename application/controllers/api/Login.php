<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        // Set headers untuk CORS
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type");
    }

    // Fungsi login
    public function index() {
        $json_data = file_get_contents('php://input');  // Mendapatkan data JSON dari request
        $data = json_decode($json_data, true);  // Mengubah JSON menjadi array

        // Validasi input email dan password
        if (isset($data['email']) && isset($data['password'])) {
            $email = $data['email'];
            $password = $data['password'];

            // Cek login ke database
            $user = $this->User_model->validate_login($email, $password);

            if ($user) {
                // Jika berhasil login
                $response = array(
                    'status' => 'success',
                    'message' => 'Login berhasil!',
                    'data' => $user
                );
            } else {
                // Jika gagal login
                $response = array(
                    'status' => 'error',
                    'message' => 'Email atau password salah.'
                );
            }
            // Kirimkan response dalam format JSON
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        } else {
            // Jika data tidak lengkap
            $response = array(
                'status' => 'error',
                'message' => 'Data tidak lengkap.'
            );
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        }
    }
}
