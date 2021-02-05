<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Simpanan_wajib extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Authorization_Token');
    $this->load->model('Simpanan_wajib_model');
  }

  public function index_get()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id = $this->get('id');
      if ($id === null) {
        $simwajib = $this->Simpanan_wajib_model->getSimpananWajib();
      } else { // Jika terdapat parameter ID
        $simwajib = $this->Simpanan_wajib_model->getSimpananWajib($id);
        if (empty($simwajib)) {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
          return;
        }
      }
      $this->response([
        'status'  => true,
        'data'    => $simwajib
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_post()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      date_default_timezone_set('Asia/Jakarta');

      $_POST        = $this->security->xss_clean($_POST);
      $data = [
        'anggota_id'    => $this->post('anggota_id'),
        'nominal_wajib' => $this->post('nominal_wajib'),
        'tgl_simpan'    => date('Y-m-d H:i:s')
      ];
      if ($this->Simpanan_wajib_model->createSimpananWajib($data) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Simpanan Wajib telah ditambahkan'
        ], REST_Controller::HTTP_CREATED);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Simpanan Wajib gagal dibuat'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_put()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id             = $this->put('id_sim_wajib');
      $data = [
        'anggota_id'    => $this->put('anggota_id'),
        'nominal_wajib' => $this->put('nominal_wajib'),
        'tgl_simpan'    => date('Y-m-d H:i:s')
      ];
      if ($this->Simpanan_wajib_model->updateSimpananWajib($data, $id) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Simpanan wajib berhasil diubah'
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Simpanan wajib gagal diubah'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }
  public function index_delete()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {

      $id = $this->delete('id_sim_wajib');
      if ($id === null) {
        $this->response([
          'status'  => false,
          'message' => 'tentukan id!'
        ], REST_Controller::HTTP_BAD_REQUEST);
      } else {
        if ($this->Simpanan_wajib_model->deleteSimpananWajib($id) > 0) {
          $this->response([
            'status'  => true,
            'id'      => $id,
            'message' => 'data Simpanan wajib dihapus'
          ], REST_Controller::HTTP_OK);
        } else {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
        }
      }
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
