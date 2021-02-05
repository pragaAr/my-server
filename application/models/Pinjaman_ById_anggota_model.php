<?php

class Pinjaman_ById_anggota_model extends CI_Model
{

  public function getData($id)
  {
    return $this->db->query("SELECT tb_anggota_user.id_anggota, tb_pinjam.total_bayar, tb_simpanan_wajib.nominal_wajib, tb_simpanan_pokok.nominal_pokok, tb_simpanan_sukarela.nominal_sukarela FROM tb_anggota_user JOIN tb_pinjam ON tb_anggota_user.id_anggota = tb_pinjam.anggota_id JOIN tb_simpanan_wajib ON tb_anggota_user.id_anggota = tb_simpanan_wajib.anggota_id JOIN tb_simpanan_pokok ON tb_anggota_user.id_anggota = tb_simpanan_pokok.anggota_id JOIN tb_simpanan_sukarela ON tb_anggota_user.id_anggota = tb_simpanan_sukarela.anggota_id WHERE tb_anggota_user.id_anggota ='$id'")->result_array();
  }
}
