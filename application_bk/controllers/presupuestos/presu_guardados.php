<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Presu_guardados extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    require_once  './PHPExcel/Classes/PHPExcel.php';
include './PHPExcel/Classes/PHPExcel/IOFactory.php';
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
    
			$data=$this->masterpage->getUsuario();
			$this->masterpage->setMasterPage('masterpage_default');
			$this->masterpage->addContentPage('presu_guardados_v', 'content',$data);
			$this->masterpage->show();
		}
	}

  public function presu_view(){
   $presupuesto = $this->input->post("presupuesto");
   $info_presupuesto = $this->db->query("select * from detalle_presupuesto where id_presupuesto=".$presupuesto)->result_array();
    $data =array(
      "info_presupuesto" => $info_presupuesto
      );
$this->load->view("presu_view",$data);


  }
 function delete_presu($id_presu){
 	  $this->db->query("delete from detalle_presupuesto where id_presupuesto =".$id_presu);
  
      $this->db->query("delete from presupuestos where id =".$id_presu);
  
      $this->index(); 

 }
	
public function _cargarvista( $data=0, $crud=0 )
	{	
		$this->load->view('vacia',$crud);	

		if( $data != 0 )
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