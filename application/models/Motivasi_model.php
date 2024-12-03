<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Motivasi_model extends CI_Model {

    private $table = 'motivasi'; // Tabel yang menyimpan data motivasi

    // Ambil semua data motivasi
    // Ambil semua data motivasi
    public function get_motivasi() {
        $query = $this->db->get($this->table);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];  // Pastikan selalu mengembalikan array kosong jika tidak ada data
    }

    // Menambahkan motivasi baru
    public function add_motivasi($isi_motivasi) {
        if (empty($isi_motivasi)) {
            return ['status' => 'error', 'message' => 'Isi motivasi tidak boleh kosong'];
        }
        $data = array('isi_motivasi' => $isi_motivasi);
        $this->db->insert($this->table, $data);
        return ['status' => 'success', 'message' => 'Motivasi berhasil ditambahkan'];
    }


    // Menghapus motivasi berdasarkan ID
    public function delete_motivasi($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return ['status' => 'success', 'message' => 'Motivasi berhasil dihapus'];
    }

    // Mengupdate motivasi berdasarkan ID
    public function update_motivasi($id, $isi_motivasi) {
        if (empty($isi_motivasi)) {
            return ['status' => 'error', 'message' => 'Isi motivasi tidak boleh kosong'];
        }
        $data = array('isi_motivasi' => $isi_motivasi);
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
        return ['status' => 'success', 'message' => 'Motivasi berhasil diperbarui'];
    }
}
