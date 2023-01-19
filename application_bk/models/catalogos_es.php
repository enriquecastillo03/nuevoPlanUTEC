<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Catalogos_es extends CI_Model
{

	function __construct()
	{
		$this->load->database();
		//$this->db->db_debug=false;
	}
	

	/**
	 * Consulta general de cualquier tabla
	 * @param  string  $tabla
	 * @param  array $where
	 * @param  integer $join
	 * @return array
	 */
	public function obtener_inmuebles()
   {
		

   }

}


