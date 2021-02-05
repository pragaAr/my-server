<?php defined('BASEPATH') or exit('No direct script access allowed');

class AnggotaUser_model extends CI_Model
{
  public function insert_user($data)
  {
    $this->db->insert('tb_anggota_user', $data);
    return $this->db->affected_rows();
  }

  public function user_login($email, $password)
  {
    $this->db->where('email', $email);
    $q = $this->db->get('tb_anggota_user');

    if ($q->num_rows()) {
      $user_pass = $q->row('password');
      if (md5($password) === $user_pass) {
        return $q->row();
      }
      return FALSE;
    } else {
      return FALSE;
    }
  }
}
