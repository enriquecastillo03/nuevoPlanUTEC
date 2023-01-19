<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caja_model extends CI_Model {

    public $variable;

    public function __construct()
    {
        parent::__construct();
        //Do your magic here!
        $this->load->database();
    }
    /**
     * Obtiene ID de cuenta presupuestaria de acuerdo a la fecha de insercion de pago
     * @param  integer $id_detalle ID detalle de cargo
     * @return integer ID de cuenta presupuestaria
     */
    public function getIdCuentaPresupuestaria( $id_detalle = 0 )
    {
        $query_cuenta_presupuestaria = "SELECT CASE
        WHEN  TIMESTAMPDIFF(MONTH, detalle_cuenta.det_fecha, NOW()) < 1 THEN servicio.srv_id_cpr_pasado
        WHEN  TIMESTAMPDIFF(MONTH, detalle_cuenta.det_fecha, NOW()) = 1 THEN servicio.srv_id_cpr_actual
        WHEN  TIMESTAMPDIFF(MONTH, detalle_cuenta.det_fecha, NOW()) > 2 THEN servicio.srv_id_cpr_futuro
        END AS cuenta_presupuestaria
        FROM det_detalle_cntc AS detalle_cuenta
        INNER JOIN sxc_servicioxcuenta_corriente AS sxc ON sxc.sxc_id = detalle_cuenta.det_id_sxc
        INNER JOIN srv_servicio AS servicio ON servicio.srv_id = sxc.sxc_id_srv
        WHERE detalle_cuenta.det_id = ?";

        $resultado = $this->db->query( $query_cuenta_presupuestaria, array( $id_detalle ) );
        return ( is_object( $resultado ) && $resultado->num_rows() > 0 )
        ? $resultado->row()->cuenta_presupuestaria
        : false;
    }

    /**
     * Establece a que cuenta presupuestaria pertenece el cobro de cada detalle de cargo mensual
     * @param  integer $id_detalle ID detalle cargo
     * @param  integer $id_cuenta_presupuestaria ID cuenta presupuestaria
     * @return boolean resultado actualizacion
     */
    public function actualizarCuentaPresupuestaria( $id_detalle, $id_cuenta_presupuestaria )
    {
        $data_detalle = array( 
            'det_id_cpr' => $id_cuenta_presupuestaria 
            );
        $this->db->where('det_id', $id_detalle );
        $this->db->update( 'det_detalle_cntc', $data_detalle );

        return true;
    }
}

/* End of file caja.php */
/* Location: ./application/models/caja.php */