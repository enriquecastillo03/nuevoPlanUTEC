<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Descripcion
 * Accede a BD y devuelve todos los datos obtenidos desde una consulta 
 * especifica usando busqueda de indices FULLTEXT en el motor de DB innoDB
 * 
 * @author: 	Alan Alvarenga - Grupo Satelite 
 * @version: 	v0.3 - 2013/05/30
 * @since:		2013/05/31 
 * @package: 	Alcaldia - Chalatenango
 * =================================================================================
 * Bitacora:
 * 2013/06/21
 * + get_documento_contribuyente - Busqueda de documentos de contribuyente por ID
 * + get_contribuyente - InnoDB FULLTEXT - Busqueda de contribuyentes por parametros dinamicos
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/06
 * + get_documento - Devuelve CI Obj con todos los documentos de una persona 
 * 					 en especifico
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/03
 * + normalizar_string - Convierte todos los caracteres especiales a su equivalente
 * 						 caracter normal
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/05/31
 * + get_persona - InnoDB FULLTEXT - Busqueda de personas por parametros dinamicos
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 */

class Persona extends CI_Model {

	/*
		String Consulta que contiene todos los parametros a enviar a MYSQL
	 */
	var $query; 

	
	/**
	 * Buscar personas por palabras clave
	 * @param  String 	$keywords 	Parametros de busqueda
	 * @return CI Query Object      CI Query Object Result con toda la informacion encontrada
	 */
	public function get_persona( $keywords )
	{
		$term = array();

		$keywords = $this->normalizar_string( $keywords );
		
		// Convetir palabras claves a un arreglo
		$params = explode(' ', $keywords );

		foreach ( $params as $value) {

			//Agregar signo + antes de cada palabra clave ( necesario para usar BOOLEAN MODE )
			$term[] = '+'.strtolower( $value );

 		}

 		
 		/*
 			Consulta base para busqueda de personas
 			CONCAT_WS = CONCATENATE WITH SEPARATOR
 			CONCAT_WS(separador, field1, field2, ..., fieldn ) as full_field
 			
 		 */
 		$this->query = " SELECT hijo.per_id, CONCAT_WS(' ', hijo.per_apellido1, hijo.per_apellido2, '-',
 								hijo.per_nombre1, hijo.per_nombre2) as per_full_name, doc_numero,
								CONCAT_WS(' ', padre.per_nombre1, padre.per_nombre2,
								padre.per_apellido1, padre.per_apellido2) as per_padre,
								CONCAT_WS(' ', madre.per_nombre1, madre.per_nombre2,
								madre.per_apellido1, madre.per_apellido2) as per_madre
 							FROM per_persona as hijo 
 							LEFT JOIN dxp_documentoxpersona ON dxp_id_per=hijo.per_id
 							LEFT JOIN doc_documento ON doc_id=dxp_id_doc
 							LEFT JOIN asn_asentamiento ON asn_per_id_hijo=per_id AND ((asn_tipo=0 AND asn_estado=1) OR (asn_tipo=1 AND asn_estado=1))
 							LEFT JOIN per_persona as padre ON padre.per_id=asn_per_id_padre
 							LEFT JOIN per_persona as madre ON madre.per_id=asn_per_id_madre
 							WHERE
 								MATCH( hijo.per_keyword ) 
 								
 								AGAINST (";
 		
 		/*
 			Contruyendo consulta dinamica, "?" agregado para enlazar informacion ( Bind Data ) 
 			con CI Active Record para evitar SQL Injection
 		 */
 		foreach ( $term as $key ) {

 			$this->query .= ' ? ';
 		}

 		/*
 			Fin query
 		 */
 		$this->query .= " IN BOOLEAN MODE) LIMIT 100";

		//Ejecutar consulta y enlazar parametros
		$result = $this->db->query( $this->query, $term );
		

		return ( $result->num_rows() > 0 )
				? $result 
				: false;
	}

	/**
	 * Convierte todos los caracteres especiales a su equivalente caracter normal
	 * @param  String 	$string 	String con caracteres especiales 
	 * @return String 				String desinfectado sin caracteres especiales
	 */
	public function normalizar_string( $string )
	{
		/*
			Convertir a HTML Entities antes de analizarlo
			Obtener la posicion de los caracteres especiales (precedidos por ampersand '&' )
			y establecer su distribucion a UTF-8
		 */
	    if ( strpos($string = htmlentities($string, ENT_QUOTES, 'UTF-8'), '&') !== false )
	    {	
	    	/*
	    		Decodificar String previo, reemplazando caracteres especiales con normales
	    		REGEX: Matches characters in a range from a-z, then match previous token between 
	    		1 and 3 times from a selected list of matches ( None-capturing group matching )
	    	 */
	        $string = strtolower( html_entity_decode( preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $string ), ENT_QUOTES, 'UTF-8' ) );
	    }

