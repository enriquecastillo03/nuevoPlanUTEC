<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Ref_model extends CI_Model
{

	function __construct()
	{
		$this->load->database();
		//$this->db->db_debug=false;
	}
	

	/**
	 * Consulta general de cualquier tabla
	 * @param  string  $tabla
	 * @param  array $where
	 * @param  integer $join
	 * @return array
	 */
	public function get_tabla($tabla,$where=0,$join=0)
   {
		$this->db->select();
		if($where!=0)
		$this->db->where($where);
		$this->db->from($tabla);				
		$query=$this->db->get();
		return $query->result_array();
   }

    public function obtener_informacion_evento($id_evento)
    {
      $this->db->select('*');
      $this->db->from('rev_ref_evento');
      $this->db->where('rev_id',(int)$id_evento);
      $query=$this->db->get();
      return $query->result_array();
    }

    /**
    * Consulta de todas las partidas de nacimiento por libro
    * @param  string $tabla
    * @param  array $where
    * @return array
    */
   	public function get_partidas($tabla,$where,$img=2,$per_id=0)
    {
    	$select="";
      $flag=0;
		$this->db->where($where);
    if($tabla=='mod_modificacion')
      $this->db->from('asn_asentamiento');      
    else      
		  $this->db->from($tabla);
		switch ($tabla) {
    		case 'asn_asentamiento':
	    		$this->db->join('per_persona','per_id=asn_per_id_hijo');        
				  $this->db->join('lib_libro','lib_id=asn_id_lib');
          if($per_id>0)
            $this->db->where('(asn_per_id_hijo='.$per_id.' or asn_per_id_padre='.$per_id.' or asn_per_id_madre='.$per_id.') and asn_tipo=0');
          $select="*,'asn' as sufijo, 'Nacimiento' as tipo_partida,CONCAT('Fecha: ',DAY(asn_fecha_nac),'-',MONTH(asn_fecha_nac),'-',YEAR(asn_fecha_nac),'<br>Hora:',asn_hora_nac,'<br>Lugar:',asn_lugar_nacimiento,'<br>Observaciones:',asn_observacion) as detalles";
    			break;
        case 'mod_modificacion':
          $this->db->join('per_persona','per_id=asn_per_id_hijo');
          $this->db->join('lib_libro','lib_id=asn_id_lib');
          if($per_id>0)
            $this->db->where('(asn_per_id_hijo='.$per_id.' or asn_per_id_padre='.$per_id.' or asn_per_id_madre='.$per_id.') and asn_tipo=1');
          $select="*,'asn' as sufijo, 'Modificacion' as tipo_partida,CONCAT('Fecha: ',DAY(asn_fecha_nac),'-',MONTH(asn_fecha_nac),'-',YEAR(asn_fecha_nac),'<br>Hora:',asn_hora_nac,'<br>Lugar:',asn_lugar_nacimiento,'<br>Observaciones:',asn_observacion) as detalles";
          break;
			case 'mat_matrimonio':
    			$this->db->join('per_persona as per_persona2','per_persona2.per_id=mat_per_id_contrayente2');
    			$this->db->join('per_persona as per_persona1','per_persona1.per_id=mat_per_id_contrayente1');   
          if($per_id>0)
            $this->db->where('mat_per_id_contrayente2='.$per_id.' or mat_per_id_contrayente1='.$per_id);
    			$select="*,'mat' as sufijo, 'Matrimonio' as tipo_partida,CONCAT(per_persona1.per_nombre1,' ',per_persona1.per_nombre2) per_nombre1,CONCAT(per_persona1.per_apellido1,' ',per_persona1.per_apellido2) per_nombre2,
    					CONCAT('<br>Y<br>',per_persona2.per_nombre1,' ',per_persona2.per_nombre2) per_apellido1,CONCAT(per_persona2.per_apellido1,' ',per_persona2.per_apellido2) per_apellido2,
              CONCAT('Regimen:',rgm_nombre,'<br>Notario:',mat_nombre_oficio,'<br>Fecha:',DAY(mat_fecha),'-',MONTH(mat_fecha),'-',YEAR(mat_fecha),'<br>Lugar:',mat_lugar) as detalles";

				$this->db->join('lib_libro','lib_id=mat_id_lib');
        $this->db->join('rgm_regimen_matrimonio','rgm_id=mat_rgm_id');
    			break;
    		case 'div_divorcio':
    			$this->db->join('per_persona as per_persona2','per_persona2.per_id=div_per_id_divorciante2');
    			$this->db->join('per_persona as per_persona1','per_persona1.per_id=div_per_id_divorciante1'); 
          if($per_id>0)
            $this->db->where('div_per_id_divorciante2='.$per_id.' or div_per_id_divorciante1='.$per_id);   	
    			$select="*,'Divorcio' as tipo_partida,'div' as sufijo,CONCAT(per_persona1.per_nombre1,' ',per_persona1.per_nombre2) per_nombre1,CONCAT(per_persona1.per_apellido1,' ',per_persona1.per_apellido2) per_nombre2,
              CONCAT('<br>Y<br>',per_persona2.per_nombre1,' ',per_persona2.per_nombre2) per_apellido1,CONCAT(per_persona2.per_apellido1,' ',per_persona2.per_apellido2) per_apellido2,
              CONCAT('Fecha de sentencia ejecutoriada:',div_fecha_ejecutor,'<br>Fecha:',DAY(div_fecha),'-',MONTH(div_fecha),'-',YEAR(div_fecha),'<br>Observaciones:',div_datos_generales) as detalles";
				$this->db->join('lib_libro','lib_id=div_id_lib');
        
    			break;
    		case 'def_defuncion':
    			$this->db->join('per_persona','per_id=def_per_id_difunto');
  				$this->db->join('lib_libro','lib_id=def_id_lib');
          $select="*,'Defuncion' as tipo_partida,'def' as sufijo,CONCAT('Fecha: ',DAY(def_fecha),'-',MONTH(def_fecha),'-',YEAR(def_fecha),'<br>Lugar:',def_lugar,'<br>Causa:',def_causa) as detalles";
    			break;
    		case 'ado_adopcion':
    			$this->db->join('per_persona','per_id=ado_per_id_adoptado');
				  $this->db->join('lib_libro','lib_id=ado_id_lib');
          $select="*,'Adopcion' as tipo_partida,'ado' as sufijo,CONCAT('Fecha: ',DAY(ado_fecha),'-',MONTH(ado_fecha),'-',YEAR(ado_fecha),'<br>Lugar:',ado_lugar,'<br>Notario:',ado_notario) as detalles";
    			break;
			case 'rep_reposicion':
    			$this->db->join('per_persona','per_id=rep_per_id');
				  $this->db->join('lib_libro','lib_id=rep_id_lib');
          $select="*,'Reposicion' as tipo_partida,'rep' as sufijo,CONCAT('Fecha: ',DAY(rep_fecha),'-',MONTH(rep_fecha),'-',YEAR(rep_fecha),'<br>Certificacion:',rep_certificacion) as detalles";
    			break;
      case 'jui_juicio':
          $this->db->join('per_persona','per_id=jui_per_id');
          $this->db->join('lib_libro','lib_id=jui_id_lib');
          $select="*,'Juicio' as tipo_partida,'jui' as sufijo,CONCAT('Fecha: ',DAY(jui_fecha),'-',MONTH(jui_fecha),'-',YEAR(jui_fecha)) as detalles";
          break;
          case 'jud_juiciodefuncion':
          $this->db->join('per_persona','per_id=jud_per_id');
          $this->db->join('lib_libro','lib_id=jud_id_lib');
          $select="*,'Juicio defuncion' as tipo_partida,'jud' as sufijo,CONCAT('Fecha: ',DAY(jud_fecha),'-',MONTH(jud_fecha),'-',YEAR(jud_fecha)) as detalles";
          break;
           case 'unm_union_no_matrimonial':
            $this->db->join('per_persona as per_persona2','per_persona2.per_id=unm_per_id_contrayente2');
          $this->db->join('per_persona as per_persona1','per_persona1.per_id=unm_per_id_contrayente1');   
          if($per_id>0)
            $this->db->where('unm_per_id_contrayente2='.$per_id.' or unm_per_id_contrayente1='.$per_id);
          $select="*,'unm' as sufijo, 'Union' as tipo_partida,CONCAT(per_persona1.per_nombre1,' ',per_persona1.per_nombre2) per_nombre1,CONCAT(per_persona1.per_apellido1,' ',per_persona1.per_apellido2) per_nombre2,
              CONCAT('<br>Y<br>',per_persona2.per_nombre1,' ',per_persona2.per_nombre2) per_apellido1,CONCAT(per_persona2.per_apellido1,' ',per_persona2.per_apellido2) per_apellido2,
              CONCAT('<br>Notario:',unm_numero_oficio,'<br>Fecha:',DAY(unm_fecha),'-',MONTH(unm_fecha),'-',YEAR(unm_fecha),'<br>Lugar:',unm_lugar) as detalles";
        $this->db->join('lib_libro','lib_id=unm_id_lib');
        //$this->db->join('rgm_regimen_matrimonio','rgm_id=mat_rgm_id');
          break;


           case 'rpt_regimen_patrimonial':
            $this->db->join('mat_matrimonio as mat','mat.mat_id=rpt_regimen_patrimonial.rpt_id_mat');  
            $this->db->join('per_persona as per_persona2','per_persona2.per_id=mat.mat_per_id_contrayente2');
          $this->db->join('per_persona as per_persona1','per_persona1.per_id=mat.mat_per_id_contrayente1');   

          if($per_id>0)
            $this->db->where('mat.mat_per_id_contrayente2='.$per_id.' or mat.mat_per_id_contrayente1='.$per_id);
          $select="*,'rpt' as sufijo, 'Regimen patrimonial' as tipo_partida,CONCAT(per_persona1.per_nombre1,' ',per_persona1.per_nombre2) per_nombre1,CONCAT(per_persona1.per_apellido1,' ',per_persona1.per_apellido2) per_nombre2,
              CONCAT('<br>Y<br>',per_persona2.per_nombre1,' ',per_persona2.per_nombre2) per_apellido1,CONCAT(per_persona2.per_apellido1,' ',per_persona2.per_apellido2) per_apellido2,
              CONCAT('<br>Notario:',mat.mat_nombre_oficio,'<br>Fecha:',DAY(mat.mat_fecha),'-',MONTH(mat.mat_fecha),'-',YEAR(mat.mat_fecha),'<br>Lugar:',mat.mat_lugar) as detalles";
        $this->db->join('lib_libro','lib_id=rpt_regimen_patrimonial.rpt_id_lib');
        //$this->db->join('rgm_regimen_matrimonio','rgm_id=mat_rgm_id');
          break;

  case 'acm_acta_matrimonial':  
            $this->db->join('per_persona as per_persona2','per_persona2.per_id=acm_per_id_contrayente2');
          $this->db->join('per_persona as per_persona1','per_persona1.per_id=acm_per_id_contrayente1');   

          if($per_id>0)
            $this->db->where('acm_per_id_contrayente2='.$per_id.' or acm_per_id_contrayente1='.$per_id);
          $select="*,'acm' as sufijo, 'Acta matrimonial' as tipo_partida,CONCAT(per_persona1.per_nombre1,' ',per_persona1.per_nombre2) per_nombre1,CONCAT(per_persona1.per_apellido1,' ',per_persona1.per_apellido2) per_nombre2,
              CONCAT('<br>Y<br>',per_persona2.per_nombre1,' ',per_persona2.per_nombre2) per_apellido1,CONCAT(per_persona2.per_apellido1,' ',per_persona2.per_apellido2) per_apellido2,
              CONCAT('<br>Notario:',acm_nombre_oficio,'<br>Fecha:',DAY(acm_fecha),'-',MONTH(acm_fecha),'-',YEAR(acm_fecha),'<br>Lugar:',acm_lugar) as detalles";
        $this->db->join('lib_libro','lib_id=acm_acta_matrimonial.acm_id_lib');
        //$this->db->join('rgm_regimen_matrimonio','rgm_id=acm_rgm_id');
          break;

  		case 'mar_marginacion':
          if($img==0)
            $im=2;
          else
            $im=$img;
          $mega_select="select img_imagen.*,'Marginacion de matrimonio' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,CONCAT(p1.per_nombre1,' ',p1.per_nombre2) per_nombre1,CONCAT(p1.per_apellido1,' ',p1.per_apellido2) per_nombre2, CONCAT('<br>Y<br>',p2.per_nombre1,' ',p2.per_nombre2) per_apellido1,CONCAT(p2.per_apellido1,' ',p2.per_apellido2) per_apellido2 from mar_marginacion
                        inner join mxm_mat_matrimonioxmar_marginacion on mxm_id_mar = mar_id
                        inner join mat_matrimonio on mat_id = mxm_id_mat
                        inner join per_persona p1 on p1.per_id = mat_per_id_contrayente1
                        inner join per_persona p2 on p2.per_id = mat_per_id_contrayente2
                        inner join lib_libro on lib_id = mat_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de divorcio' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,CONCAT(p1.per_nombre1,' ',p1.per_nombre2) per_nombre1,CONCAT(p1.per_apellido1,' ',p1.per_apellido2) per_nombre2, CONCAT('<br>Y<br>',p2.per_nombre1,' ',p2.per_nombre2) per_apellido1,CONCAT(p2.per_apellido1,' ',p2.per_apellido2) per_apellido2 from mar_marginacion
                        inner join dxm_divorcioxmarginacion on dxm_id_mar = mar_id
                        inner join div_divorcio on div_id = dxm_id_div
                        inner join per_persona p1 on p1.per_id = div_per_id_divorciante1
                        inner join per_persona p2 on p2.per_id = div_per_id_divorciante2
                        inner join lib_libro on lib_id = div_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de defuncion' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,per_nombre1,per_nombre2,per_apellido1, per_apellido2 from mar_marginacion
                        inner join mxd_marginacionxdef_defuncion on mxd_id_mar = mar_id
                        inner join def_defuncion on def_id = mxd_id_def
                        inner join per_persona on per_id = def_per_id_difunto
                        inner join lib_libro on lib_id = def_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de asentamiento' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,per_nombre1,per_nombre2,per_apellido1, per_apellido2 from mar_marginacion
                        inner join mxa_marginacionxasentamiento on mxa_id_mar = mar_id
                        inner join asn_asentamiento on asn_id = mxa_id_asn
                        inner join per_persona on per_id = asn_per_id_hijo
                        inner join lib_libro on lib_id = asn_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de adopcion' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,per_nombre1,per_nombre2,per_apellido1, per_apellido2 from mar_marginacion
                        inner join mxa_mar_marginacionxado_adopcion on mxa_id_mar = mar_id
                        inner join ado_adopcion on ado_id = mxa_id_ado
                        inner join per_persona on per_id = ado_per_id_adoptado
                        inner join lib_libro on lib_id = ado_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de reposicion' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,per_nombre1,per_nombre2,per_apellido1, per_apellido2 from mar_marginacion
                        inner join mxr_mar_marginacionxrep_reposicion on mxr_id_mar = mar_id
                        inner join rep_reposicion on rep_id = mxr_id_rep
                        inner join per_persona on per_id = rep_per_id
                        inner join lib_libro on lib_id = rep_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im."
                        union
                        select img_imagen.*,'Marginacion de juicio' as tipo_partida,'mar' as sufijo,CONCAT('Fecha: ',DAY(mar_fecha),'-',MONTH(mar_fecha),'-',YEAR(mar_fecha),'<br>Certificacion:',mar_certificacion) as detalles,mar_marginacion.*,lib_libro.*,tpl_tipo_libro.*,per_nombre1,per_nombre2,per_apellido1, per_apellido2 from mar_marginacion
                        inner join mxj_mar_marginacionxjui_juicio on mxj_id_mar = mar_id
                        inner join jui_juicio on jui_id = mxj_id_jui
                        inner join per_persona on per_id = jui_per_id
                        inner join lib_libro on lib_id = jui_id_lib
                        inner join tpl_tipo_libro on tpl_id = lib_tpl_id
                        left join ixm_imgxmarginacion on ixm_mar_id=mar_id
                        left join img_imagen on img_id=ixm_img_id AND img_estado=".$im;
                        $flag=1;
    			break;
    	}

      if($flag!=1){
        if($tabla=='mod_modificacion')            
          $tabla='asn_asentamiento';
        $cad=explode('_', $tabla);
        $sufijo=$cad[0];
        $inicial=$sufijo[0];
        $tablaix='ix'.$inicial.'_imgx'.$cad[1];
        $campoimagen='ix'.$inicial.'_img_id';
        $campopartida='ix'.$inicial.'_'.$sufijo.'_id';
        switch ($img) {
          case 0:
          $this->db->join($tablaix,$campopartida.'='.$sufijo.'_id','left');
          $this->db->where('('.$campoimagen.' is null or img_estado=0) ');
          $this->db->join('img_imagen','img_id='.$campoimagen,'left');         
          
          break;
          case 1:       
          $this->db->join($tablaix,$campopartida.'='.$sufijo.'_id');        
          $this->db->join('img_imagen','img_id='.$campoimagen);
          $this->db->where('img_estado',1);
          break;
          case 2:
          $this->db->join($tablaix,$campopartida.'='.$sufijo.'_id');        
          $this->db->join('img_imagen','img_id='.$campoimagen);
          $this->db->where('img_estado',2);
          break;
        }
      $this->db->join('tpl_tipo_libro','tpl_id=lib_tpl_id');
    	$this->db->select($select);	
		$query=$this->db->get();
		return $query->result_array();}
    else
    {
        $query=$this->db->query($mega_select);
        return $query->result_array();
    }
    }

   /**
    * Devuelve el nombre de la tabla de partida a la cual se hara una consulta
    * @param  integer $id
    * @return array
    */
   public function get_tipo_libro($id)
   {
   		$this->db->select();
		$this->db->from('lib_libro');		
		$this->db->join('tpl_tipo_libro','tpl_id=lib_tpl_id');
		$this->db->where('lib_id',$id);		
		$query=$this->db->get();
		return $query->row_array();		   	
   }

   /**
    * Consulta de un registro especifico
    * @param  string $tabla
    * @param  array $where
    * @return array
    */
   public function get_registro($tabla,$where)
   {   		
		$this->db->select()
			->where($where)
			->from($tabla);			
		$query=$this->db->get();
		return $query->row_array();
   }

   public function get_persona($tabla,$where,$campo)
   {
    $this->db->select('*,d2.dep_nombre as dep_nacimiento, m2.mun_nombre as mun_nacimiento,d1.dep_nombre dep_nombre, m1.mun_nombre mun_nombre')
      ->where($where)
      ->from($tabla)
      ->join('per_persona',$campo)
      ->join('mun_municipio m1','m1.mun_id=per_mun_id_domicilio')
      ->join('mun_municipio m2','m2.mun_id=per_mun_id_nacimiento')
      ->join('dep_departamento d1','d1.dep_id=per_dep_id_origen') 
      ->join('dep_departamento d2','d2.dep_id=per_dep_id_nacimiento')
      ->join('gen_genero','gen_id=per_gen_id')
      ->join('nac_nacionalidad','nac_id=per_nac_id')
      ->join('pro_profesion','pro_id=per_pro_id','left')
      ->join('dxp_documentoxpersona','dxp_id_per=per_id','left')
      ->join('doc_documento','doc_id=dxp_id_doc','left');
      //->join('tid_tipo_documento','tid_id=doc_tid_id and tid_id=1','left');
    $query=$this->db->get();
    return $query->row_array();
   }

   public function get_partida($tabla,$where,$join)
   {      
    $this->db->select()
      ->where($where)
      ->from($tabla)    
      ->join('lib_libro',$join); 
    $query=$this->db->get();
    return $query->row_array();
   }

   public function get_libros()
   {    
    $this->db->select()
      ->from('lib_libro')    
      ->join('tpl_tipo_libro','tpl_id=lib_tpl_id'); 
    $query=$this->db->get();
    $arreglo = $query->result_array();
    for ($i=0; $i <count($arreglo) ; $i++) {       
      $arreglo[$i]['partidas']= $this->count_partidas($arreglo[$i]['lib_id']);
    }   
    return $arreglo;
   }

   /**
    * Conteo de registros
    * @param  string $tabla
    * @param  array $where
    * @return integer
    */
   public function count_registro($tabla,$where)
   {
      $this->db->select()
   			->where($where)
   			->from($tabla);
   		return $this->db->count_all_results();
   	}

     public function count_partidas($libro)
   {
    $registro=$this->get_tipo_libro($libro);
    $cad=explode('_', $registro['tpl_tabla']);
    $sufijo=$cad[0];
    
    
      $this->db->select()
        ->where($sufijo.'_id_lib',$libro)
        ->from($registro['tpl_tabla']);
      return $this->db->count_all_results();
    }

  /**
   * insertar un registro en la base de datos
   * @param string $tabla
   * @param array $datos
   * @return integer
   */
  public function add_regitro($tabla,$datos)
	{		
		try{
			$this->db->insert($tabla,$datos);
			return $this->db->insert_id();
		}
		catch(Exception $e){
			return 0;
		}
	}

   /**
    * Eliminar registros de la base de datos
    * @param  string $tabla
    * @param  array $where
    * @return integer
    */
   public function del_opc($tabla,$where)
	{		
		$this->db->delete($tabla,$where);
		return $this->db->affected_rows();	
	}

  public function cerrar_libro($id)  
  {
    $resultado=$this->mod_registro('lib_libro',array('lib_id'=>$id),array('lib_cierre'=>date('Y-m-d'),'lib_estado'=>2));
    return $resultado;
  }

  public function reaperturar_libro($id)  
  {
    $resultado=$this->mod_registro('lib_libro',array('lib_id'=>$id),array('lib_cierre'=>null,'lib_estado'=>1));
    return $resultado;
  }

  public function mod_registro($tabla, $where, $set)
  {
    $this->db->where($where);
    $this->db->update($tabla, $set); 
   return $this->db->affected_rows(); 
  }

  public function get_imagen($tabla,$id)
  {
    if($tabla=='mod_modificacion')            
      $tabla='asn_asentamiento';
    $cad=explode('_', $tabla);
    $sufijo=$cad[0];
    $inicial=$sufijo[0];
    $tablaix='ix'.$inicial.'_imgx'.$cad[1];
    $campoimagen='ix'.$inicial.'_img_id';
    $campopartida='ix'.$inicial.'_'.$sufijo.'_id';
    $this->db->select()    
    ->from('img_imagen')
    ->join($tablaix,$campoimagen.'=img_id')
    ->join($tabla,$sufijo.'_id='.$campopartida)
    ->where($sufijo.'_id',$id);
    $query=$this->db->get();
    return $query->result_array();
  }

  public function get_marginaciones($tabla,$partida)
  {
    if($tabla=='mod_modificacion')            
      $tabla='asn_asentamiento';    
    $cad=explode('_', $tabla);
    $sufijo=$cad[0];
    switch ($sufijo) {
      case 'asn':
        $tablamx='mxa_marginacionxasentamiento';
        $campomar='mxa_id_mar';
        $campomx='mxa_id_asn';
        break;
      case 'ado':
        $tablamx='mxa_mar_marginacionxado_adopcion';
        $campomar='mxa_id_mar';
        $campomx='mxa_id_ado';
        break;
      case 'def':
        $tablamx='mxd_marginacionxdef_defuncion';
        $campomar='mxd_id_mar';
        $campomx='mxd_id_def';
        break;
      case 'rep':
        $tablamx='mxr_mar_marginacionxrep_reposicion';
        $campomar='mxr_id_mar';
        $campomx='mxr_id_rep';
        break;
      case 'jui':
        $tablamx='mxj_mar_marginacionxjui_juicio';
        $campomar='mxj_id_mar';
        $campomx='mxj_id_jui';
        break;
       case 'div':
      $tablamx='dxm_divorcioxmarginacion';
      $campomar='dxm_id_mar';
      $campomx='dxm_id_div';
      break;
       case 'mat':
        $tablamx='mxm_mat_matrimonioxmar_marginacion';
        $campomar='mxm_id_mar';
        $campomx='mxm_id_mat';
        break;
    }
    //print_r('from '.$tabla.'<br> join '.$tablamx.' on '.$campomx.'='.$sufijo.'_id <br>join Marginacion on mar_id='.$campomar);
    //exit();
    $this->db->select()
    ->from($tabla)
    ->join($tablamx,$campomx.'='.$sufijo.'_id')
    ->join('mar_marginacion','mar_id='.$campomar)
    ->join('ixm_imgxmarginacion','ixm_mar_id=mar_id')
    ->join('img_imagen','img_id=ixm_img_id')
    ->where($sufijo.'_id',$partida)
    ->where('img_estado',2);
    $query=$this->db->get();      
    if ($query->num_rows() > 0)
      return $query->result_array();
    else
      return false;
  }

   public function get_eventos()
   {      
    $this->db->select()      
      ->from('rev_ref_evento')    
      ->join('tpl_tipo_libro','tpl_id=rev_id_tpl'); 
    $query=$this->db->get();
    return $query->result_array();
   }  
    public function obtener_libros($tipo_libro)
   {      
   $libros_abiertos= $this->db->query('SELECT * FROM lib_libro  AS lib 
   INNER JOIN tpl_tipo_libro tpl ON tpl.`tpl_id` = lib.`lib_tpl_id`
   WHERE lib.`lib_estado` = 1
   AND lib.`lib_tpl_id` ='.$tipo_libro)->result_array();

    return $libros_abiertos;
   } 

     public function user_ref()
   {      
   $user_ref= $this->db->query('SELECT * FROM users AS us 
INNER JOIN uxr_usuarioxrol uxr ON uxr.`uxr_id_usu` = us.`id`
INNER JOIN rol_rol rol ON rol.`rol_id` = uxr.`uxr_id_rol`
WHERE rol.`rol_id` = 8')->result_array();

    return $user_ref;
   }  
   

   public function informacion_matrimonio($id_contrayente){
   $info_matrimonio = $this->db->query("SELECT * FROM acm_acta_matrimonial AS acm 
    INNER JOIN per_persona per ON (per.`per_id` = acm.`acm_per_id_contrayente1` OR per.`per_id` = acm.`acm_per_id_contrayente2` )
    WHERE acm.`acm_per_id_contrayente1` =".$id_contrayente." OR acm.`acm_per_id_contrayente2` = ".$id_contrayente."
    GROUP BY acm.`acm_id`")->row_array();
   return $info_matrimonio;
   
   }

   public function verificar_existencia_en_libro($tabla, $campo,  $mat_id){
    $existencia = $this->db->query('select * from '.$tabla.' where '.$campo.'='.$mat_id)->row_array();
    return $existencia;

   }


   public function info_matri($mat_id){
    $data_matrimonio =  $this->db->query("SELECT *, CONCAT(per3.`per_nombre1`,' ', per3.`per_nombre2`,' ', per3.`per_apellido1`,' ', per3.`per_apellido2`) AS nombre_testigo1,
      CONCAT(per4.`per_nombre1`,' ', per4.`per_nombre2`,' ', per4.`per_apellido1`,' ', per4.`per_apellido2`) AS nombre_testigo2, per3.per_fecha AS fecha_testigo1 , per4.per_fecha AS fecha_testigo2,
      pro_testigo1.pro_nombre AS profesion_testigo1,  pro_testigo2.pro_nombre AS profesion_testigo2,
      mun_testigo1.`mun_nombre` AS muni_tes1, mun_testigo2.`mun_nombre` AS muni_tes2,
      dep_testigo1.`dep_nombre` AS depa_tes1, dep_testigo2.`dep_nombre` AS depa_tes2,
      mun_contrayente1.`mun_nombre` AS muni_contrayente1, mun_contrayente2.`mun_nombre` AS muni_contrayente2,
      dep_contrayente1.`dep_nombre` AS depa_contrayente1, dep_contrayente2.`dep_nombre` AS depa_contrayente2,
      doc_tes1.`doc_numero` AS doc1_tes1, doc_tes2.`doc_numero` AS doc2_tes2,
      doc.`doc_numero` AS doc_contrayente1, doc2.`doc_numero` AS doc_contrayente2,
      CONCAT(per1.`per_nombre1`,' ', per1.`per_nombre2`,' ', per1.`per_apellido1`,' ', per1.`per_apellido2`) AS nombre_contrayente1,
      CONCAT(per2.`per_nombre1`,' ', per2.`per_nombre2`,' ', per2.`per_apellido1`,' ', per2.`per_apellido2`) AS nombre_contrayente2,
      per1.per_fecha AS fecha_contrayente1 , per2.per_fecha AS fecha_contrayente2,
      pro_contrayente1.pro_nombre AS profesion_contrayente1,  pro_contrayente2.pro_nombre AS profesion_contrayente2,
      per1.`per_fecha` AS per_fecha_contrayente1,  per2.`per_fecha` AS per_fecha_contrayente2, rgm.`rgm_nombre` AS nombre_regimen,
      per1.`per_apellido1` AS primer_apellido_esposo, per2.`per_apellido1` AS primer_apellido_esposa, per2.`per_apellido2` AS segundo_apellido_esposa,
      CONCAT(padre_contrayente1.`per_nombre1`,' ', padre_contrayente1.`per_nombre2`,' ',padre_contrayente1.`per_apellido1`,' ', padre_contrayente1.`per_apellido2`) AS nombre_padre_contrayente1,
      CONCAT(padre_contrayente2.`per_nombre1`,' ', padre_contrayente2.`per_nombre2`,' ',padre_contrayente2.`per_apellido1`,' ', padre_contrayente2.`per_apellido2`) AS nombre_padre_contrayente2,
      CONCAT(madre_contrayente1.`per_nombre1`,' ', madre_contrayente1.`per_nombre2`,' ',madre_contrayente1.`per_apellido1`,' ', madre_contrayente1.`per_apellido2`) AS nombre_madre_contrayente1,
      CONCAT(madre_contrayente2.`per_nombre1`,' ', madre_contrayente2.`per_nombre2`,' ',madre_contrayente2.`per_apellido1`,' ', madre_contrayente2.`per_apellido2`) AS nombre_madre_contrayente2,
      pro_padre_contrayente1.pro_nombre AS profesion_padre_contrayente1, pro_madre_contrayente1.`pro_nombre` AS profesion_madre_contrayente1,
      pro_padre_contrayente2.pro_nombre AS profesion_padre_contrayente2, pro_madre_contrayente1.`pro_nombre` AS profesion_madre_contrayente2
      FROM acm_acta_matrimonial AS acm
      INNER JOIN per_persona per1 ON per1.`per_id` = acm.`acm_per_id_contrayente1`
      INNER JOIN per_persona per2 ON per2.`per_id` = acm.`acm_per_id_contrayente2`
      INNER JOIN per_persona per3 ON per3.`per_id` = acm.`acm_per_id_testigo1`
      INNER JOIN per_persona per4 ON per4.`per_id` = acm.`acm_per_id_testigo2` 
      INNER JOIN per_persona padre_contrayente1 ON padre_contrayente1.`per_id` = acm.`acm_per_id_padre1`
      INNER JOIN per_persona padre_contrayente2 ON padre_contrayente2.`per_id` = acm.`acm_per_id_padre2`
      INNER JOIN per_persona madre_contrayente1 ON madre_contrayente1.`per_id` = acm.`acm_per_id_madre1`
      INNER JOIN per_persona madre_contrayente2 ON madre_contrayente2.`per_id` = acm.`acm_per_id_madre2`
      LEFT JOIN dxp_documentoxpersona dxp ON dxp.`dxp_id_per` = per1.`per_id`
      LEFT JOIN dxp_documentoxpersona dxp2 ON dxp2.`dxp_id_per` = per2.`per_id`
      LEFT JOIN dxp_documentoxpersona dxp3 ON dxp3.`dxp_id_per` = per3.`per_id`
      LEFT JOIN dxp_documentoxpersona dxp4 ON dxp4.`dxp_id_per` = per4.`per_id`
      LEFT JOIN doc_documento doc ON doc.`doc_id` = dxp.`dxp_id_per`
      LEFT JOIN doc_documento doc2 ON doc2.`doc_id` = dxp2.`dxp_id_per`
      LEFT JOIN doc_documento doc_tes1 ON doc_tes1.`doc_id` = dxp3.`dxp_id_per`
      LEFT JOIN doc_documento doc_tes2 ON doc_tes2.`doc_id` = dxp4.`dxp_id_per`
      INNER JOIN ofi_oficio ofi ON ofi.`ofi_id` = acm.`acm_ofi_id`
      INNER JOIN pro_profesion pro_testigo1 ON pro_testigo1.`pro_id` = per3.`per_pro_id`
      INNER JOIN pro_profesion pro_testigo2 ON pro_testigo2.`pro_id` = per4.`per_pro_id`
      INNER JOIN pro_profesion pro_contrayente1 ON pro_contrayente1.`pro_id` = per1.`per_pro_id`
      INNER JOIN pro_profesion pro_contrayente2 ON pro_contrayente2.`pro_id` = per2.`per_pro_id`
      INNER JOIN pro_profesion pro_padre_contrayente1 ON pro_padre_contrayente1.`pro_id` = padre_contrayente1.`per_pro_id`
      INNER JOIN pro_profesion pro_madre_contrayente1 ON pro_madre_contrayente1.`pro_id` = madre_contrayente1.`per_pro_id`
      INNER JOIN pro_profesion pro_padre_contrayente2 ON pro_padre_contrayente2.`pro_id` = padre_contrayente2.`per_pro_id`
      INNER JOIN pro_profesion pro_madre_contrayente2 ON pro_madre_contrayente2.`pro_id` = madre_contrayente2.`per_pro_id`
      INNER JOIN mun_municipio mun_testigo1 ON mun_testigo1.`mun_id` = per3.`per_mun_id_domicilio` 
      INNER JOIN mun_municipio mun_testigo2 ON mun_testigo2.`mun_id` = per4.`per_mun_id_domicilio` 
      INNER JOIN dep_departamento dep_testigo1 ON dep_testigo1.`dep_id` = per3.`per_dep_id_nacimiento` 
      INNER JOIN dep_departamento dep_testigo2 ON dep_testigo2.`dep_id` = per4.`per_dep_id_nacimiento`
      INNER JOIN mun_municipio mun_contrayente1 ON mun_contrayente1.`mun_id` = per1.`per_mun_id_domicilio` 
      INNER JOIN mun_municipio mun_contrayente2 ON mun_contrayente2.`mun_id` = per2.`per_mun_id_domicilio` 
      INNER JOIN dep_departamento dep_contrayente1 ON dep_contrayente1.`dep_id` = per1.`per_dep_id_nacimiento` 
      INNER JOIN dep_departamento dep_contrayente2 ON dep_contrayente2.`dep_id` = per2.`per_dep_id_nacimiento`
      INNER JOIN nac_nacionalidad nac_contrayente1 ON nac_contrayente1.`nac_id` = per1.`per_nac_id`
      INNER JOIN nac_nacionalidad nac_contrayente2 ON nac_contrayente2.`nac_id` = per3.`per_nac_id`
      INNER JOIN rgm_regimen_matrimonio rgm ON rgm.`rgm_id` = acm.`acm_rgm_id` 
      WHERE acm.`acm_id` =".$mat_id)->row_array();
    return $data_matrimonio;
   }

}

