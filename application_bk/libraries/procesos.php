<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Procesos
 * Libreria que maneja procesos.
 * 
 * 2013-06-25
 * 
 * @package erpConamype
 * @author Alexis Beltran
 * @copyright 2013
 * @version RC1
 * @access public
 */
 
 //----------------------------------------------------------------------------
/**
 * Procesos_Model_Driver
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos_Model_Driver
{
    public $driver = null;
    
    protected function set_default_Model($proceso)
	{
		$ci = &get_instance();
		$ci->load->model('Procesos_Model');
		
		$this->driver = $ci->Procesos_Model;
	}
    
    protected function primer_trayecto($id){
        $tabla = $this->tabla->tab_nombre;
        $campo = $this->tabla->tab_key;
    }
    
    protected function get_paso_driver($paso){
        if($this->opcion != null  && count($this->roles) > 0){
            $this->paso = $this->driver->get_paso(
                $this->opcion,
                $this->roles,
                $paso
            );
        }
    }
    
    protected function get_paso_by_oxp(){
        $this->paso = $this->driver->get_paso_by_oxp(
            $this->opcion,
            $this->roles
        );
    } 
    
    
}

/**
 * Procesos_Tarea
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos_Tarea extends Procesos_Model_Driver
{   
    protected $tarea = null;
    protected $tabla = null;
    
    protected function get_tabla($tarea)
    {
        $this->tabla = $this->driver->get_tabla_by_tarea($tarea);
    }
}

/**
 * Procesos_Pasos
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos_Pasos extends Procesos_Tarea
{
    protected $pasos        = array();
    protected $paso         = null;
    protected $paso_detalle = array();
    
    protected function get_pasos($tarea)
    {
        $this->pasos = $this->driver->get_pasos_by_tarea($tarea);
    }
    
    /**
     * Procesos_Pasos::get_paso()
     * 
     * @param mixed $paso
     * @return void
     */
    protected function get_paso()
    {
        if($this->paso != null){
            //sino en base a introducido por programador
            $paso = $this->driver->get_paso(
                $this->opcion,
                $this->roles,
                $this->paso
            );
            if($paso == null){
                $paso = $this->driver->get_paso_by_id($this->paso);
            }
        }else{
            //tratar de optener paso, sino fue definido.
            $paso = $this->driver->get_paso_by_oxp(
                $this->opcion,
                $this->roles
            );
            
        }
        //print_r($paso);die('==');
        //Si existe paso obtener el detalle
        if($paso != null){
            $this->paso = $paso;
            $this->paso_detalle = $this->driver->get_paso_detalle($this->paso, $this->roles);
            return;
        }
        
        //die('Declaración de Paso es obligatoria.');
    }
}

/**
 * Procesos_Trayecto
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos_Trayecto extends Procesos_Pasos
{
    protected $id                   = null;
    //protected $
}



/**
 * Procesos_Permisos
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos_Permisos extends Procesos_Trayecto
{
    protected $permisos = array();
    protected $permiso  = array();
    protected $usuario  = null;
    protected $roles    = array();
    protected $opciones = array();
    protected $opcion   = null;
    
    protected function get_all_permisos()
    {
        $this->permisos = $this->driver->get_all_permisos();
    }
    
    protected function get_permiso()
    {
        
    }
    
    //Obtiene la opcion actual
    /**
     * Procesos_Permisos::get_opcion()
     * 
     * @return void
     */
    protected function get_opcion()
    {
        $ci = &get_instance();
        $modulo = $ci->uri->segment(1);
        $grupo = $ci->uri->segment(2);
        $funcion = $ci->uri->segment(3);
        
        if($this->opcion != null){
            $opcion = $this->driver->get_opcion_by_id($this->opcion);
        }else{
            $opcion = $this->driver->get_opcion_by_uri($modulo,$grupo,$funcion);
        }
        
        if($opcion != null){
            return $this->opcion = $opcion;
        }
        
        die('Opcion es Obligatoria.');
    }
    
    protected function get_tarea_by_opcion()
    {
        if($this->tarea != null){
            $tarea = $this->driver->get_tarea($this->tarea);
        }else{
            $tarea = $this->driver->get_tarea_by_opcion($this->opcion);
        }
        if($tarea != null){
            return $this->tarea =  $tarea;
        }
        die('Tarea es obligatoria');
    }
    
    protected function get_permisos_by_paso()
    {
        if($this->paso){
            $this->permiso = $this->driver->get_permisos_by_paso($this->paso, $this->roles);
        }
    }
    
    protected function get_roles_by_user()
    {
        $this->roles = $this->driver->get_rol_of_usuario($this->usuario);
    }
}

