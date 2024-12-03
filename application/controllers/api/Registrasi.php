<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registrasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->model('User_model');

        // Add CORS headers to allow access from your Flutter app's origin
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Handle preflight requests (OPTIONS)
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
    }

    public function index() {
        $json_data = file_get_contents('php://input');
        $data = json_decode($json_data, true);

        if ($data) {
            $this->form_validation->set_data($data);
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('profesi', 'Profesi', 'required');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

            if ($this->form_validation->run() === FALSE) {
                $validation_errors = validation_errors();
                $response = array(
                    'status' => 'error',
                    'message' => "Validasi gagal: $validation_errors"
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            $user_data = array(
                'nama' => $data['nama'],
                'profesi' => $data['profesi'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT)
            );

            $existing_user = $this->User_model->get_user_by_email($data['email']);
            if ($existing_user) {
                $response = array(
                    'status' => 'error',
                    'message' => 'Email sudah terdaftar. Silakan gunakan email lain.'
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
                return;
            }

            $result = $this->User_model->register_user($user_data);

            if ($result) {
                $response = array(
                    'status' => 'success',
                    'message' => 'Registrasi berhasil!'
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            } else {
                $response = array(
                    'status' => 'error',
                    'message' => 'Registrasi gagal, coba lagi nanti.'
                );
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($response));
            }

        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Data tidak valid atau tidak lengkap.'
            );
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($response));
        }
    }
}

