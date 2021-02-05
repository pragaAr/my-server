<?php

class Angsuran_pinjaman_model extends CI_Model
{

    public function getAngsurByIdPinjam($id = null)
    {
        $this->db->select('*');
        $this->db->select('CONCAT("Angsuran Ke ",(SELECT COUNT(*)+1 FROM tb_angsur a WHERE a.pinjam_id=tb_angsur.pinjam_id AND a.id_angsur<tb_angsur.id_angsur)) AS ket_otomatis', false);

        $this->db->where('pinjam_id', $id);
        $this->db->order_by('tgl_bayar', "desc");
        return $this->db->get('tb_angsur')->result_array();
    }
}
