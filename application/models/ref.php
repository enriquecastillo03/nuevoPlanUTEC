<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Ref extends CI_Model
{

	function __construct()
	{
		$this->load->database();
	}

	public function get_tabla($tabla,$where="")
   {   		
		$this->db->select()
			->where($where)
			->from($tabla);			
		$query=$this->db->get();
		return $query->result_array();
   }

   public function get_registro($tabla,$where)
   {   		
		$this->db->select()
			->where($where)
			->from($tabla);			
		$query=$this->db->get();
		return $query->row_array();
   }

  public function add_opc($rol,$opc)
	{
		$this->db->insert('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_opc'=>$opc));
		return $this->db->affected_rows();
	}

   public function del_opc($rol,$opc)
	{		
		$this->db->delete('oxr_opcionxrol',array('oxr_id_rol'=>$rol,'oxr_id_opc'=>$opc));
		return $this->db->affected_rows();	
	}
  
}

