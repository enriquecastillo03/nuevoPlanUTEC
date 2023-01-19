<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Feriados extends CI_Controller
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

				$this->_cargarvista();
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		
		}
	}
	function add_feriado(){
     $fecha_inicio = $this->input->post("fecha_inicio");
     $descripcion = $this->input->post("descripcion");
     $array_feriado= array(
       "fecha_inicio" => $fecha_inicio,
       "descripcion"   => $descripcion

     	);
     $this->db->insert("feriados", $array_feriado);
     $this->load->view("sistema/feriados/index");  

	}  

	function edit_feriado(){
		$id = $this->input->post("id");
		$dias = $this->input->post("dias");
		echo "id:".$id."luego...".$dias; 
		$fecha= $this->db->query("SELECT DATE_ADD(fecha_inicio, INTERVAL ".$dias." DAY) AS nueva_fecha FROM feriados WHERE id =".$id)->row_array();
        $nueva_fecha = $fecha["nueva_fecha"];
        $this->db->query("update feriados set fecha_inicio ='".$nueva_fecha."' where id =".$id);
        echo 1; 
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