<?php

class Angsuran_model extends CI_Model
{
  public function deleteAngsuran($id)
  {
    $this->db->delete('tb_angsur', ['id_angsur' => $id]);
    return $this->db->affected_rows();
  }

  public function createAngsuran($data)
  {
    $this->db->insert('tb_angsur', $data);
    return $this->db->affected_rows();
  }

  public function updateAngsuran($data, $id)
  {
    $this->db->update('tb_angsur', $data, ['id_angsur' => $id]);
    return $this->db->affected_rows();
  }

  public function getAngsur($id = null)
  {
    $this->db->select('*');
    $this->db->select('COALESCE((SELECT SUM(nom_angsur) FROM tb_angsur WHERE tb_angsur.pinjam_id=tb_pinjam.id_pinjam), 0) AS total_angsur', false);
    $this->db->select('tb_pinjam.total_bayar - COALESCE((SELECT SUM(nom_angsur) FROM tb_angsur WHERE tb_angsur.pinjam_id=tb_pinjam.id_pinjam), 0) AS sisa_angsur', false);
    $this->db->join('tb_anggota_user', 'tb_anggota_user.id_anggota=tb_pinjam.anggota_id');

    if ($id == null) {
      return $this->db->get('tb_pinjam')->result_array();
    } else {
      return $this->db->get_where('tb_pinjam', ['id_pinjam' => $id])->result_array();
    }
  }
}
