<?php

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class registrasi extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        // Allow CORS from specific origins (replace localhost with your actual front-end URL in production)
        header('Access-Control-Allow-Origin: *');  // Or use 'http://localhost:50037' for production

        // Allow specific headers
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");

        // Allow specific methods
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Handle preflight request (OPTIONS)
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            // Send response for OPTIONS request and stop further processing
            die();
        }

        // Load user model
        $this->load->model('user');
    }

    public function index_post()
    {
        // Get post data
        $nama = strip_tags($this->post('nama'));
        $profesi = strip_tags($this->post('profesi'));
        $email = strip_tags($this->post('email'));
        $password = $this->post('password');

        // Validate input data
        if (!empty($nama) && !empty($profesi) && !empty($email) && !empty($password)) {
            // Check if email already exists
            $con['returnType'] = 'count';
            $con['conditions'] = array('email' => $email);
            $userCount = $this->user->getRows($con);

            if ($userCount > 0) {
                // Email exists, send error response
                $this->response("The given email already exists.", REST_Controller::HTTP_BAD_REQUEST);
            } else {
                // Insert user data
                $userData = array(
                    'nama' => $nama,
                    'profesi' => $profesi,
                    'email' => $email,
                    'password' => md5($password),
                );
                $insert = $this->user->insert($userData);

                if ($insert) {
                    // Success response
                    $this->response([
                        'is_active' => TRUE,
                        'message' => 'The user has been added successfully.',
                        'data' => $insert
                    ], REST_Controller::HTTP_OK);
                } else {
                    // Error response
                    $this->response("Some problems occurred, please try again.", REST_Controller::HTTP_BAD_REQUEST);
                }
            }
        } else {
            // Missing data, error response
            $this->response("Provide complete user info to add.", REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
