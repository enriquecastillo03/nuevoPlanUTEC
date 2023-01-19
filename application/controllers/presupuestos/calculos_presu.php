<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Calculos_presu extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    require_once  './PHPExcel/Classes/PHPExcel.php';
include './PHPExcel/Classes/PHPExcel/IOFactory.php';
$this->load->library('grocery_CRUD');
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
    
		/*	$data=$this->masterpage->getUsuario();
			$this->masterpage->setMasterPage('masterpage_default');
			$this->masterpage->addContentPage('presu_guardados_v', 'content',$data);
			$this->masterpage->show();
		*/


			$crud = new grocery_CRUD();
				$crud->set_table('presupuestohorasclase');
				$crud->set_subject('presupuestohorasclase');
				 $crud->unset_add();
				 $crud->set_relation("idEscuela","facultad","nombre");
				 $crud->set_relation("idUsuario","users","nombres_apellidos");
				 $crud->display_as("idEscuela", "Escuela");
				 $crud->display_as("idUsuario", "Usuario");
				 $crud->display_as("IdCalendarioAcademico", "Año");
				//comnetariado porq este usuario era todo el analisis
				// $crud->where("idUsuario",$this->session->userdata("user_id"));
				//	$crud->add_action('Modificar', '', '','ui-icon-image',array($this,'just_a_test'));
                $texto   = "Calcular";
				$funcion ="presupuestos/calculos_presu/presu_view";
				$texto1   = "Detalle";
				$funcion1 ="presupuestos/calculos_presu/presu_view";

				$output = $crud->render($texto, $funcion,$texto1, $funcion1);
				$this->_cargarvista(null,$output);

		}
	}

	function just_a_test($primary_key , $row)
{
    return site_url('presupuestos/presu_guardados/modificar_presu').'?presu='.$row->idPresupuestoHorasClase;
}



 public function modificar_presu(){


if($this->input->get("presu")){
$presu = $this->input->get("presu");


  }else{
 	$url = $_SERVER['REQUEST_URI'];

 	$url = explode("/", $url); 

    $url = end($url);

 $data = $this->db->query("select * from presupuestohorasclased where id_p=".$url)->row_array();

 $presu= $data['idPresupuestoHorasClase'];
}

  // echo $presu; 


   $crud = new grocery_CRUD();
				 $crud->set_table('presupuestohorasclased');
				$crud->set_subject('presupuestohorasclased');
				 $crud->unset_add();
				 $crud->set_relation("idDocente","docentes","{nombres} {apellidos}");
				 $crud->set_relation("idAsignatura","catedras","nombre");
				 $crud->set_relation("idHorario","horario","{HorarioTexto}-{DiasTexto}");
				 $crud->where("idPresupuestoHorasClase",$presu);
				 $crud->display_as("idAsignatura", "Asignatura");
				 $crud->display_as("idSeccion", "Seccion");
				 $crud->display_as("idDocente", "Docente");
				  $crud->display_as("idHorario", "Horario");
				$crud->unset_columns("idPresupuestoHorasClase", "Item");

                 $crud->unset_edit_fields('idPresupuestoHorasClase','Item');

				$output = $crud->render();
				$this->_cargarvista(null,$output);

  }





  public function presu_view(){
   $presupuesto = $this->input->post("valor");
   $iteraciones= $this->db->query("SELECT COUNT(*) AS iteraciones FROM presupuestohorasclase pre
								JOIN ciclossemanas cic ON cic.`idciclo` = pre.`Ciclo`
								WHERE pre.`idPresupuestoHorasClase` =".$presupuesto)->row_array();
   $iteraciones = $iteraciones['iteraciones'];

   $info_presupuesto = $this->db->query("
SELECT  *, cat.`nombre` AS asignatura FROM presupuestohorasclase AS pre
JOIN presupuestohorasclased det ON det.`idPresupuestoHorasClase` = pre.`idPresupuestoHorasClase`
JOIN ciclossemanas ci ON ci.`idciclo` = pre.`Ciclo`
JOIN horario hor ON hor.`idHorario` = det.`idHorario`
JOIN catedras cat ON cat.`id` = det.`idAsignatura`
JOIN docentes doc ON doc.`id` = det.`idDocente`
WHERE pre.`idPresupuestoHorasClase`=".$presupuesto)->result_array();

    $data =array(
      "data" => $info_presupuesto,
      "total_iteraciones_rows" => $iteraciones
      );


  $this->load->view("presu_calculo",$data);


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