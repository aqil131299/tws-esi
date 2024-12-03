<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Registrasi extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        // Set CORS headers
        header('Access-Control-Allow-Origin: *');  // * allows all domains, you can replace * with a specific domain
        header('Access-Control-Allow-Headers: Content-Type, X-Requested-With, X-API-KEY, Authorization');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('HTTP/1.1 200 OK');
            exit();
        }

        // Load the user model
        $this->load->model('User');
    }

    public function index_post()
    {
        $nama = strip_tags($this->post('nama'));
        $profesi = strip_tags($this->post('profesi'));
        $email = strip_tags($this->post('email'));
        $password = $this->post('password');

        // Validate the input data
        if (!empty($nama) && !empty($profesi) && !empty($email) && !empty($password)) {

            // Check if the email already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array('email' => $email);
            $userCount = $this->user->getRows($con);

            if ($userCount > 0) {
                $this->response(["message" => "The given email already exists."], REST_Controller::HTTP_BAD_REQUEST);
            } else {
                // Insert new user data
                $userData = array(
                    'nama' => $nama,
                    'profesi' => $profesi,
                    'email' => $email,
                    'password' => md5($password),  // Use a stronger hashing algorithm in production (e.g. bcrypt)
                );
                $insert = $this->user->insert($userData);

                if ($insert) {
                    $this->response([
                        'is_active' => TRUE,
                        'message' => 'The user has been added successfully.',
                        'data' => $insert
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response(["message" => "Something went wrong, please try again."], REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            $this->response(["message" => "Please provide complete user information."], REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