	    //return string desinfectado
    	return $string;

	}

	/**
	 * Busqueda de documentos de persona por ID
	 * @param  Integer 	$user_id 	Person ID
	 * @return CI Query Object      CI Query Object result with all the data found
	 */
	public function get_documento( $user_id )
	{
		//Base query
		$this->query = "SELECT per_id, doc_numero, tid_nombre
							FROM per_persona 
							INNER JOIN dxp_documentoxpersona ON dxp_id_per = per_id 
							INNER JOIN doc_documento ON doc_id = dxp_id_doc
							INNER JOIN tid_tipo_documento ON doc_tid_id = tid_id 
							WHERE per_id = ? ";

		//Run query
		$query_results = $this->db->query( $this->query, $user_id );

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
			foreach ($query_results->result() as $row ) {

				//Contruir AJAX message response documentos de persona
					$data['message'][] = array(
						'id' 			=> $row->per_id,
						'doc_number' 	=> $row->doc_numero,
						'doc_type' 		=> $row->tid_nombre
						);
			}

			//Codificar los resultados JSON
			$result = json_encode($data);

		}else{

			//if no data found using user_id set alert message
			$data['response'] = true;
			$data['message'] = array();

			$data['message'][] = Array(
				'id' 			=> false,
				'doc_number' 	=> 'Est&aacute; persona no posee ning&uacute;n documento',
				'doc_type'		=> 'Informaci&oacute;n'
				);
			
			$result = json_encode($data);
		}

		return $result;

	}


	/**
	 * Buscar contribuyentes por palabras clave
	 * @param  String 	$keywords 	Parametros de busqueda
	 * @return CI Query Object      CI Query Object Result con toda la informacion encontrada
	 */
	public function get_contribuyente( $keywords )
	{
		$term = array();

		$keywords = $this->normalizar_string( $keywords );
		
		// Convetir palabras claves a un arreglo
		$params = explode(' ', $keywords );

		foreach ( $params as $value) {

			//Agregar signo + antes de cada palabra clave ( necesario para usar BOOLEAN MODE )
			$term[] = '+'.strtolower( $value );

 		}

 		
 		/*
 			Consulta base para busqueda de personas
 			CONCAT_WS = CONCATENATE WITH SEPARATOR
 			CONCAT_WS(separador, field1, field2, ..., fieldn ) as full_field
 			
 		 */
 		$this->query = " SELECT con_id, CONCAT_WS(' ', con_apellido1, con_apellido2, '-',
 								con_nombre1, con_nombre2) as con_full_name, con_direccion
 							FROM con_contribuyente
 							WHERE
 								MATCH( con_keyword ) 
 								AGAINST (";
 		
 		/*
 			Contruyendo consulta dinamica, "?" agregado para enlazar informacion ( Bind Data ) 
 			con CI Active Record para evitar SQL Injection
 		 */
 		foreach ( $term as $key ) {

 			$this->query .= ' ? ';
 		}

 		/*
 			Fin query
 		 */
 		$this->query .= " IN BOOLEAN MODE)";

		//Ejecutar consulta y enlazar parametros
		$query_results = $this->db->query( $this->query, $term );
		
		/*
			Verificar resultados
		 */
		if( $query_results && $query_results->num_rows() > 0 ){
			
			/*
				Si los resultados son mayores a 0 set Ajax response = true
				y crear un nuevo index array para almacenar los resultados obtenidos
			 */
			$data['response'] = TRUE;
			$data['message'] = array();
			
			/*
				
				Extraer data y almacenarla en la respuesta de AJAX message
			 */
			foreach ( $query_results->result() as $row ) {
				
				//Contruir AJAX message response para personas
					$data['message'][] = array(
						'id' 			=> $row->con_id,
						'name' 			=> $row->con_full_name,
						'address' 		=> $row->con_direccion
						);
			}

			//Codificar los resultados JSON
			$result =  json_encode( $data );
		}else{

			//if no data found using keywords set alert message
			$data['response'] = TRUE;
			$data['message'] = array();

			$data['message'][] = Array(
				'id' 		=> FALSE,
				'name'		=> '',
				'address' 	=> ''
				);
			
			$result =  json_encode( $data );
		}

		return $result;
	}

	/**
	 * Busqueda de documentos de contribuyente por ID
	 * @param  Integer 	$user_id 	Contribuyente ID
	 * @return CI Query Object      CI Query Object result with all the data found
	 */
	public function get_documento_contribuyente( $user_id )
	{
		//Base query
		$this->query = "SELECT con_id, doc_numero, tid_nombre
							FROM con_contribuyente 
							INNER JOIN dxc_documentoxcontribuyente ON dxc_id_con = con_id 
							INNER JOIN doc_documento ON doc_id = dxc_id_doc
							INNER JOIN tid_tipo_documento ON doc_tid_id = tid_id 
							WHERE con_id = ? ";

		//Run query
		$query_results = $this->db->query( $this->query, $user_id );

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
			foreach ($query_results->result() as $row ) {

				//Contruir AJAX message response documentos de persona
					$data['message'][] = array(
						'id' 			=> $row->con_id,
						'doc_number' 	=> $row->doc_numero,
						'doc_type' 		=> $row->tid_nombre
						);
			}

			//Codificar los resultados JSON
			$result = json_encode($data);

		}else{

			//if no data found using user_id set alert message
			$data['response'] = true;
			$data['message'] = array();

			$data['message'][] = Array(
				'id' 			=> false,
				'doc_number' 	=> 'Est&aacute; persona no posee ning&uacute;n documento',
				'doc_type'		=> 'Informaci&oacute;n'
				);
			
			$result = json_encode($data);
		}

		return $result;


	}

	
}

/* End of file personas.php */
/* Location: ./application/models/personas.php */