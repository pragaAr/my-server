<?php

class Anggota_model extends CI_Model
{
  public function getAnggota($id = null)
  {
    if ($id === null) {

      return $this->db->get('tb_anggota_user')->result_array();
    } else {
      return $this->db->get_where('tb_anggota_user', ['id_anggota' => $id])->result_array();
    }
  }

  public function createAnggota($data)
  {
    $this->db->insert('tb_anggota_user', $data);
    return $this->db->affected_rows();
  }

  public function updateAnggota($data, $id)
  {
    $this->db->update('tb_anggota_user', $data, ['id_anggota' => $id]);
    return $this->db->affected_rows();
  }

  public function deleteAnggota($id)
  {
    $this->db->delete('tb_anggota_user', ['id_anggota' => $id]);
    return $this->db->affected_rows();
  }
}