/**
 * Procesos
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
/**
 * Procesos
 * 
 * @package   
 * @author erpConamype
 * @copyright satelite
 * @version 2013
 * @access public
 */
class Procesos extends Procesos_Permisos
{
	/**
	 * Grocery CRUD version
	 * 
	 * @var	string
	 */
	const	VERSION = "0.1";    
    protected $pre_run          = false;
    
    
    function __construct()
	{
		$this->ci =& get_instance();
        
        $this->ci->load->library('Tank_auth');
        $this->ci->load->library('session');
    }
    
    /**
     * Procesos::Procesos()
     * 
     * @param int $tarea
     * @param int $paso
     * @param int $opcion
     * @return object
     */
    //public function Procesos($tarea = null, $paso = null, $opcion = null){
    //    $this->set_variables($tarea, $paso, $opcion);
    //    return $this;
    //}
    
    /**
     * Procesos::pre_run()
     * Obtiene todos los parametros necesarios
     * 
     * @return
     */
    protected function pre_run()
    {
        //para no repetir
        if($this->pre_run) return;
        $this->pre_run = true;
        
        $this->set_default_Model($this);
        $this->get_all_permisos();
        
        $this->usuario  = $this->ci->users->get_user_by_id($this->ci->tank_auth->get_user_id(),true);
        $this->get_roles_by_user();        
        
        $this->get_opcion();
        $this->get_tarea_by_opcion();
        $this->get_paso();
        
        $this->get_pasos($this->tarea);
        $this->get_tabla($this->tarea);
        
        $this->get_permisos_by_paso();
        
        //print_r($this);
    }
    
    /**
     * Procesos::set_tarea()
     * 
     * @param int $tarea
     * @return
     */
    public function set_tarea($tarea)
    {
        if(!empty($tarea) && is_numeric($tarea) && $tarea > 0){
            $this->tarea    = $tarea;
        }else if($tarea != null){
            die('Tarea Incorrecta.');
        }
        return $this;
    }
    
    /**
     * Procesos::set_opcion()
     * 
     * @param int $opcion
     * @return
     */
    public function set_opcion($opcion)
    {
        if(!empty($opcion) && is_numeric($opcion) && $opcion > 0){
            $this->opcion   = $opcion;
        }else if($opcion != null){
            die('Opcion Incorrecta.');
        }
        return $this;
    }
    
    /**
     * Procesos::set_paso()
     * 
     * @param int $paso
     * @return object
     */
    public function set_paso($paso)
    {
        if(!empty($paso) && is_numeric($paso) && $paso > 0){
            $this->paso    = $paso;
        }else if($paso != null){
            die('Paso Incorrecta.');
        }
        return $this;
    }
    
    /**
     * Procesos::set_variables()
     * 
     * @param int $tarea
     * @param int $paso
     * @param int $opcion
     * @return object
     */
    public function set_variables($tarea = null, $paso = null, $opcion = null)
    {
        $this->set_tarea($tarea)->set_paso($paso)->set_opcion($opcion);
        $this->pre_run();
        return $this;
    }
    
