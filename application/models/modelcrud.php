<?php
/**
 *
 */
class Modelcrud extends CI_Model
{

  public function get($batas=NULL,$offset=NULL,$cari=NULL)
  {
      if ($batas != NULL) {
        $this->db->limit($batas,$offset);
      }
      if ($cari != NULL) {
          $this->db->or_like($cari);
      }
      $this->db->from('users');
      $query = $this->db->get();
      return $query->result();
  }
  public function jumlah_row($search)
  {
    $this->db->or_like($search);
    $query = $this->db->get('users');

    return $query->num_rows();
  }



  public function get_by_id($kondisi)
  {
      $this->db->from('users');
      $this->db->where($kondisi);
      $query = $this->db->get();
      return $query->row();
  }

  public function insert($data)
  {
      $this->db->insert('users',$data);
      return TRUE;
  }
  public function delete($where)
  {
      $this->db->where($where);
      $this->db->delete('users');
      return TRUE;
  }
  public function update($data,$kondisi)
  {
      $this->db->update('users',$data,$kondisi);
      return TRUE;
  }

}
