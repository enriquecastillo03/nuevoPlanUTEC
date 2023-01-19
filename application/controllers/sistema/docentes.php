<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Docentes extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model('sistema');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {

			try
			{
				$crud = new grocery_CRUD();
				$crud->set_table('docentes');
				$crud->set_subject('docentes');
				$crud->set_relation("titulo","titulos","titulo");
				$crud->set_relation("idCategoria","categorias","cate");
				$crud->set_relation("idMaestria","docentemaestrias","TipoPago");
				
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