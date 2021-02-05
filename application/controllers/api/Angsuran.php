<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Angsuran extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Authorization_Token');
    $this->load->model('Angsuran_model');
  }

  public function index_get()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id = $this->get('id_angsur');
      if ($id === null) {
        $data = $this->Angsuran_model->getAngsur();
      } else {
        $data = $this->Angsuran_model->getAngsur($id);
        if (empty($data)) {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
          return;
        }
      }
      $this->response([
        'status'  => true,
        'data'    => $data
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
      $data = [
        'pinjam_id'   => $this->post('pinjam_id'),
        'nom_angsur'  => $this->post('nom_angsur'),
        'tgl_bayar'   => date('Y-m-d H:i:s')
      ];
      if ($this->Angsuran_model->createAngsuran($data) > 0) {
        $this->response([
          'status' => true,
          'message' => 'data angsuran baru telah ditambahkan'
        ], REST_Controller::HTTP_CREATED);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data angsuran gagal dibuat'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }
}
