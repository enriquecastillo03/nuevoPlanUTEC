<?php

/**
 * Procesos_Model
 * 
 * 2013-06-25
 * 
 * @package erpConamype
 * @author Alexis Beltran
 * @copyright 2013
 * @version RC1
 * @access public
 */
class Procesos_Model extends CI_Model
{
    protected $proceso = null;
    
    protected $pasos    = 'pas_paso';
    protected $tareas   = 'tar_tarea';
    protected $oxp      = 'oxp_opcionxpaso';
    protected $permisos = 'per_permiso';
    protected $trayecto = 'try_trayecto';
    protected $oxx      = 'oxx_opcionxpermiso';
    protected $tablas   = 'tab_tabla';
    protected $roles    = 'rol_rol';
    protected $uxr      = 'uxr_usuarioxrol';
    protected $opciones = 'opc_opcion';
    protected $usuarios = 'users';
    
    /**
     * @var Tabla oxr_opcionxrol
     */
    protected $oxr      = 'oxr_opcionxrol';
    
    /**
     * Prototipo de tra_trayecto
     */
    protected $tbl_trayecto = null;
    
    /**
     * Procesos_Model::__construct()
     * @var Object tab_tabla
     * 
     * @return
     */
    function __construct()
    {
        parent::__construct();
        
        //prototipo de tra_trayecto
        $this->tbl_trayecto = (Object) array (
            'tab_id'       => null,
            'tab_nombre'   => $this->trayecto,
            'tab_key'      => 'try_id',
            'tab_key_tipo' => 'int'
        );
    }
    
    
    /**
     * Procesos_Model::get_tarea()
     * 
     * @param mixed $tarea
     * @return result
     */
    function get_tarea($tarea)
    {
        $result = $this->db->get_where($this->tareas,array('tar_id' => $tarea),1);
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_tarea_by_opcion()
     * 
     * @param Object $opcion
     * @return result
     */
    function get_tarea_by_opcion($opcion)
    {
        $this->db->select($this->tareas . '.*');
        $this->db->join($this->pasos,'oxp_id_pas = pas_id');
        $this->db->join($this->tareas,'pas_id_tar = tar_id');
        $this->db->group_by('tar_id');
        $result = $this->db->get_where($this->oxp,array('oxp_id_opc' => $opcion->opc_id));
        if($result->num_rows() == 1){
            return $result->row();
        } 
        return null;
    }
    
    /**
     * Procesos_Model::get_tabla_by_tarea()
     * 
     * @param Object $tarea
     * @return result
     */
    function get_tabla_by_tarea($tarea)
    {
        //print_r($tarea);
        $this->db->select($this->tablas.'.*');
        $this->db->join($this->tareas,'tar_id_tab = tab_id');
        $result = $this->db->get_where($this->tablas,array('tar_id' => $tarea->tar_id));
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null;
    }
    
    //-----------Pasos
    
    /**
     * Procesos_Model::get_paso_by_id()
     * 
     * @param int $paso_id
     * @return object
     */
    function get_paso_by_id($paso_id)
    {
        $result = $this->db->get_where($this->pasos,array('pas_id' => $paso_id),1);
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_pasos_by_tarea()
     * 
     * @param Object $tarea
     * @return result
     */
    function get_pasos_by_tarea($tarea)
    {
        return $this->db->get_where($this->pasos,array('pas_id_tar' => $tarea->tar_id))->result();
    }
    
    /**
     * Procesos_Model::get_paso_final()
     * Obtiene el ultimo paso de un Proceso
     * 
     * @param object $tarea
     * @return object
     */
    function get_paso_final($tarea)
    {
        $pasos = $this->get_pasos_by_tarea($tarea);       
        return array_pop($pasos);
    }
    
    /**
     * Procesos_Model::get_paso_inicial()
     * Obtiene el primer paso de un Proceso.
     * 
     * @param mixed $tarea
     * @return
     */
    function get_paso_inicial($tarea)
    {
        $pasos = $this->get_pasos_by_tarea($tarea);
        return array_pop(array_reverse($pasos));
    }    
    
    /**
     * Procesos_Model::get_paso()
     * Obtiene el Paso en base a los parametros del sistema
     * 
     * @param object $opc
     * @param object $rol
     * @param int $paso
     * @return object
     */
    function get_paso($opc, $roles, $paso)
    {
        $this->db->select( $this->pasos . '.*');
        $this->db->join($this->pasos,'oxp_id_pas = pas_id');
        $this->db->where(array(
            'oxp_id_opc'    => $opc->opc_id,
            'oxp_id_pas'    => $paso
        ));
        
        //obtenemos el primero rol
        if(count($roles) == 1){
            $this->db->where('oxp_id_rol', $roles[0]->rol_id);
        }else{
            $rol_uno = array_pop($roles);
            $this->db->where('oxp_id_rol', $rol_uno->rol_id);
            //si existen mas tambien se toman en cuenta
            if(count($roles) > 0){
                foreach($roles as $rol){
                    $this->db->or_where('oxp_id_rol', $rol->rol_id);
                }
            }
        }
        
        $result = $this->db->get($this->oxp,1);
        //echo $this->db->last_query();
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_paso_by_oxp()
     * Obtiene el Paso en base a los parametros de la db.
     * 
     * @param object $opc
     * @param object $rol
     * @param object $est
     * @param int $orden
     * @return object
     */
    function get_paso_by_oxp($opc, $roles, $est = null, $orden = null)
    {
        $this->db->select($this->pasos . '.*');
        $this->db->join($this->pasos,'oxp_id_pas =  pas_id');
        $this->db->where('oxp_id_opc', $opc->opc_id);
        
        //obtenemos el primero rol
        if(count($roles) == 1){
            $this->db->where('oxp_id_rol', $roles[0]->rol_id);
        }else{
            $rol_uno = array_pop($roles);
            $this->db->where('oxp_id_rol', $rol_uno->rol_id);
            //si existen mas tambien se toman en cuenta
            if(count($roles) > 0){
                foreach($roles as $rol){
                    $this->db->or_where('oxp_id_rol', $rol->rol_id);
                }
            }
        }
        
        $result = $this->db->get($this->oxp,1);
        //echo $this->db->last_query();
        if($result->num_rows() > 0){
            return $result->row();
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_paso_detalle()
     * 
     * 
     * @param Object $paso
     * @param Object array $rol
     * @return object
     */
    function get_paso_detalle($paso, $roles = null)
    {
        if($roles){
            //obtenemos el primero rol
            if(count($roles) == 1){
                $this->db->where('oxp_id_rol', $roles[0]->rol_id);
            }else{
                $rol_uno = array_pop($roles);
                $this->db->where('oxp_id_rol', $rol_uno->rol_id);
                //si existen mas tambien se toman en cuenta
                if(count($roles) > 0){
                    foreach($roles as $rol){
                        $this->db->or_where('oxp_id_rol', $rol->rol_id);
                    }
                }
            }
        }
        $result = $this->db->get_where($this->oxp,array('oxp_id_pas' => $paso->pas_id),1);
        //echo $this->db->last_query();
        if($result->num_rows() > 0){
            return $result->row();
        } 
        return null;
    }
    
    /**
     * Procesos_Model::get_next_paso()
     * Obtiene el siguiente paso del actual
     * 
     * @param Object $paso
     * @return object
     */
    function get_next_paso($tarea, $paso)
    {
        //print_r($paso);
        $result = $this->db->get_where($this->pasos, array(
            'pas_id_tar' => $tarea->tar_id,
            'pas_orden'  => $paso->pas_orden + 1
        ));
        //echo $this->db->last_query();
        if($result->num_rows() > 0){
            return $result->row();
        }
        return null; 
    }
    
    /**
     * Procesos_Model::get_before_paso()
     * Obtiene el paso anterior al actual.
     * 
     * @param mixed $tarea
     * @param mixed $paso
     * @return
     */
    function get_before_paso($tarea, $paso)
    {
        //echo 'before';
        $result = $this->db->get_where($this->pasos, array(
            'pas_id_tar' => $tarea->tar_id,
            'pas_orden'  => $paso->pas_orden - 1
        ));
        //echo $this->db->last_query();
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null; 
    }
    
    /**
     * Procesos_Model::get_permisos_by_paso()
     * 
     * @param Object $paso
     * @return result
     */
    function get_permisos_by_paso($paso, $roles)
    {
        //print_r($paso);
        if(count($paso) > 1){
            die('Sin definir Paso a Ejecutar. ocupar set_paso().');
        }
        $this->db->select($this->permisos . '.*');
        $this->db->join($this->oxp,'oxp_id_pas = pas_id','Inner');
        $this->db->join($this->oxx,'oxx_id_oxp = oxp_id','Inner');
        $this->db->join($this->permisos,'oxx_id_per = per_id','Inner');
        //obtenemos el primero rol
        $rol_uno = array_pop($roles);
        $this->db->where('oxp_id_rol', $rol_uno->rol_id);
        
        //si existen mas tambien se toman en cuenta
        if(count($roles) > 0){
            foreach($roles as $rol){
                $this->db->or_where('oxp_id_rol', $rol->rol_id);
            }
        }
        $this->db->group_by('per_id');
        $result = $this->db->get_where($this->pasos,array('pas_id' => $paso->pas_id));
        //echo $this->db->last_query();
        if($result->num_rows() > 0){
            return $result->result();
        } 
        return null;
    }
    
    //------------Opcion
    
    /**
     * Procesos_Model::get_opcion_by_id()
     * 
     * @param int $opcion
     * @return object
     */
    function get_opcion_by_id($opcion)
    {
        $result = $this->db->get_where($this->opciones,array('opc_id' => $opcion),1);
        if($result->num_rows() == 1){
            return $result->row();
        } 
        return null;
    }
    
    /**
     * Procesos_Model::get_opcion_by_uri()
     * Obtiene la opcion en base a los paramentros de la url.
     * 
     * @param mixed $modulo
     * @param mixed $grupo
     * @param mixed $funcion
     * @return object opcion
     */
    function get_opcion_by_uri($modulo,$grupo,$funcion)
    {
        $this->db->select('funcion.*');
        $this->db->join($this->opciones . ' grupo','grupo.opc_id = funcion.opc_padre');
        $this->db->join($this->opciones . ' modulo','modulo.opc_id = grupo.opc_padre');
        $this->db->where(array(
            'modulo.opc_funcion'  => $modulo,
            'grupo.opc_funcion'   => $grupo,
            'funcion.opc_funcion' => $funcion
        ));
        $result = $this->db->get($this->opciones . ' funcion');
        if($result->num_rows() > 0){
            return $result->row();
        }
        return null; 
    }
    
    /**
     * Procesos_Model::get_roles_by_opcion()
     * Obtiene los roles que posee una opcion de menu
     * 
     * @since 2013-07-06
     * @param int $opcion
     * @return Result
     */
    function get_roles_by_opcion($opcion)
    {
        $this->db->select($this->roles . '.*');
        $this->db->join($this->oxr,'oxr_id_rol = rol_id');
        $result = $this->db->get_where($this->roles, array('oxr_id_opc' => $opcion));
        //echo $this->db->last_query();    //DEL
        if($result->num_rows() > 0){
            return $result->result();
        }
        return null;
    }
    
    //------------Permisos
    
    /**
     * Procesos_Model::get_all_permisos()
     * Obtiene Todos los permisos.
     * 
     * @return Result
     */
    function get_all_permisos(){
        return $this->db->get($this->permisos)->result();
    }
    
    //------------Usuario
    
    /**
     * Procesos_Model::get_rol_of_usuario()
     * 
     * @param mixed $usuario
     * @return result
     */
    function get_rol_of_usuario($usuario){
        $this->db->select($this->roles . '.*');
        $this->db->join($this->roles,'uxr_id_rol = rol_id');
        return $this->db->get_where($this->uxr,array('uxr_id_usu' => $usuario->id))->result();
    }
    
    //------------Trayecto
    
    /**
     * Procesos_Model::get_actual_estado()
     * 
     * @param Object $tarea
     * @param int $id
     * @return
     */
    function get_actual_estado($tarea, $id)
    {
        $tabla = $this->get_tabla_by_tarea($tarea);
        $this->db->select($this->pasos . '.*');
        $this->db->join($this->pasos,'try_id_pas = pas_id');
        $this->db->where(array(
            'try_id_tab' => $tabla->tab_id,
            'try_registro_id' => $id
        ));
        $this->db->order_by('try_fecha_fin','desc');
        $result = $this->db->get($this->trayecto,1);
        //echo $this->db->last_query();
        if($result->num_rows() == 1){
            return $result->row();
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_next_estado()
     * 
     * @param Object $tarea
     * @param int $id
     * @return
     */
    function get_next_estado($tarea, $id)
    {
        $tabla = $this->get_tabla_by_tarea($tarea);
        //Verificamos si existe el registro
        if($this->_row_exists($tabla, $id))
        {
            $actual = $this->get_actual_estado($tarea, $id);
            //verificamos si no es el final del proceso
            $final = $this->get_paso_final($tarea);
            //echo 'actual';print_r($actual);
            //echo 'final';print_r($final);
            if($actual->pas_id != $final->pos_id){
                return $this->get_next_paso($tarea, $actual);
            }else{
                return -1;
            }
        }
        
        return null;
    }
    
    /**
     * Procesos_Model::get_estado()
     * Obtiene El Paso Actual del id
     * 
     * @param mixed $tarea
     * @param mixed $id
     * @return void
     */
    function get_estado($tarea, $id)
    {
        $tabla = $this->get_tabla_by_tarea($tarea);
        $pasos =  $this->get_pasos_by_tarea($tarea);
        //Verificamos si existe el registro en la tabla origen y en trayecto
        if($this->_row_exists($tabla, $id) && count($trayecto = $this->get_trayecto($tarea, $id)) > 0)
        {
            return $this->get_next_estado($tarea, $id);
        }
        return null;
    }
    
    //TODO: Hacer Puro MVC (Alexis/2013-06-27)
    /**
     * Procesos_Model::next_estado()
     * 
     * @param object $tarea
     * @param object $usuario
     * @param int $id
     * @param int $paso
     * @param string $obs
     * @param bool $aprobado
     * @return id nuevo trayecto
     */
    function next_estado($tarea, $usuario, $id, $paso = null, $obs = '', $aprobado =  true)
    {
        //echo "next_estado(id: $id, paso: $paso, obs: $obs, aprobado: $aprobado)<br />\n";
        
        $tabla  = $this->get_tabla_by_tarea($tarea);
        $actual = $this->get_actual_estado($tarea,$id);
        $final =  $this->get_paso_final($tarea);
        
        //Si no hay registro iniciar el trayecto
        if($actual == null){
            $inicial = $this->get_paso_inicial($tarea);
            return $this->db->insert($this->trayecto,array(
                'try_id_tab' => $tabla->tab_id,
                'try_id_use' => $usuario->id,
                'try_id_pas' => $inicial->pas_id,
                'try_registro_id' => $id,
                'try_aceptado' => '2',
                'try_obs' => $obs
            ));
        }
        
        //Validamos si ya alcanzamos el final del proceso.
        if($actual->pas_id == $final->pas_id){
            return null;
        }
        
        //Obtenesmos el siguiente paso en base al resultado del ultimo reg.
        $try_actual =  $this->get_trayecto_actual($tarea, $id);
        if($try_actual->try_aceptado == '3'){
            $next   = $this->get_before_paso($tarea, $actual);
        }else if($paso){
            $next   = $this->get_paso_by_id($paso);
        }else{
            $next   = $this->get_next_paso($tarea, $actual);
        }
        
        //TESTING
        //print_r($actual); print_r($next); print_r($try_actual); die();
        if($actual->pas_id == $next->pas_id){
            return null;
        }
        
        if(!$next->pas_validar){
            //No necesita validacion
            
            //echo ('no valida');
            return $this->db->insert($this->trayecto,array(
                'try_id_tab' => $tabla->tab_id,
                'try_id_use' => $usuario->id,
                'try_id_pas' => $next->pas_id,
                'try_registro_id' => $id,
                'try_aceptado' => '2',
                'try_obs' => $obs
            ));//*/
        }else{
            //Necesita Validacion
            //echo ('validar');
            
            //verificamos si existe un final
            $fin =  $this->get_paso_by_id($next->pas_validar);
            
            $return = $this->db->insert($this->trayecto,array(
                'try_id_tab' => $tabla->tab_id,
                'try_id_use' => $usuario->id,
                'try_id_pas' => $next->pas_id,
                'try_registro_id' => $id,
                'try_aceptado' => ($aprobado)?'2':'3',
                'try_obs' => $obs
            ));
            
            if($fin->pas_orden == -1){
                $this->db->insert($this->trayecto,array(
                    'try_id_tab' => $tabla->tab_id,
                    'try_id_use' => $usuario->id,
                    'try_id_pas' => $fin->pas_id,
                    'try_registro_id' => $id,
                    'try_aceptado' => ($aprobado)?'2':'3',
                    'try_obs' => $obs
                ));
            }
            
            return $return; 
        }
    }
    
    /**
     * Procesos_Model::get_trayecto()
     * Tr
     * 
     * @param Object $tarea
     * @param int $id
     * @return result
     */
    function get_trayecto($tarea, $id)
    {
        if($tarea && $id > 0 ){
            $tabla = $this->get_tabla_by_tarea($tarea);
            $this->db->where(array(
                'try_id_tab'        => $tabla->tab_id,
                'try_registro_id'   => $id
                )
            );
            $this->db->group_by('try_id');
            $this->db->order_by('try_id','desc');
            $result = $this->db->get($this->trayecto);
            if($result->num_rows() > 0){
                return $result->result();
            }
        }
        return null;
    }

    
    /**
     */
    function get_historico($tarea, $id = null)
    {
        $this->db->select(
 'try_id,
  try_registro_id As registro,
  pas_nombre As paso,
  username,
  try_fecha_fin As fecha,
  IF(try_aceptado = 1, "Pendiente" , 
        IF(try_aceptado = 2, "Aprobado" , "Rechazado") ) As estado,
  try_obs As obs'
        , false);
        $this->db->join($this->usuarios, 'try_id_use = id');
        $this->db->join($this->pasos, 'try_id_pas = pas_id');
        $this->db->join($this->tablas, 'try_id_tab =  tab_id');
        $this->db->join($this->tareas, 'try_id_tab = tab_id AND pas_id_tar = tar_id');
        
        if($id){
            $this->db->where('try_registro_id', $id);
        }
        $this->db->where('tar_id', $tarea->tar_id);
        
        $this->db->group_by('registro, fecha');
        
        $result =  $this->db->get($this->trayecto);
        //echo $this->db->last_query(); //DEL:
        if($result->num_rows() > 0){
            $result = $result->result(); 
            //print_r($result); //DEL:
            return $result;
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_trayecto_actual()
     * 
     * @param object $tarea
     * @param int $id
     * @return object
     */
    function get_trayecto_actual($tarea, $id)
    {
        $trayecto =  $this->get_trayecto($tarea, $id);
        if(count($trayecto)){
            return array_pop(array_reverse($trayecto));
        }
        return null;
    }
    
    /**
     * Procesos_Model::get_registro_by_paso()
     * 
     * @param object $tarea
     * @param object $paso
     * @return result
     */
    function get_registro_by_paso($tarea, $paso)
    {
        $tabla =  $this->get_tabla_by_tarea($tarea);
        $this->db->where(array(
            'try_id_tab' => $tabla->tab_id,
            'try_id_pas' => $paso->pas_id
        ));
        $this->db->group_by('try_registro_id');
        return $this->db->get($this->trayecto)->result();
    }
    
    /**
     * Procesos_Model::primer_trayecto()
     * @deprecated 
     * 
     * @param mixed $paso
     * @param mixed $usuario
     * @param mixed $tabla
     * @param mixed $id
     * @param string $obs
     * @return
     */
    function primer_trayecto($paso, $usuario, $tabla, $id, $obs = '')
    {
        if($this->_row_exists($tabla, $id)){
            //Primer registro
            $this->db->insert($this->trayecto,array(
                'try_id_tab'        => $tabla->tab_id,
                'try_id_use'        => $usuario->id,
                'try_id_pas'        => $paso->pas_id,
                'try_registro_id'   => $id,
                'try_fecha_inicio'  => $this->_now(),
                'try_fecha_fin'     => $this->_now(),
                'try_acepta'        => 1,
                'try_obs'           => $obs
            ));
            //Segundo Registro
            $this->db->insert($this->trayecto,array(
                'try_id_tab'        => $tabla->tab_id,
                'try_id_use'        => null,
                'try_id_pas'        => $paso->pas_id,
                'try_registro_id'   => $id,
                'try_fecha_inicio'  => $this->_now(),
                'try_fecha_fin'     => null,
                'try_acepta'        => null,
                'try_obs'           => ''
            ));
        }
    }
    
    //---------
    
    /**
     * Procesos_Model::_row_exists()
     * 
     * @param Object $tabla
     * @param mixed $id
     * @return
     */
    protected function _row_exists($tabla, $id)
    {
        $row = $this->db->get_where($tabla->tab_nombre,array($tabla->tab_key => $id),1);
        return ($row->num_rows() == 1);
    }
    
    protected function _now()
    {
        return date('Y-m-d H:i:s');
    } 
}

?>