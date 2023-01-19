<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('ref_model');
	}

function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {	
			//$data['anuncios']=$this->ref_model->get_tabla('anu_anuncio',array('anu_estado'=>1));
			$this->_cargarvista();			
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