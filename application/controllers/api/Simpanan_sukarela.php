<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Simpanan_sukarela extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Authorization_Token');
    $this->load->model('Simpanan_sukarela_model');
  }

  public function index_get()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id = $this->get('id_sukarela');
      if ($id === null) {
        $simSukarela = $this->Simpanan_sukarela_model->getSimpananSukarela();
      } else { // Jika terdapat parameter ID
        $simSukarela = $this->Simpanan_sukarela_model->getSimpananSukarela($id);
        if (empty($simSukarela)) {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
          return;
        }
      }
      $this->response([
        'status'  => true,
        'data'    => $simSukarela
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
        'anggota_id'        => $this->post('anggota_id'),
        'nominal_sukarela'  => $this->post('nominal_sukarela'),
        'tgl_simpan'        => date('Y-m-d H:i:s')
      ];
      if ($this->Simpanan_sukarela_model->createSimpananSukarela($data) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Simpanan Sukarela telah ditambahkan'
        ], REST_Controller::HTTP_CREATED);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Simpanan Sukarela gagal dibuat'
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
      $id             = $this->put('id_sukarela');
      $data = [
        'anggota_id'        => $this->put('anggota_id'),
        'nominal_sukarela'  => $this->put('nominal_sukarela'),
        'tgl_simpan'        => date('Y-m-d H:i:s')
      ];
      if ($this->Simpanan_sukarela_model->updateSimpananSukarela($data, $id) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Simpanan Sukarela berhasil diubah'
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Simpanan Sukarela gagal diubah'
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

      $id = $this->delete('id_sukarela');
      if ($id === null) {
        $this->response([
          'status'  => false,
          'message' => 'tentukan id!'
        ], REST_Controller::HTTP_BAD_REQUEST);
      } else {
        if ($this->Simpanan_sukarela_model->deleteSimpananSukarela($id) > 0) {
          $this->response([
            'status'  => true,
            'id'      => $id,
            'message' => 'data Simpanan Sukarela dihapus'
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
