<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motivasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Motivasi_model');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT");
        header("Access-Control-Allow-Headers: Content-Type");
    }

    // Menampilkan data motivasi
    // Menampilkan data motivasi
    public function get_motivasi() {
        $data = $this->Motivasi_model->get_motivasi();

        if (empty($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada data motivasi']);
        } else {
            // Pastikan untuk memformat data dalam bentuk yang benar
            $formatted_data = array_map(function($item) {
                return [
                    'id' => (string)$item['id'],  // Pastikan ID dikirim dalam format string
                    'isi_motivasi' => isset($item['isi_motivasi']) ? $item['isi_motivasi'] : '',  // Menghindari nilai null
                    'created_at' => isset($item['created_at']) ? $item['created_at'] : '',  // Pastikan 'created_at' ada dan tidak null
                ];
            }, $data);

            // Pastikan array yang dikirimkan ke json_encode tidak ada nilai null
            $response = [
                'status' => 'success',
                'data' => $formatted_data
            ];

            echo json_encode($response);
        }
    }

    
    

    // Menambahkan motivasi baru
    public function post_motivasi() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['isi_motivasi'])) {
            $result = $this->Motivasi_model->add_motivasi($data['isi_motivasi']);
            echo json_encode($result);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Isi motivasi tidak ditemukan']);
        }
    }

    // Menghapus motivasi berdasarkan ID
    public function delete_motivasi($id) {
        $result = $this->Motivasi_model->delete_motivasi($id);
        echo json_encode($result);
    }

    // Mengupdate motivasi
    public function update_motivasi($id) {
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['isi_motivasi'])) {
            $result = $this->Motivasi_model->update_motivasi($id, $data['isi_motivasi']);
            echo json_encode($result);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Isi motivasi tidak ditemukan']);
        }
    }
}
