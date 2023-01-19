<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Descripcion:
 * Administra XHTTP REQUEST para busqueda de personas y documentos BACK-END
 * @author:		Alan Alvarenga - Grupo Satelite 
 * @version:	v0.3 - 2013/06/04 
 * @since:		2013/05/31
 * @package: 	Alcaldia - Chalatenango
 * =====================================================================================
 * Bitacora:
 * 2013/06/03
 * + get_documento - Devuelve JSON object con todos los documentos de una persona 
 * 					 en especifico
 * by Alan Alvarenga
 * -------------------------------------------------------------------------------------
 * 2013/05/31
 * + get_persona - Devuelve JSON object con toda la informacion obtenida de la BD
 * by Alan Alvarenga
 * -------------------------------------------------------------------------------------
 */
class Buscar extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('persona');
	}

	public function index()
	{	
		
		$this->_cargarvista();
	}

	protected function _cargarvista( $data=0, $crud=0 )
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

	/**
	 * Devuelve JSON object con toda la informacion obtenida de la BD
	 * @return JSON Object 	Return ID + Full Name de la tabla per_persona
	 */
	public function get_persona()
	{
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}
		//Get terminos de busqueda
		$keywords 	= strtolower( $_REQUEST['search'] );

		//Set Ajax data response to false
		$data['response'] = FALSE;
		
		/*
			Cargar modelo persona y usar get_persona para obtener la informacion
			de todas las personas que concuerdan con los parametros de busqueda
		 */
		
		$query_results = $this->persona->get_persona( $keywords ); 

		
		/*
			Verificar resultados
		 */
		if( $query_results && $query_results->num_rows() > 0 ){
			
			/*
				Si los resultados son mayores a 0 set Ajax response = true
				y crear un nuevo index array para almacenar los resultados obtenidos
			 */
			$data['response'] = true;
			$data['message'] = array();
			
			/*
				
				Extraer data y almacenarla en la respuesta de AJAX message
			 */
			foreach ( $query_results->result() as $row ) {
				
				//Contruir AJAX message response para personas
					$data['message'][] = array(
						'id' 			=> $row->per_id,
						'name' 			=> $row->per_full_name,
						'address' 		=> 'Documento: '.$row->doc_numero.'&#13;Padre: '.$row->per_padre.'&#13;Madre: '.$row->per_madre
						);
			}

			//Codificar los resultados JSON
			echo json_encode( $data );
		}else{

			//if no data found using keywords set alert message
			$data['response'] = true;
			$data['message'] = array();

			$data['message'][] = Array(
				'id' 		=> false,
				'name'		=> '',
				'address' 	=> ''
				);
			
			echo json_encode( $data );
		}
		
	}
	
	/**
	 * Devuelve JSON object con todos los documentos de una persona en especifico
	 * @return JSON Object 		Return AJAX response con todos los documentos encontrados para una persona
	 */
	public function get_documento()
	{
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}
		//Get persona id
		$user_id 	= $_REQUEST['id'];

		//Set Ajax data response to false
		$data['response'] = FALSE;

		/*
			Cargar modelo persona y user get_documento para obtener los documentos
			correspondientes a la persona especificada por id
		 */
		
		$query_results = $this->persona->get_documento( $user_id ); 

		echo $query_results;
	}

	/**
	 * Devuelve JSON object con toda la informacion obtenida de la BD
	 * @return JSON Object 	Return ID + Full Name de la tabla per_persona
	 */
	public function get_contribuyente()
	{
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}
		//Get terminos de busqueda
		$keywords 	= strtolower( $_REQUEST['search'] );

		//Set Ajax data response to false
		$data['response'] = FALSE;
		
		/*
			Cargar modelo persona y usar get_persona para obtener la informacion
			de todas las personas que concuerdan con los parametros de busqueda
		 */
		
		$query_results = $this->persona->get_contribuyente( $keywords ); 

		echo $query_results;
		
	}

	/**
	 * Devuelve JSON object con todos los documentos de una persona en especifico
	 * @return JSON Object 		Return AJAX response con todos los documentos encontrados para una persona
	 */
	public function get_documento_contribuyente()
	{
		if (!$this->input->is_ajax_request()) {
   			exit('No direct script access allowed');
		}
		//Get persona id
		$user_id 	= $_REQUEST['id'];

		//Set Ajax data response to false
		$data['response'] = FALSE;

		/*
			Cargar modelo persona y user get_documento para obtener los documentos
			correspondientes a la persona especificada por id
		 */
		
		$query_results = $this->persona->get_documento_contribuyente( $user_id ); 

		echo $query_results;
	}


}

/* End of file search.php */
/* Location: ./application/controllers/search.php */