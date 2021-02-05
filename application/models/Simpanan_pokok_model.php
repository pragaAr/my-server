<?php

class Simpanan_pokok_model extends CI_Model
{
  public function getSimpananAnggota()
  {
    $query = "SELECT `tb_simpanan_pokok`.*,`tb_anggota_user`.`nama`
                FROM `tb_simpanan_pokok` JOIN `tb_anggota_user`
                  ON `tb_simpanan_pokok`.`anggota_id` = `tb_anggota_user`.`id_anggota`                
              ";
    return $this->db->query($query)->result_array();
  }
  public function getSimpananPokok($id = null)
  {
    if ($id === null) {

      return $this->db->get('tb_simpanan_pokok')->result_array();
    } else {
      return $this->db->get_where('tb_simpanan_pokok', ['id_sim_pokok' => $id])->result_array();
    }
  }

  public function createSimpananPokok($data)
  {
    $this->db->insert('tb_simpanan_pokok', $data);
    return $this->db->affected_rows();
  }

  public function updateSimpananPokok($data, $id)
  {
    $this->db->update('tb_simpanan_pokok', $data, ['id_sim_pokok' => $id]);
    return $this->db->affected_rows();
  }

  public function deleteSimpananPokok($id)
  {
    $this->db->delete('tb_simpanan_pokok', ['id_sim_pokok' => $id]);
    return $this->db->affected_rows();
  }
}
