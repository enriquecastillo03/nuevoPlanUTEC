<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Sistema extends CI_Model
{

	function __construct()
	{
		$this->load->database();
	}

	public function cargar_menus($id,$nivel,$padre=0)
   {
   		$where=array('opc_nivel'=>$nivel);
   		if($padre!=0)
   			$where=array('opc_padre'=>$padre);
		$this->db->select()
			->where('uxr_id_usu',$id)
			->from('uxr_usuarioxrol')
			->join('oxr_opcionxrol','oxr_id_rol=uxr_id_rol')
			->join('opc_opcion','opc_id=oxr_id_opc')
			->where($where);
		$query=$this->db->get();
		//echo $this->db->last_query();


         //print_r($query);

		return $query->result_array();
   }

   public function cargar_menus0()
   {   		
   		$this->db->select()
   		->where('opc_nivel',0)
		->from('opc_opcion');
		$query=$this->db->get();				
		return $query->result_array();   	
   }

   public function cargar_opciones($rol)
   {   		
   		$this->db->select()
   		->from('oxr_opcionxrol')
		->join('opc_opcion','opc_id=oxr_id_opc')
		->where('oxr_id_rol',$rol);
		$query=$this->db->get();				
		return $query->result_array();   	
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

	public function del_registro($tabla,$where)
	{		
		$this->db->delete($tabla,$where);
		return $this->db->affected_rows();	
	}

   public function modulo_actual($modulo)
   {
   		$this->db->select()
			->where('opc_funcion',$modulo)
			->from('opc_opcion');
		$query=$this->db->get();		
		if($query->num_rows()>0)
			return $query->row_array();
		else 
			return array('opc_id'=>-1);
   }

   public function cargar_tabla($tabla)
   {   		
   		$this->db->select()			
		->from($tabla);
		$query=$this->db->get();				
		return $query->result_array();   	
   }

   public function get_tabla($tabla,$where=0)
   {
		$this->db->select();
		if($where!=0)
		$this->db->where($where);
		$this->db->from($tabla);			
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

   public function add_registro($tabla,$cadena)
   {   		
		$this->db->insert($tabla,$cadena);
		return $this->db->affected_rows();
   }

   public function mod_registro($tabla, $where, $set)
  {
    $this->db->where($where);
    $this->db->update($tabla, $set); 
   return $this->db->affected_rows(); 
  }

   
}

