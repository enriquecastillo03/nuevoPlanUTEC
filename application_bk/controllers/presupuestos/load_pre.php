<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Load_pre extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    require_once  './PHPExcel/Classes/PHPExcel.php';
include './PHPExcel/Classes/PHPExcel/IOFactory.php';
	}

	function index($presu = null )
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
    
			//echo $this->uri->segment(3);
			$data=$this->masterpage->getUsuario();
        if($presu != null){
       
        $data["presupuesto_guardado"] = $presu; 
      }
			$this->masterpage->setMasterPage('masterpage_default');
			$this->masterpage->addContentPage('presupuestos/crear_presupuesto', 'content',$data);
			$this->masterpage->show();
		}
	}

	public function upload_file(){ 

      //Config the parameters to upload the file to the server.
      //Configuramos los parametros para subir el archivo al servidor.               
      $config['upload_path'] = "files/";

      $config['allowed_types'] = 'xlsx';
      $config['max_size']     = '0';                 

      //Load the Upload CI library
      //Cargamos la libreria CI para Subir
     $this->load->library('upload', $config);

  
     if ( ! $this->upload->do_upload('file') ){      
     
          //Displaying Errors.
           //Mostramos los errores.
           print_r($this->upload->display_errors());                                              
     }
     else{
           //Uploads the excel file and read it with the PHPExcel Library.
           //Subimos el archivo de excel y lo leemos con la libreria PHPExcel.
          $data = array('upload_data' => $this->upload->data());                 
          $this->load->library('excel');
           //$excel = $this->excel->read_file($data['upload_data']['file_name']);
     }              
            

             
     //The file stored in the server will be deleted, we don't need it anymore.
     //El archivo almacenado en el servidor sera eliminado, no lo necesitamos mas.
     //unlink($config['upload_path'].'/'.$data['upload_data']['file_name']);                  
                
            $nueva_direccion = str_replace("/application/controllers/", "/", __FILE__);
            $nueva_direccion = str_replace('/presupuestos/load_pre.php', "/files/".$data['upload_data']['file_name'], $nueva_direccion);

//            echo $nueva_direccion;

  //          exit(); 

       
                $objReader = PHPExcel_IOFactory::createReader('Excel2007');

$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($nueva_direccion);
$objWorksheet = $objPHPExcel->setActiveSheetIndex(0)->toArray(null,true,true,true);

//objWorksheet = $objPHPExcel->getActiveSheet();


     //Set the array result from the library function and pass it to the view.
     //Asignamos el arreglo resultante de la funcion de la libreria y lo pasamos a la vista.
//obtener todas las catedras para rnederizarls en la view
$facultad = $this->db->query("select * from facultad")->result_array(); 
     $data['objWorksheet'] = $objWorksheet;
      $data['facultad'] = $facultad;
  
     $this->_cargarvista($data);      
     }



