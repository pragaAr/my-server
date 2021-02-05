<?php defined('BASEPATH') or exit('No direct script access allowed');

use Restserver\Libraries\REST_Controller;

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class User extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('User_model', 'UserModel');
  }

  public function register_post()
  {
    header("Access-Control-Allow-Origin: *");
    $_POST = $this->security->xss_clean($_POST);

    $this->form_validation->set_rules('nama', 'Nama Lengkap', 'trim|required');
    $this->form_validation->set_rules('alamat', 'Alamat', 'trim|required');
    $this->form_validation->set_rules('jk', 'Jenis Kelamin', 'trim|required');
    $this->form_validation->set_rules('telpon', 'No Telpon', 'trim|required');
    $this->form_validation->set_rules('email', 'Email', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

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
        'alamat'        => $this->input->post('alamat'),
        'jk'            => $this->input->post('jk'),
        'telpon'        => $this->input->post('telpon'),
        'email'         => $this->input->post('email'),
        'password'      => md5($this->input->post('password')),
        'image'         => '-',
        'role_id'       => '2',
        'is_active'     => '1',
        'date_created'  => date('Y-m-d H:i:s'),
      ];

      if ($this->UserModel->insert_user($data) > 0) {
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
      $user = $this->UserModel->user_login($this->input->post('email'), $this->input->post('password'));

      if (!empty($user) and $user != FALSE) {
        $this->load->library('Authorization_Token');
        date_default_timezone_set('Asia/Jakarta');

        $payload['id']            = $user->id;
        $payload['nama']          = $user->nama;
        $payload['alamat']        = $user->alamat;
        $payload['jk']            = $user->jk;
        $payload['telpon']        = $user->telpon;
        $payload['email']         = $user->email;
        $payload['role_id']       = $user->role_id;
        $payload['is_active']     = $user->is_active;
        $payload['date_created']  = date('Y-m-d H:i:s');
        $payload['time']          = time();

        $user_token = $this->authorization_token->generateToken($payload);

        $data = [
          'user_id'     => $user->id,
          'nama'        => $user->nama,
          'role_id'     => $user->role_id,
          'created_at'  => time(),
          'token'       => $user_token,
        ];
        $this->response([
          'status'  => true,
          'data'    => $data,
          'message' => "User login successful"
        ], REST_Controller::HTTP_OK);
      } else {
        $message = [
          'status'  => FALSE,
          'message' => "Invalid email or Password"
        ];
        $this->response($message, REST_Controller::HTTP_NOT_FOUND);
      }
    }
  }
}
