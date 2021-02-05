<?php

class Pinjaman_model extends CI_Model
{
  public function getPinjaman($id = null)
  {
    if ($id == null) {
      return $this->db->get('tb_pinjam')->result_array();
    } else {
      return $this->db->get_where('tb_pinjam', ['id_pinjam' => $id])->result_array();
    }
  }

  public function createPinjaman($data)
  {
    $this->db->insert('tb_pinjam', $data);
    return $this->db->affected_rows();
  }

  public function deletePinjaman($id)
  {
    $this->db->delete('tb_pinjam', ['id_pinjam' => $id]);
    return $this->db->affected_rows();
  }

  public function updatePinjaman($data, $id)
  {
    $this->db->update('tb_pinjam', $data, ['id_pinjam' => $id]);
    return $this->db->affected_rows();
  }
}
