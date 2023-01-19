<?php
class Reporteria extends CI_Model
{
	function __construct()
	{
		$this->load->database();
	}

	public function ingresar($tabla, $cadena)
    {
        $this->db->insert($tabla, $cadena);
        return $this->db->insert_id();
    } 
	
	public function cargar_todo($tabla)
   	{
		$this->db->select();
		$this->db->from($tabla);
		$query=$this->db->get();
		return $query->result_array();
   	}

   	public function cargar_registros($tabla,$campo,$id)
   	{
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where($campo,$id);
		$query=$this->db->get();
		return $query->result_array();
   	}
    
   	public function cargar_registro($tabla,$campo,$id)
   	{
		$this->db->select();
		$this->db->from($tabla);
		$this->db->where($campo,$id);
		$query=$this->db->get();
		return $query->row_array();
   	}

   	function verificar_registros($from,$where)
   	{
		$this->db->select();
		$this->db->from($from);
		$this->db->where($where);
		$query=$this->db->get();
		return $query->result_array();
   	}

	function verificar_registro($from,$where)
   	{
		$this->db->select();
		$this->db->from($from);
		$this->db->where($where);
		$query=$this->db->get();
		return $query->row_array();
   	}
   
	function cargar_reportes($rol)
	{
		$this->db->select();
		$this->db->from('rep_reporte');
		$this->db->join('rxr_reportexrol',' rep_reporte.rep_id = rxr_reportexrol.rxr_id_rep');
		$this->db->where('rxr_reportexrol.rxr_id_rol',$rol);
		$rs=$this->db->get();
		return $rs->result_array();
	}

}
/*END*/