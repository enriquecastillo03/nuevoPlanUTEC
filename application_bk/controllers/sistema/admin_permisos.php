<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_permisos extends CI_Controller
{
	/**
	 * Alerta::__construct()
	 * 
	 * @return
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
        $this->load->library('Procesos');
	}

	/**
	 * Alerta::index()
	 * 
	 * @return
	 */
	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {			
			$this->_cargarvista();
		}
	}
    
    function tareas(){
        if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
        else 
        {
			try
			{
				$crud = new grocery_CRUD();
				$crud->set_table('tar_tarea');
				$crud->set_subject('Proceso');
                $crud->display_as('tar_nombre','Nombre')
                    ->display_as('tar_id_tab','Tabla')
                    ->display_as('tar_duracion','Duración')
                ;//*/
                $crud->set_relation('tar_id_tab','tab_tabla','tab_nombre');
                $crud->set_rules('tar_nombre','Nombre','required|alpha');
                $crud->set_rules('tar_duracion','Duración','required|numeric');
                
                $crud->add_action('Agregar Flujo','','','',function($valor){
                    return base_url() . 'sistema/admin_permisos/gestion/' . $valor;
                });
                $crud->add_action('Agregar Pasos','','','',function($valor){
                    
                    return base_url() . 'sistema/admin_permisos/pasos/' . $valor;
                });
                
                $crud->columns('tar_nombre', 'tar_id_tab', 'tar_duracion');
                
                $crud->unset_delete();
				$this->_cargarvista(null,$crud->render());
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		}
    }
    
    function pasos($tarea = null){
        if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
        else 
        {
			try
			{
				$crud = new grocery_CRUD();
				$crud->set_table('pas_paso');
				$crud->set_subject('Paso');
                $crud->display_as('pas_id_tar','Proceso')
                    ->display_as('pas_nombre','Nombre')
                    ->display_as('pas_orden','Orden')
                    ->display_as('pas_duracion','Duracion')
                    ->display_as('pas_validar','Validador')
                ;
                if($tarea && is_numeric($tarea)){
                    $crud->where('pas_id_tar',$tarea);
                }
                $crud->set_relation('pas_id_tar','tar_tarea','tar_nombre', ($tarea && is_numeric($tarea))?array('tar_id' => $tarea):null);
                //$crud->set_relation('pas_validar','pas_paso','pas_nombre');
                $crud->required_fields('pas_id_tar', 'pas_nombre', 'pas_orden', 'pas_duracion', 'pas_validar');
                $crud->set_rules('pas_orden','required|muneric');
                $crud->set_rules('pas_validar','required|muneric');
                $crud->order_by('pas_id_tar, pas_orden');
				$this->_cargarvista(null,$crud->render());
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		}
    }
    
    function gestion($tarea = null){
        if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		}
        else 
        {
			try
			{
			 
                //$per = new Procesos();
                //$per
                    //->set_tarea('cheques')
                    //->set_opcion('26')
                    //->set_paso(1)
                ;
                
                //response = $per->validar();
                
                //print_r($per);
                //print_r($per->get_permisos());
             
				$crud = new grocery_CRUD();
				$crud->set_table('oxp_opcionxpaso');
				$crud->set_subject('Flujo');
				$crud->columns('oxp_id_pas','per','oxp_id_rol','oxp_id_opc');
                $crud->display_as('oxp_id_pas','Paso')
                    ->display_as('per','Permisos')
                    ->display_as('oxp_id_rol','Rol')
                    ->display_as('oxp_id_opc','Vista (Menu)')
                ;
                if($tarea && is_numeric($tarea)){
                    $crud->where('pas_id_tar',$tarea);
                }
                $crud->set_relation_n_n('per','oxx_opcionxpermiso','per_permiso','oxx_id_oxp','oxx_id_per','per_nombre');
                $crud->set_relation('oxp_id_pas','pas_paso','({pas_id_tar}-{pas_orden}) {pas_nombre}',($tarea && is_numeric($tarea))?array('pas_id_tar' => $tarea):null,'pas_id_tar, pas_orden');
                $crud->set_relation('oxp_id_rol','rol_rol','rol_nombre');
                $crud->set_primary_key('opc_id','vw_opc')->set_relation('oxp_id_opc','vw_opc','({opc_id}) {opc_nombre}');
                $crud->order_by('oxp_id_pas');
                $crud->add_fields('oxp_id_pas','per','oxp_id_rol','oxp_id_opc');
                $crud->edit_fields('oxp_id_pas','per','oxp_id_rol','oxp_id_opc');
				$this->_cargarvista(null,$crud->render());
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		}
    }

	/**
	 * Alerta::_cargarvista()
	 * 
	 * @param integer $data
	 * @param integer $crud
	 * @return
	 */
	function _cargarvista($data=0,$crud=0)
	{	
		$this->load->view('vacia',$crud);	
		if($data!=0)
			$data=array_merge($data,$this->masterpage->getUsuario());
		else
			$data=$this->masterpage->getUsuario();		
		$vista=$data['modulo'].'/'.$data['control'].'/'.$data['funcion'];
		$this->masterpage->setMasterPage('masterpage_default');
		$this->masterpage->addContentPage($vista, 'content',$data);
		$this->masterpage->show();
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */