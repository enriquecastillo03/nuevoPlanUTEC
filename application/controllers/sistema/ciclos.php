<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ciclos extends CI_Controller
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
				$crud->set_table('ciclos');
				$crud->set_subject('Ciclos');
				$crud->callback_after_insert(array($this, 'calcular_semanas'));
				$texto    = "ver semanas"; 
				$funcion  =  "sistema/ciclos/ver_semana";

				$output = $crud->render($texto, $funcion);

				$this->_cargarvista(null,$output);
			}
			catch(Exception $e)
			{
				show_error($e->getMessage().' --- '.$e->getTraceAsString());
			}
			
		
		}
	}


	function calcular_semanas($post_array,$primary_key)
{

 date_default_timezone_set('UTC');

	 $fecha_inicio = $this->conversion($post_array['fecha_inicio']); 
	 $fecha_fin    = $this->conversion($post_array['fecha_fin']); 


 $fecha1 = explode("/", $post_array['fecha_inicio']);
 $diadelasemana = $this->diaSemana($fecha1[2],$fecha1[1],$fecha1[0]);
 //echo $diadelasemana; 
 $cant_dias_operar = 7-$diadelasemana;


$date1 = str_replace("/","-",$post_array['fecha_inicio']);
$fecha_convert=  date('Y-m-d', strtotime($date1));



 $nuevafecha = date('Y-m-d', strtotime($fecha_convert) + (86400*($cant_dias_operar) )); 



$array_semanas_first= array(
   "idciclo" =>    $primary_key,
   "fechainicial" =>  $fecha1[2]."-".$fecha1[1]."-".$fecha1[0],
   "fechafinal"  =>  $nuevafecha

	);

 $this->db->insert("ciclossemanas", $array_semanas_first);
//echo $nuevafecha;


$fechaInicio=strtotime($nuevafecha);
$fechaFin=strtotime($fecha_fin );
$array_ini_fin = array(); 
for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    $fecha_bucle =  date("Y-m-d", $i);
    $fecha_buc = explode("-", $fecha_bucle); 
  
    if($this->diaSemana($fecha_buc[0],$fecha_buc[1],$fecha_buc[2]) == 1){
         
      $lunes = $fecha_bucle;
      $domingo = date('Y-m-d', strtotime($lunes) + (86400*6 )); 

      echo "fecha lunes: ".$lunes."<br>";
      echo "fecha Domingo: ".$domingo."<br>";

     $array_semanas = array(
      "idciclo" =>     $primary_key,
      "fechainicial" => $lunes,
      "fechafinal" =>   $domingo

     	);

  $this->db->insert("ciclossemanas", $array_semanas);

      }


}




 

    return true;
}


function ver_semana(){
	$valor = $this->input->post("valor");
	$data = $this->db->query("select * from ciclossemanas where idciclo=".$valor)->result_array();
    $data_array = array(
      "data"   => $data

    	); 
	$this->load->view("ver_semana", $data_array);
}

function diaSemana($ano,$mes,$dia)
{
	// 0->domingo	 | 6->sabado
	$dia= date("w",mktime(0, 0, 0, $mes, $dia, $ano));
		return $dia;
}

  public function conversion($fecha){

    $fecha = explode("/", $fecha);
    $nueva_fecha = $fecha[2].'-'.$fecha[1].'-'.$fecha[0];
    return $nueva_fecha; 

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