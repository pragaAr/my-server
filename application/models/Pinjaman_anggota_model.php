<?php

class Pinjaman_anggota_model extends CI_Model
{
  public function getPinjamanAnggota()
  {
    $query = "SELECT `tb_pinjam`.*,`tb_anggota_user`.`nama`
                FROM `tb_pinjam` JOIN `tb_anggota_user`
                  ON `tb_pinjam`.`anggota_id` = `tb_anggota_user`.`id_anggota`                
              ";
    return $this->db->query($query)->result_array();
  }
}
