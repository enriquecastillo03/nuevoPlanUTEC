<?php if (!defined('basepath')) exit('no direct script access allowed');

class Catedras extends ci_controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_crud');
	}

	

	
	function uso_inmueble()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('uso_uso');
				$crud->set_subject('uso de inmuebles');
				$crud->columns('uso_descripcion');
				$crud->display_as('descripcion');
				 $crud->required_fields('uso_descripcion');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function ubicacion()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ubi_ubicacion');
				$crud->set_subject('zonas de inmuebles');
				$crud->columns('ubi_zona', 'ubi_descripcion');
				$crud->display_as('zona', 'descripcion');
				 $crud->required_fields('ubi_zona','ubi_descripcion');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function tipo_documento()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('tid_tipo_documento');
				$crud->set_subject('documentos');
				$crud->columns('tid_nombre','tid_validez_ini','tid_validez_fin','tid_descripcion');
				$crud->display_as('tid_nombre','documento');
				$crud->display_as('tid_validez_ini','inicio de validez');
				$crud->display_as('tid_validez_fin','fin de validez');
				$crud->display_as('tid_descripcion','descripcion');
				 $crud->required_fields('tid_nombre','tid_validez_ini','tid_validez_fin');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function tipo_telefono()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('tit_tipo_telefono');
				$crud->set_subject('tipos de telefonos');
				$crud->columns('tit_nombre');
				$crud->display_as('tipo de telefono');
				 $crud->required_fields('tit_nombre');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function licencias()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('til_tipo_licencia');
				$crud->set_subject('uso de inmuebles');
				$crud->columns('til_nombre','til_descripcion','til_estado');
				$crud->display_as('licencia','descripcion', 'estado');
				 $crud->required_fields('til_nombre','til_estado');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}
/////////////////////////////////////////////////////////////////////////////////

	
	function solicitudes()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('tis_tipo_solicitud');
				$crud->set_subject('solicitudes');
				$crud->columns('tis_nombre', 'tis_descripcion');
				$crud->display_as('tis_nombre','solicitud','tis_descripcion', 'descripcion');
				 $crud->required_fields('tis_nombre','solicitud','tis_descripcion', 'descripcion');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function giro()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('gir_giro');
				$crud->set_subject('giro');
				$crud->columns('gir_id_ace', 'gir_nombre');
				$crud->display_as('gir_id_ace', 'actividad economica', 'gir_nombre', 'giro');
				 $crud->required_fields('gir_id_ace', 'gir_nombre');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function actividad_economica()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ace_actividad_economica');
				$crud->set_subject('actividad economica');
				$crud->columns('ace_nombre');
				$crud->display_as('ace_nombre','actividad economica');
				
				 $crud->required_fields('ace_nombre');
				$output = $crud->render();				
				$this->_cargarvista(null,$output);
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}

   
    
	function servicios()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables')
				
				$crud->set_table('srv_servicio');
				$crud->set_subject('servicios');
				$crud->columns('srv_nombre', 'srv_descripcion', 'srv_unidad_medida', 'srv_tiempo_corte');
				$crud->display_as('srv_nombre', 'servicio');
				$crud->display_as('srv_descripcion', 'descripcion');
				$crud->display_as('srv_unidad_medida', 'medida');
				$crud->display_as('srv_tiempo_corte', 'tiempo corte');
				 $crud->required_fields('srv_nombre', 'srv_descripcion', 'srv_unidad_medida', 'srv_tiempo_corte');
				 //esta estructura debe indicarse atendiendo lo siguiente: carpeta, controller, function 
				
				 $funcion_opcional= "uatm/opcional/opcion";
                 $texto_opcion= "ver tarifas";
				$output = $crud->render($texto_opcion, $funcion_opcional);			
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////
	
function gestion_personas()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('per_persona');
				$crud->set_subject('tipos de telefonos');
				$crud->columns('per_nac_id', 'per_gen_id', 'per_dep_id_origen', 'per_mun_id_domicilio', 'per_direccion', 'per_pro_id','per_nombre1', 'per_nombre2', 'per_apellido1', 'per_apellido2' );
				$crud->display_as('per_nac_id', 'nacionalidad');
				$crud->display_as('per_gen_id', 'genero');
				$crud->display_as('per_dep_id_origen', 'departamento de origen');
				$crud->display_as('per_mun_id_domicilio', 'municipio corte');
				$crud->display_as('per_direccion', 'direccion');
				$crud->display_as('per_pro_id', 'profesion');
				$crud->display_as('per_nombre1', 'nombre 1');
				$crud->display_as('per_nombre2', 'nombre 2');
				$crud->display_as('per_apellido1', 'apellido1');
				$crud->display_as('per_apellido2', 'apellido 2');
				$crud->set_relation('per_mun_id_domicilio','mun_municipio','mun_nombre');
				$crud->set_relation('per_mun_id_nacimiento','mun_municipio','mun_nombre');
				$crud->set_relation('per_pro_id','pro_profesion','pro_nombre');
				$crud->set_relation('per_gen_id','gen_genero','gen_nombre');
				$crud->set_relation('per_dep_id_origen','dep_departamento','dep_nombre');
				$crud->set_relation('per_gen_id','gen_genero','gen_nombre');
				$crud->set_relation('per_nac_id','nac_nacionalidad','nac_nombre');
				 //$crud->required_fields('srv_nombre', 'srv_descripcion', 'srv_unidad_medida', 'srv_tiempo_corte');
				$output = $crud->render();			
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}

