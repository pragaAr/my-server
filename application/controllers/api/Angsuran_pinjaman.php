<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Angsuran_pinjaman extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('Angsuran_pinjaman_model');
    }

    public function index_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization");

        $is_valid_token = $this->authorization_token->validateToken();
        if (!empty($is_valid_token) and $is_valid_token['status'] === TRUE) {
            $id = $this->get('id_angsur');
            if (isset($id)) {
                $angsuran = $this->Angsuran_pinjaman_model->getAngsurByIdPinjam($id);
                $this->response([
                    'status'    => true,
                    'data'      => $angsuran
                ], REST_Controller::HTTP_OK);
                return;
            }
            $this->response([
                'status'    => false,
                'message'   => 'id tidak ditemukan'
            ], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response(['status' => FALSE, 'message' => $is_valid_token['message']], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
