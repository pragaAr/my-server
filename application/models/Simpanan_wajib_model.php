<?php

class Simpanan_wajib_model extends CI_Model
{
  public function getSimpananAnggota()
  {
    $query = "SELECT `tb_simpanan_wajib`.*,`tb_anggota_user`.`nama`
                FROM `tb_simpanan_wajib` JOIN `tb_anggota_user`
                  ON `tb_simpanan_wajib`.`anggota_id` = `tb_anggota_user`.`id_anggota`                
              ";
    return $this->db->query($query)->result_array();
  }
  public function getSimpananWajib($id = null)
  {
    if ($id === null) {

      return $this->db->get('tb_simpanan_wajib')->result_array();
    } else {
      return $this->db->get_where('tb_simpanan_wajib', ['id_sim_wajib' => $id])->result_array();
    }
  }

  public function createSimpananWajib($data)
  {
    $this->db->insert('tb_simpanan_wajib', $data);
    return $this->db->affected_rows();
  }

  public function updateSimpananWajib($data, $id)
  {
    $this->db->update('tb_simpanan_wajib', $data, ['id_sim_wajib' => $id]);
    return $this->db->affected_rows();
  }

  public function deleteSimpananWajib($id)
  {
    $this->db->delete('tb_simpanan_wajib', ['id_sim_wajib' => $id]);
    return $this->db->affected_rows();
  }
}