function estado_solicitudes()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ess_estado_solicitud');
				$crud->set_subject('estado de solicitudes');
				$crud->columns('ess_nombre');
				$crud->display_as('ess_nombre', 'estado');

				$output = $crud->render();			
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}

function gestion_inmuebles()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ixp_inmueblexpersona');
				$crud->set_subject('gestion de inmuebles');
				$crud->columns('ixp_id_per', 'ixp_id_inm');
				$crud->display_as('ixp_id_per', 'propietario');
				$crud->display_as('ixp_id_inm', 'inmueble');		
				$crud->set_relation('ixp_id_per','per_persona','per_nombre1');
				$crud->set_relation('ixp_id_inm','inm_inmueble','inm_direccion');
				$crud->required_fields('ixp_id_per', 'ixp_id_inm');
				$output = $crud->render();			
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


  function cierre_cuenta_general()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ixp_inmueblexpersona')
				->where("ixp_estado",3);
				$crud->set_subject('cierre de cuentas');
				$crud->columns('ixp_id_per', 'ixp_id_inm');
				$crud->display_as('ixp_id_per', 'propietario');
				$crud->display_as('ixp_id_inm', 'inmueble');		
				$crud->set_relation('ixp_id_per','per_persona','per_nombre1');
				$crud->set_relation('ixp_id_inm','inm_inmueble','inm_direccion');
				$crud->required_fields('ixp_id_per', 'ixp_id_inm');
				 $funcion_opcional= "uatm/opcional/solicitud_cierre";
                 $texto_opcion= "aceptar";
                 $funcion_opcional1= "uatm/opcional/solicitud_cierre_denegada";
                 $texto_opcion1= "denegar";
				$output = $crud->render($texto_opcion, $funcion_opcional, $texto_opcion1, $funcion_opcional1);	
		
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}


	function cierre_cuenta_general_aceptada()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ixp_inmueblexpersona')
				->where("ixp_estado",2);
				$crud->set_subject('cierre de cuentas');
				$crud->columns('ixp_id_per', 'ixp_id_inm');
				$crud->display_as('ixp_id_per', 'propietario');
				$crud->display_as('ixp_id_inm', 'inmueble');		
				$crud->set_relation('ixp_id_per','per_persona','per_nombre1');
				$crud->set_relation('ixp_id_inm','inm_inmueble','inm_direccion');
				$crud->required_fields('ixp_id_per', 'ixp_id_inm');
				 $funcion_opcional= "uatm/opcional/retorno_proceso";
                 $texto_opcion= "volver a proceso";
				$output = $crud->render($texto_opcion, $funcion_opcional);	
		
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}
function cierre_cuenta_general_denegada()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('ixp_inmueblexpersona')
				->where("ixp_estado",4);
				$crud->set_subject('cierre de cuentas');
				$crud->columns('ixp_id_per', 'ixp_id_inm');
				$crud->display_as('ixp_id_per', 'propietario');
				$crud->display_as('ixp_id_inm', 'inmueble');		
				$crud->set_relation('ixp_id_per','per_persona','per_nombre1');
				$crud->set_relation('ixp_id_inm','inm_inmueble','inm_direccion');
				$crud->required_fields('ixp_id_per', 'ixp_id_inm');
				 $funcion_opcional= "uatm/opcional/retorno_proceso";
                 $texto_opcion= "volver a proceso";
				$output = $crud->render($texto_opcion, $funcion_opcional);	
		
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}
function tipo_rotulos()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('tir_tipo_rotulo');
				
				$crud->set_subject('rotulos');
				$crud->columns('tir_nombre', 'tir_descripcion');
				$crud->display_as('tir_nombre', 'rotulo');
				$crud->display_as('tir_descripcion', 'descripcion');			
				$crud->required_fields('tir_nombre');
				
				$output = $crud->render();	
		
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}

/*
function servicios()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			try
			{
				$crud = new grocery_crud();
				//$crud->set_theme('datatables');
				$crud->set_table('srv_servicio');
				$crud->set_subject('tipos de telefonos');
				$crud->columns('srv_nombre', 'srv_descripcion', 'srv_unidad_medida', 'srv_tiempo_corte');
				$crud->display_as('srv_nombre', 'servicio');
				$crud->display_as('srv_descripcion', 'descripcion');
				$crud->display_as('srv_unidad_medida', 'medida');
				$crud->display_as('srv_tiempo_corte', 'tiempo corte');
				 $crud->required_fields('srv_nombre', 'srv_descripcion', 'srv_unidad_medida', 'srv_tiempo_corte');
				$output = $crud->render();			
				$this->_cargarvista(null,$output);
				
			}
			catch(exception $e)
			{
				show_error($e->getmessage().' --- '.$e->gettraceasstring());
			}
			
		}
	}
	*/
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	function _cargarvista($data=0,$crud=0)
	{	
		$this->load->view('vacia',$crud);	
		if($data!=0)
			$data=array_merge($data,$this->masterpage->getusuario());
		else
			$data=$this->masterpage->getusuario();
		$vista=$data['modulo'].'/'.$data['control'].'/'.$data['funcion'];
		$this->masterpage->setmasterpage('masterpage_default');
		$this->masterpage->addcontentpage($vista, 'content',$data);
		$this->masterpage->show();
	}






}

/* end of file welcome.php */
/* location: ./application/controllers/welcome.php */

