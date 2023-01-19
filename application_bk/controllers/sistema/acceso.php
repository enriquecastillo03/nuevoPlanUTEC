<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Acceso extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {			
			$this->_cargarvista();
		}
	}

	function roles()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				
				$crud = new grocery_CRUD();
				$crud->set_table('rol_rol');
				$crud->set_subject('Roles');
				$crud->columns('rol_nombre','rol_descripcion');
				$output = $crud->render();
				$this->_cargarvista(null,$output);
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		}
	}

	function permisos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {	
			$data['rol']=$this->sistema->cargar_tabla('rol_rol');
			$data['opc']=$this->sistema->cargar_menus0();			
			$this->_cargarvista($data);
		}
	}

	function opciones()
	{
		$data['oxr']=$this->sistema->cargar_opciones($_POST['rol']);
		$data['nivel']=$_POST['opc'];
		$data['opciones']=$this->sistema->cargar_tabla('opc_opcion');
		$this->load->view('sistema/acceso/opciones',$data);
	}

	function addopc()
	{
		$this->sistema->add_opc($_POST['rol'],$_POST['opc']);
	}

	function delopc()
	{
		$this->sistema->del_opc($_POST['rol'],$_POST['opc']);
	}

	function usuarios()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_CRUD();
				$crud->set_table('users');
				$crud->set_subject('Usuarios');
				$crud->columns('username','email','activated','rol');
                $crud->display_as('username','Nombre de Usuario')
                    ->display_as('email','Correo')
                    ->display_as('activated','Activo')
                    ->display_as('rol','Rol')
                    ->display_as('username','Nombre de Usuario')
                    ->display_as('username','Nombre de Usuario')
                ;
                
                $crud->set_relation_n_n('rol','uxr_usuarioxrol','rol_rol','uxr_id_usu','uxr_id_rol','rol_nombre');
                
                $crud->add_fields('username','email','activated','rol');
                $crud->unset_delete();
                $crud->edit_fields('email','activated','rol');
				$output = $crud->render();
				$this->_cargarvista(null,$output);
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		}
	}

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