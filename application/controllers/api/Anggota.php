<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Anggota extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('Authorization_Token');
    $this->load->model('Anggota_model');
  }

  public function index_get()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
      $id = $this->get('id_anggota');
      if ($id === null) {
        $anggota = $this->Anggota_model->getAnggota();
      } else {
        $anggota = $this->Anggota_model->getAnggota($id);
        if (empty($anggota)) {
          $this->response([
            'status'  => false,
            'message' => 'id tidak ditemukan'
          ], REST_Controller::HTTP_BAD_REQUEST);
          return;
        }
      }
      $this->response([
        'status'  => true,
        'data'    => $anggota
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

      $_POST = $this->security->xss_clean($_POST);

      $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
      $this->form_validation->set_rules('email', 'Email', 'trim|required');
      $this->form_validation->set_rules('password', 'Password', 'trim|required');
      $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
      $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'trim|required');
      $this->form_validation->set_rules('no_telp', 'No Telpon', 'trim|required');
      $this->form_validation->set_rules('no_ktp', 'No KTP', 'trim|required');

      if ($this->form_validation->run() == FALSE) {
        $message = array(
          'status'  => false,
          'error'   => $this->form_validation->error_array(),
          'message' => validation_errors()
        );

        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
      } else {
        date_default_timezone_set('Asia/Jakarta');

        $this->load->model('Anggota_model');
        $data = [
          'nama'         => $this->input->post('nama', TRUE),
          'email'        => $this->input->post('email', TRUE),
          'password'     => $this->input->post('password', TRUE),
          'alamat'       => $this->input->post('alamat', TRUE),
          'jk'           => $this->input->post('jk', TRUE),
          'no_telp'      => $this->input->post('no_telp', TRUE),
          'no_ktp'       => $this->input->post('no_ktp', TRUE),
          'status'       => 'Anggota',
          'role_id'      => '2',
          'date_created' => date('Y-m-d H:i:s')
        ];

        $output = $this->Anggota_model->createAnggota($data);

        if ($output > 0 and !empty($output)) {
          // Success
          $message = [
            'status' => true,
            'message' => "Anggota Ditambahkan"
          ];
          $this->response($message, REST_Controller::HTTP_OK);
        } else {
          // Error
          $message = [
            'status' => FALSE,
            'message' => "Anggota Gagal Ditambahkan"
          ];
          $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
      }
    } else {
      $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_put() //1 mulai
  { //1 mulai
    header("Access-Control-Allow-Origin: *"); //1 mulai
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization"); //1 mulai

    $is_valid_token = $this->authorization_token->validateToken(); //2
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) { //3

      $id   = $this->put('id_anggota'); //4
      $data = [
        'nama'         => $this->put('nama'), //5
        'email'        => $this->put('email'), //5
        'password'     => $this->put('password'), //5
        'alamat'       => $this->put('alamat'), //5
        'jk'           => $this->put('jk'), //5
        'no_telp'      => $this->put('no_telp'), //5
        'no_ktp'       => $this->put('no_ktp'), //5
      ];

      if ($this->Anggota_model->updateAnggota($data, $id) > 0) { //6
        $this->response([
          'status'  => true, //7
          'message' => 'data anggota berhasil diubah' //7
        ], REST_Controller::HTTP_OK); //7
      } else { //8
        $this->response([
          'status'  => false, //8
          'message' => 'data Anggota gagal diubah' //8
        ], REST_Controller::HTTP_BAD_REQUEST); //8
      }
    } else { //9
      $this->response([
        'status' => FALSE, //9
        'message' => $is_valid_token['message'] //9
      ], REST_Controller::HTTP_NOT_FOUND); //9
    }
  } //10 selesai

  public function index_delete()
  {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

    $is_valid_token = $this->authorization_token->validateToken();
    if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {

      $id = $this->delete('id_anggota');
      if ($id === null) {
        $this->response([
          'status'  => false,
          'message' => 'tentukan id!'
        ], REST_Controller::HTTP_BAD_REQUEST);
      } else {
        if ($this->Anggota_model->deleteAnggota($id) > 0) {
          $this->response([
            'status'  => true,
            'id'      => $id,
            'message' => 'data anggota dihapus'
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
