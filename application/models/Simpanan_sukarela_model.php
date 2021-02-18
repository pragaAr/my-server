<?php

class Simpanan_sukarela_model extends CI_Model
{
  public function getSimpananAnggota()
  {
    $query = "SELECT `tb_simpanan_sukarela`.*,`tb_anggota_user`.`nama`
                FROM `tb_simpanan_sukarela` JOIN `tb_anggota_user`
                  ON `tb_simpanan_sukarela`.`anggota_id` = `tb_anggota_user`.`id_anggota`                
              ";
    return $this->db->query($query)->result_array();
  }

  public function getSimpananSukarela($id = null)
  {
    if ($id === null) {

      return $this->db->get('tb_simpanan_sukarela')->result_array();
    } else {
      return $this->db->get_where('tb_simpanan_sukarela', ['id_sukarela' => $id])->result_array();
    }
  }

  public function createSimpananSukarela($data)
  {
    $this->db->insert('tb_simpanan_sukarela', $data);
    return $this->db->affected_rows();
  }

  public function updateSimpananSukarela($data, $id)
  {
    $this->db->update('tb_simpanan_sukarela', $data, ['id_sukarela' => $id]);
    return $this->db->affected_rows();
  }

  public function deleteSimpananSukarela($id)
  {
    $this->db->delete('tb_simpanan_sukarela', ['id_sukarela' => $id]);
    return $this->db->affected_rows();
  }
}
