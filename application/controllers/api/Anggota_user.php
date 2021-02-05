<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Anggota_user extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('AnggotaUser_model');
  }

  public function register_post()
  {
    header("Access-Control-Allow-Origin: *");
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
      $data = [
        'nama'          => $this->input->post('nama'),
        'email'         => $this->input->post('email'),
        'password'      => md5($this->input->post('password')),
        'alamat'        => $this->input->post('alamat'),
        'jk'            => $this->input->post('jk'),
        'no_telp'       => $this->input->post('no_telp'),
        'no_ktp'        => $this->input->post('no_ktp'),
        'status'        => 'Anggota',
        'role_id'       => '2',
        'date_created'  => date('Y-m-d H:i:s')
      ];

      if ($this->AnggotaUser_model->insert_user($data) > 0) {
        $this->response([
          'status'  => true,
          'message' => 'registrasi berhasil'

        ], REST_Controller::HTTP_CREATED);
      } else {
        $this->response([
          'status'  => false,
          'message' => 'registrasi gagal'
        ], REST_Controller::HTTP_BAD_REQUEST);
      }
    }
  }

  public function login_post()
  {
    header("Access-Control-Allow-Origin: *");

    $_POST = $this->security->xss_clean($_POST);

    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|max_length[100]');

    if ($this->form_validation->run() == FALSE) {
      $message = array(
        'status'  => false,
        'error'   => $this->form_validation->error_array(),
        'message' => validation_errors()
      );

      $this->response($message, REST_Controller::HTTP_NOT_FOUND);
    } else {
      $user = $this->AnggotaUser_model->user_login($this->input->post('email'), $this->input->post('password'));

      if (!empty($user) and $user != FALSE) {
        $this->load->library('Authorization_Token');
        date_default_timezone_set('Asia/Jakarta');

        $payload['id']            = $user->id_anggota;
        $payload['nama']          = $user->nama;
        $payload['email']         = $user->email;
        $payload['alamat']        = $user->alamat;
        $payload['jk']            = $user->jk;
        $payload['no_telp']       = $user->no_telp;
        $payload['no_ktp']        = $user->no_ktp;
        $payload['status']        = $user->status;
        $payload['role_id']       = $user->role_id;
        $payload['date_created']  = date('Y-m-d H:i:s');
        $payload['time']          = time();

        $user_token = $this->authorization_token->generateToken($payload);

        $data = [
          'id'                  => $user->id_anggota,
          'nama'                => $user->nama,
          'email'               => $user->email,
          'status'              => $user->status,
          'role_id'             => $user->role_id,
          'date_created'        => $user->date_created,
          'created_at'          => time(),
          'token'               => $user_token,
        ];
        $this->response([
          'status'  => true,
          'data'    => $data,
          'message' => "Congratulation, Login success"
        ], REST_Controller::HTTP_OK);
      } else {
        $message = [
          'status'  => FALSE,
          'message' => "Email atau Password salah!"
        ];
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
      }
    }
  }
}
