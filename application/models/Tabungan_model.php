<?php

class Tabungan_model extends CI_Model
{
  public function getTabungan($id = null)
  {
    if ($id === null) {

      return $this->db->get('tb_tabungan')->result_array();
    } else {
      return $this->db->get_where('tb_tabungan', ['id' => $id])->result_array();
    }
  }

  public function createTabungan($data)
  {
    $this->db->insert('tb_tabungan', $data);
    return $this->db->affected_rows();
  }

  public function updateTabungan($data, $id)
  {
    $this->db->update('tb_tabungan', $data, ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function deleteTabungan($id)
  {
    $this->db->delete('tb_tabungan', ['id' => $id]);
    return $this->db->affected_rows();
  }
}
