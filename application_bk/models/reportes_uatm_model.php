<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reportes_uatm_model extends CI_Model
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
	public function get_tabla($tabla,$where=0)
   {
		$this->db->select();
		if($where!=0)
		$this->db->where($where);
		$this->db->from($tabla);				
		$query=$this->db->get();
		return $query->result_array();
   }

   public function get_mora_rubro($fecha1=0, $fecha2=0)
   {
    $this->db->select('srv_nombre, sum(det_monto) as mora')    
        ->where('srv_tiempo_corte',30)
        ->from('srv_servicio')
        ->join('sxc_servicioxcuenta_corriente','sxc_id_srv=srv_id','left');
        if ($fecha1 == 0 && $fecha2 == 0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha<='.date('Ymd',strtotime('-1 month')),'left');
        }
        if ($fecha1 != 0 && $fecha2!=0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha>='.$fecha1.' AND det_fecha<='.$fecha2. ' AND det_fecha<='.date('Ymd',strtotime('-1 month')),'left');          
        }
        if ($fecha1 != 0 && $fecha2==0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha>='.$fecha1.' AND det_fecha<='.date('Ymd',strtotime('-1 month')),'left');          
        }
        if ($fecha1 == 0 && $fecha2!=0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha<='.$fecha2.' AND det_fecha<='.date('Ymd',strtotime('-1 month')),'left');          
        }
        $this->db->group_by('srv_nombre');
    $query=$this->db->get();
    return $query->result_array();
   }

   public function get_f1($fecha1=0, $fecha2=0)
   {
    $this->db->select('count(fun_id) as f1, fun_fecha, GROUP_CONCAT(fun_id) AS ids_funos');    

      if ($fecha1 != 0) {
        $this->db->where('fun_fecha>=',$fecha1);        
      }
      if ($fecha2!=0) {
        $this->db->where('fun_fecha<=',$fecha2);
      }
    
    $this->db->from('fun_funo');
    $this->db->group_by('fun_fecha');
    $query=$this->db->get();
    //echo $this->db->last_query();
    return $query->result_array();
   }

   public function get_fxd($fecha1=0,$fecha2=0)
   {
    $this->db->select('fun_fecha, sum(det_monto) as monto, fun_concepto, fun_subtotal, fun_id, (select sum(det_monto) from det_detalle_cntc where det_estado=1 AND det_id_sxc=det.det_id_sxc) as saldo');
    if ($fecha1 != 0) {
        $this->db->where('fun_fecha>=',$fecha1);        
      }
      if ($fecha2!=0) {
        $this->db->where('fun_fecha<=',$fecha2);
      }
          $this->db->from('fun_funo')
          ->join('fxd_funoxdetalle_cntc','fxd_id_fun=fun_id')
          ->join('det_detalle_cntc det','det_id=fxd_id_det','left')          
          ->group_by('fun_id');
    $query=$this->db->get();
    return $query->result_array();
   }

   public function get_mora_presupuestaria($fecha1=0,$fecha2=0)
   {
    $this->db->select('cpr_id, cpr_nombre, cpr_numero, sum(det_monto) as mora')
        ->from('cpr_cuenta_presupuesto')
        ->join('srv_servicio','srv_id_cpr_pasado=cpr_id')
        ->join('sxc_servicioxcuenta_corriente','sxc_id_srv=srv_id');
        if ($fecha1 == 0 && $fecha2 == 0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha<='.date('Ymd',strtotime('-1 month')));
        }
        if ($fecha1 != 0 && $fecha2!=0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha>='.$fecha1.' AND det_fecha<='.$fecha2. ' AND det_fecha<='.date('Ymd',strtotime('-1 month')));          
        }
        if ($fecha1 != 0 && $fecha2==0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha>='.$fecha1.' AND det_fecha<='.date('Ymd',strtotime('-1 month')));          
        }
        if ($fecha1 == 0 && $fecha2!=0) {
          $this->db->join('det_detalle_cntc','det_id_sxc=sxc_id AND det_estado = 1 AND det_fecha<='.$fecha2.' AND det_fecha<='.date('Ymd',strtotime('-1 month')));          
        }
        $this->db->group_by('cpr_id');
    $query=$this->db->get();
    return $query->result_array();
   }

   
}