    public function get_variables($objetos = true)
    {
        //Carga de datos
        $this->pre_run();
        
        if($objetos){
            return (object) array(
                'tarea' => $this->tarea,
                'paso'  => $this->paso,
                'opcion'  => $this->opcion
            );
        }else{
            return (object) array(
                'tarea' => $this->tarea->tar_nombre,
                'paso'  => $this->paso->pas_nombre,
                'opcion'  => $this->opcion->opc_nombre
            );
        } 
    }

    /**
     * Procesos::validar()
     * 
     * @return void
     */
    public function validar()
    {
        //Validamos si esta logeado.
        if (!$this->ci->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
        
        //Carga de datos
        $this->pre_run();
        
        //print_r($this);
        
        //nos aseguramos se a la opcion cargada
        if(
            //nos aseguramos que tenemso todos loa parametros
            //$this->permiso == null ||
            $this->paso == null || $this->opcion == null || $this->paso_detalle == null ||
            $this->opcion->opc_id != $this->paso_detalle->oxp_id_opc 
            || !$this->validar_permiso('Ver')){
            //TODO: Reenviar a  principal y decir que no tiene permisos.
            $alerta = array(
                'tipo_alerta'=> 'note_error',
                'titulo_alerta'=>"Sección Restringida.",
                'texto_alerta'=>"La seccion que intenta acceder no esta permitido para este rol.");
            $this->ci->session->set_flashdata($alerta);
            redirect();
        }
    }
    
    /**
     * Procesos::validar_user()
     * 
     * @param int $opcion
     * @return void
     */
    public function validar_user($opcion = null)
    {
        //Validamos si esta logeado.
        if (!$this->ci->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
        
        //set_opcion 
        $this->set_opcion($opcion);
        
        //pre configuramos
        $this->set_default_Model($this);
        $this->get_all_permisos();
        
        $this->usuario  = $this->ci->users->get_user_by_id($this->ci->tank_auth->get_user_id(),true);
        $this->get_roles_by_user();
        $this->get_opcion();
        
        //los roles para ese usuario
        $oxr = $this->driver->get_roles_by_opcion($this->opcion->opc_id);
        
        foreach($this->roles as $u_rol){
            foreach($oxr as $o_rol){
                if($u_rol->rol_id == $o_rol->rol_id){
                    return true;
                }
            }
        }
        
        //TODO: Reenviar a  principal y decir que no tiene permisos.
        $alerta = array(
            'tipo_alerta'=> 'note_error',
            'titulo_alerta'=>"Sección Restringida.",
            'texto_alerta'=>"La seccion que intenta acceder no esta permitido para este rol.");
        $this->ci->session->set_flashdata($alerta);
        redirect();
        //die('Sin Permisos');
    }
    
    /**
     * Procesos::get_permisos()
     * Obtine los permisos que existen para determinado paso.
     * 
     * @return Object
     */
    public function get_permisos()
    {
        //Carga de datos
        $this->pre_run();
        
        $permisos = array();
        //Todos los permisos como falso
        foreach($this->permisos as $row)
        {
            $permisos[$row->per_nombre] = false;
        }
        //Validamos que tenga permisos
        if($this->permiso != null){
            //Todos los permisos asignados al paso
            foreach($this->permiso as $row)
            {
                $permisos[$row->per_nombre] = true;
            }
        }
        
        return (Object) $permisos;
    }
    
    
    /**
     * Procesos::get_permiso()
     * Retorna Si se cuenta con el actual permiso.
     * Ver, Crear, Editar, Eliminar, Aceptar, 
     * Denegar, Pdf, Excel, Imprimir
     * 
     * @param string $permiso
     * @return bool
     */
    public function validar_permiso($permiso)
    {
        foreach($this->get_permisos() as $row => $value)
        {
            //if($permiso == 'Crear') echo "$permiso $row $value |" . ($permiso == $row) . '-'. ($permiso == $row && $value) .'<br />';
            if($permiso == $row && $value){
                return true;
            }
        }
        return false;
    }
    
    /**
     * Procesos::paso_terminal()
     * 
     * @param int $id
     * @param int $paso
     * @return void
     */
    function paso_terminal($id, $paso, $obs = '')
    {
        if($id && is_numeric($id) && $paso && is_numeric($paso))
        {
            return $this->set_next_estado($id, $paso, $obs);
        }
        return false;
    }
    
    function print_validar($paso, $data)
    {
        //variables post
        $registro = $this->ci->input->post('registro');
        $obs      = $this->ci->input->post('obs');
        $aceptado = $this->ci->input->post('aceptado');
        
        //validacion
        if(!is_numeric($registro) && !is_string($obs) && ( $aceptado == 'yes' || $aceptado == 'no' )){
            die('Error: Verifique Valores.');
        }
        
        if($registro && $obs && $aceptado){
            if($this->set_next_estado($registro, $paso, $obs, ($aceptado == 'yes')) ){
                die('Ok');
            }
            die('Error: Imposible Guardar en Base de datos.');
        }
        
        if($data['registro'] = $registro){
            die($this->ci->load->view('procesos/validar',$data, true));
        }else{
            die('Error: Sin Registro');
        }
    }
    
    /**
     * Procesos::get_trayecto()
     * 
     * @param mixed $id
     * @return
     */
    function get_trayecto($id)
    {
        //Carga de datos
        $this->pre_run();
        
        return $this->driver->get_trayecto($this->tarea,$id);
    }
    
    /**
     * Procesos::get_estado()
     * 
     * @param mixed $id
     * @return result
     */
    function get_estado($id)
    {
        //Carga de datos
        $this->pre_run();
        
        if($id > 0){
            return $this->driver->get_estado($this->tarea, $id);
        }
        return null;
    }
    
    function get_paso_actual($id)
    {
        if($id > 0){
            $tra =  $this->driver->get_trayecto_actual($this->tarea,$id);
            $act = $this->driver->get_actual_estado($this->tarea, $id);
            if($tra != null && $tra->try_aceptado == '3'){
                //print_r($act); die();
                //echo "RECHA:$id ";
                
                //si tiene fin
                if($act->pas_validar){
                    return $this->driver->get_paso_by_id($act->pas_validar);
                }else{
                    $bef = $this->driver->get_before_paso($this->tarea, $act);
                    return $bef;
                }
            }else if($tra != null && $tra->try_aceptado == '2'){
                return $act;
            }
        }
        return null;
    }
    
    function get_historico($tarea = null, $registro =  null)
    {
        //Validamos si esta logeado.
        if (!$this->ci->tank_auth->is_logged_in()) {
            redirect('/auth/login/');
        }
        
        //Configuramos la tarea a ejecutar
        $this->set_tarea($tarea);
        
        //Cargamos la infraestructura
        $this->set_default_Model($this);
        $this->get_tarea_by_opcion();
        $this->get_pasos($this->tarea);
        $this->get_tabla($this->tarea);
        
        return $this->driver->get_historico($this->tarea, $registro);
        
    }
    
    function print_historico($tarea, $registro)
    {
        
    }
    
    /**
     * Procesos::set_next_estado()
     * Asigna el siguente Paso.
     * 
     * @param int $id
     * @param int $paso
     * @param string $obs
     * @param bool $aceptado
     * @return
     */
    function set_next_estado($id, $paso = null, $obs =  '', $aceptado =  true)
    {
        //nos aseguramos que se inicialice el sistema.
        $this->validar();
        
        return $this->driver->next_estado($this->tarea, $this->usuario, $id, $paso, $obs, $aceptado);
    }
    
    /**
     * Procesos::get_registros_by_paso()
     * 
     * @param int $paso
     * @return result
     */
    function get_registros_by_paso($paso_id)
    {
        //Carga de datos
        $this->pre_run();
         
        if($paso_id > 0){
            $paso = $this->driver->get_paso_by_id($paso_id);
            if(count($paso) > 0){
                return $this->driver->get_registro_by_paso($this->tarea, $paso);
            }
        }
        return null;
    }
}

/* End of file Someclass.php */