public function obtener_carreras(){


$facu = $this->input->post("facu");
$carreras = $this->db->query("SELECT carr.`nombre` AS nombre_carrera, carr.id as id_carrera FROM facultad AS facu
INNER JOIN facu_x_carre fxc ON fxc.id_facu = facu.`id`
INNER JOIN carreras carr ON carr.`id` = fxc.id_carre
WHERE facu.`id` =".$facu)->result_array();

$select =""; 
foreach ($carreras as $key) {
$select .= "<option value=".$key["id_carrera"].">".$key["nombre_carrera"]."</option>";
}


echo $select; 


}

public function guardar_presupuesto_master(){
    $nombre = $this->input->post("nombre_presupuesto");
    $carrera = $this->input->post("carreras");
    $anio = $this->input->post("anio");
    $ciclo = $this->input->post("ciclo");
    $contenido_arreglo_a  = unserialize($this->input->post("contenido_arreglo_a")); // recuperarla;
   $contenido_arreglo_b   = unserialize($this->input->post("contenido_arreglo_b")); // recuperarla;
  $contenido_arreglo_c  = unserialize($this->input->post("contenido_arreglo_c")); // recuperarla;

  $contenido_arreglo_d  = unserialize($this->input->post("contenido_arreglo_d")); // recuperarla;

  $contenido_arreglo_e  = unserialize($this->input->post("contenido_arreglo_e")); // recuperarla;

  $contenido_arreglo_f  = unserialize($this->input->post("contenido_arreglo_f")); // recuperarla;

  $contenido_arreglo_g  = unserialize($this->input->post("contenido_arreglo_g")); // recuperarla;

  $contenido_arreglo_h  = unserialize($this->input->post("contenido_arreglo_h")); // recuperarla;

  $contenido_arreglo_i  = unserialize($this->input->post("contenido_arreglo_i")); // recuperarla;

  $contenido_arreglo_j  = unserialize($this->input->post("contenido_arreglo_j")); // recuperarla;
  $contenido_arreglo_k  = unserialize($this->input->post("contenido_arreglo_k")); // recuperarla;

  $contenido_arreglo_l  = unserialize($this->input->post("contenido_arreglo_l")); // recuperarla;

  $contenido_arreglo_m  = unserialize($this->input->post("contenido_arreglo_m")); // recuperarla;

  $contenido_arreglo_n  = unserialize($this->input->post("contenido_arreglo_n")); // recuperarla;
    $contenido_arreglo_o  = unserialize($this->input->post("contenido_arreglo_o")); // recuperarla;
  $contenido_arreglo_p  = unserialize($this->input->post("contenido_arreglo_p")); // recuperarla;
//ha que insertar en nuevo presupuesto asignarle un estado de no validado, y agregarle sus detalles

  //ensamblar array para la tabla de presupuestos
  $array_presupuesto = array(
   "nombre" => $nombre,
   "id_carrera" => $carrera,
  
   "id_ciclo"    => $ciclo
    );

  $this->db->insert("presupuestos", $array_presupuesto);
  $id_presupuesto = $this->db->insert_id(); 

  //ensamblar array para los detalles de presupuesto
//el limite del for sera el arreglo D, debdo a que es la columna de asignaturas entonces lo recorrere para evaluar hasta donde tiene registros. esto sera til para saber hasta donde
  //debo recorrer los demas arreglos
  $c = 0;
  for ($i=0; $i <count($contenido_arreglo_c) ; $i++) { 
     if($contenido_arreglo_c[$i]  != ""){
      $c++; 
     }
  }

  

for ($i=0; $i < $c; $i++) { 
 $detalle_presupuesto_array = array(
    "id_presupuesto" => $id_presupuesto,
    "cat" =>       $contenido_arreglo_b[$i],
    "cod" =>       $contenido_arreglo_c[$i],
    "asignatura" =>       $contenido_arreglo_d[$i],
    "sec" =>       $contenido_arreglo_e[$i],
    "dhd" =>       $contenido_arreglo_f[$i],
    "aula" =>       $contenido_arreglo_g[$i],
    "ins" =>       $contenido_arreglo_h[$i],
    "tit" =>       $contenido_arreglo_i[$i],
    "docente" =>       $contenido_arreglo_j[$i],
    "cate" =>       $contenido_arreglo_k[$i],
    "pag" =>       $contenido_arreglo_l[$i],
    "perf" =>       $contenido_arreglo_m[$i],
    "nota" =>       $contenido_arreglo_n[$i],
    "observaciones" =>       $contenido_arreglo_o[$i],
    "mae" =>       $contenido_arreglo_p[$i]

    );

$this->db->insert("detalle_presupuesto", $detalle_presupuesto_array);



  
}
 
    
$this->index(1); 




}





public function setValidationCell($obExcel, $celda, $rangoCeldas){
      $objValidation = $obExcel->getActiveSheet()->getCell($celda)->getDataValidation();
      $objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
      $objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
      $objValidation->setAllowBlank(false);
      $objValidation->setShowInputMessage(true);
      $objValidation->setShowErrorMessage(true);
      $objValidation->setShowDropDown(true);
      $objValidation->setErrorTitle('Dato Erroneo');
      $objValidation->setError('El valor ingresado no está en la lista.');
      $objValidation->setPromptTitle('Selección de lista');
      $objValidation->setPrompt('Por favor seleccione el valor de la lista desplegable.');
      $objValidation->setFormula1("=".$rangoCeldas);  // Make sure to put the list items between " and "  !!!

}



public function exportar_excel(){


error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

/** Include PHPExcel */


// Create new PHPExcel object
echo date('H:i:s') , " Create new PHPExcel object" , EOL;
//$objPHPExcel = new PHPExcel();


$fileType = 'Excel2007';
$fileName = './PHPExcel/Examples/CARGA-fica.xlsx';

// Read the file
$objReader = PHPExcel_IOFactory::createReader($fileType);
$objPHPExcel = $objReader->load($fileName);



// Set document properties
echo date('H:i:s') , " Set document properties" , EOL;

$objPHPExcel->getProperties()->setCreator("Enrique Castillo")
							 ->setLastModifiedBy("Enrique Castillo")
							 ->setTitle("Modificacion de Archivo de Excel desde PHP")
							 ->setSubject("Modificacion Excel PHP")
							 ->setDescription("Prueba de modificacin de archivo de excel")
							 ->setKeywords("office PHPExcel php")
							 ->setCategory("Test result file");


// Add some data
echo date('H:i:s') , " Add some data" , EOL;
$usuarios = $this->db->query('select * from docentes')->result_array();
$iteracion = 2; 
$iteracion_corre = 1; 
foreach ($usuarios as $key) {
	$objPHPExcel->setActiveSheetIndex(2)->setCellValue('A'.$iteracion, $iteracion_corre);
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('B'.$iteracion, $key['nombres'].' '.$key['Aapellidos']);


$iteracion++; 
$iteracion_corre++;

}

$titulos = $this->db->query('select * from titulos')->result_array();
$iteracion = 2; 
$iteracion_corre = 1; 
foreach ($titulos as $key) {
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('C'.$iteracion, $iteracion_corre);
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('D'.$iteracion, $key['titulo']);

$iteracion++; 
$iteracion_corre++;

}

$duracion = $this->db->query('select * from Horario')->result_array();
$iteracion = 2; 
$iteracion_corre = 1; 
foreach ($duracion as $key) {
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('E'.$iteracion, $iteracion_corre);
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('F'.$iteracion, $key['DiasTexto']."-".$key['HorarioTexto']."-".$key['Duracion']);

$iteracion++; 
$iteracion_corre++;

}

$categorias = $this->db->query('select * from categorias')->result_array();
$iteracion = 2; 
$iteracion_corre = 1; 
foreach ($categorias as $key) {
  $objPHPExcel->setActiveSheetIndex(2)->setCellValue('G'.$iteracion, $iteracion_corre);
    $objPHPExcel->setActiveSheetIndex(2)->setCellValue('H'.$iteracion, $key['cate']);

$iteracion++; 
$iteracion_corre++;

}


//Configuracion de validacion de datos con lista
$objPHPExcel->setActiveSheetIndex(0);           
for($i=9; $i<136; $i++){
  $this->setValidationCell($objPHPExcel,"J{$i}",'Docentes!$B$2:$B$200');
  $this->setValidationCell($objPHPExcel,"I{$i}",'Docentes!$D$2:$D$200');
   $this->setValidationCell($objPHPExcel,"F{$i}",'Docentes!$F$2:$F$200');
   $this->setValidationCell($objPHPExcel,"K{$i}",'Docentes!$H$2:$H$200');
 } 
 


// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A4', '')
            ->setCellValue('A5', '');


$objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
$objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
$objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);


// Rename worksheet
echo date('H:i:s') , " Rename worksheet" , EOL;
$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Save Excel 2007 file
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


// Save Excel 95 file
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$callStartTime = microtime(true);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$nueva_direccion = str_replace("/application/controllers/", "/", __FILE__);
$objWriter->save(str_replace('.php', '.xlsx', $nueva_direccion));
$callEndTime = microtime(true);
$callTime = $callEndTime - $callStartTime;

echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;
echo 'Call time to write Workbook was ' , sprintf('%.4f',$callTime) , " seconds" , EOL;
// Echo memory usage
echo date('H:i:s') , ' Current memory usage: ' , (memory_get_usage(true) / 1024 / 1024) , " MB" , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing files" , EOL;
echo 'Files have been created in ' , getcwd() , EOL;
redirect(base_url("presupuestos/load_pre.xlsx")); 




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