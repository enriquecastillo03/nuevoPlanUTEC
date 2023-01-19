<?php

/**
 * 
 */
class Gsatelite extends CI_Model
{

    /**
     * Gsatelite::__construct()
     * 
     * @return
     */
    function __construct()
    {
        $this->load->database();
        $this->db->db_debug=false;
    }

    /**
     * Gsatelite::ingresar()
     * 
     * @param mixed $tabla
     * @param mixed $cadena
     * @return
     */
    public function ingresar($tabla, $cadena)
    {
        $this->db->insert($tabla, $cadena);
        return $this->db->insert_id();
    } //fin de esta funcion

    /**
     * Gsatelite::actualizar()
     * 
     * @param mixed $tabla
     * @param mixed $cadena
     * @param mixed $campo
     * @param mixed $condicion
     * @return
     */
    public function actualizar($tabla, $cadena, $campo, $condicion)
    {
        $this->db->where($campo, $condicion);
        $this->db->update($tabla, $cadena);
        return $this->db->affected_rows();
    } //fin de esta funcion

    function actualizar_correlativos($tabla,$cadena,$valor1,$valor4)
    {
        $this->db->where('cor_id_ser',$valor1);
        $this->db->where('cor_numero',$valor4);
        $this->db->update($tabla, $cadena);
        return $this->db->affected_rows();
    }
    
    /**
     * Gsatelite::get_dropdown()
     * Genera un array para utilizar con form_dropdown.
     * 
     * @author Alexis Beltran
     * @param string $tabla 
     * @param string $display Campo a desplegar
     * @param string $value Campo en value
     * @param string $where
     * @return array
     */
    function get_dropdown($tabla, $display, $value, $where = '')
    {
        $this->db->select($value . ', ' . $display);
        if($where) $this->db->where($where);
        $result = $this->db->get($tabla);
        $return = array();
        foreach ($result->result() as $row){
            $return[$row->$value] = $row->$display;
        }
        return $return;
    }


    /**
     * Gsatelite::borrado_general()
     * 
     * @param mixed $tabla
     * @param mixed $cadena
     * @return
     */
    public function borrado_general($tabla, $cadena)
    {
        $this->db->delete($tabla, $cadena);
        return $this->db->affected_rows();
    } //fin de esta funcion


}

?>