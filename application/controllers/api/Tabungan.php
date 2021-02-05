<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Pinjaman extends REST_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->library('Authorization_Token');
    $this->load->model('Pinjaman_model');
  }

  public function index_get()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id = $this->get('id');
      if ($id === null) {
        $Pinjaman = $this->Pinjaman_model->getPinjaman();
      } else { // Jika terdapat parameter ID
        $Pinjaman = $this->Pinjaman_model->getPinjaman($id);
        if (empty($Pinjaman)) {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
          return;
        }
      }
      $this->response([
        'status'  => true,
        'data'    => $Pinjaman
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
      $post_data    = $this->post();
      $bungabln     = $post_data['jml_pinjam'] * ($post_data['bunga'] / 100);
      $pokok        = $post_data['jml_pinjam'] / $post_data['tempo'];
      $cicilan      = $bungabln + $pokok;
      $total_bayar  = $post_data['tempo'] * $cicilan;
      $data = [
        'anggota_id'  => $this->post('anggota_id'),
        'nama'        => $this->post('nama'),
        'jml_pinjam'  => $this->post('jml_pinjam'),
        'bunga'       => $this->post('bunga'),
        'tempo'       => $this->post('tempo'),
        'angsur_bln'  => $cicilan,
        'tgl_pinjam'  => date('Y-m-d H:i:s'),
        'tgl_bayar'   => $this->post('tgl_bayar'),
        'total_bayar' => $total_bayar

      ];
      if ($this->Pinjaman_model->createPinjaman($data) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Pinjaman baru telah ditambahkan'
        ], REST_Controller::HTTP_CREATED);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Pinjaman gagal dibuat'
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
      $id           = $this->put('id');
      $put_data     = $this->put();
      $bungabln     = $put_data['jml_pinjam'] * ($put_data['bunga'] / 100);
      $pokok        = $put_data['jml_pinjam'] / $put_data['tempo'];
      $cicilan      = $bungabln + $pokok;
      $total_bayar  = $put_data['tempo'] * $cicilan;
      $data = [
        'anggota_id'  => $this->put('anggota_id'),
        'nama'        => $this->post('nama'),
        'jml_pinjam'  => $this->put('jml_pinjam'),
        'bunga'       => $this->put('bunga'),
        'tempo'       => $this->put('tempo'),
        'angsur_bln'  => $cicilan,
        'tgl_pinjam'  => $this->put('tgl_pinjam'),
        'tgl_bayar'   => $this->put('tgl_bayar'),
        'total_bayar' => $total_bayar
      ];
      if ($this->Pinjaman_model->updatePinjaman($data, $id) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'data Pinjaman berhasil diubah'
        ], REST_Controller::HTTP_OK);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'data Pinjaman gagal diubah'
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
      $id = $this->delete('id');
      if ($id === null) {
        $this->response([
          'status'  => false,
          'message' => 'tentukan id!'
        ], REST_Controller::HTTP_BAD_REQUEST);
      } else {
        if ($this->Pinjaman_model->deletePinjaman($id) > 0) {
          $this->response([
            'status'  => true,
            'id'      => $id,
            'message' => 'data Pinjaman dihapus'
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
