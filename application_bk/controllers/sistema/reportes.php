<?php
class Reportes extends CI_Controller {

    function __construct(){
        parent::__construct();
		$this->load->library('Grocery_CRUD');
        $this->load->model('reporteria');
    }
    
    function index()
    {
        if (!$this->tank_auth->is_logged_in()) {
		  redirect('/auth/login/');
		}
        else {			
		  $this->_cargarvista();
		}
	}

    function reporteria() 
    {
        $data['roles']=$this->reporteria->cargar_todo('rol_rol');
        $this->_cargarvista(null, $data);
    }  

    function buscar_tablas()
    {   
        $data=$this->db->query("SHOW TABLES FROM alcaldia");

        $tablas['response'] = true;
        $tablas['message'] = array();
        foreach($data->result() as $row)
            $tablas['message'][]= array('tabla_nombre' =>$row->Tables_in_alcaldia);

        echo json_encode( $tablas );
    }

    function buscar_campos()
    {   
        $tabla=$this->input->post('tab');
        $query="select * from ".$tabla;
        $data=$this->db->query($query);

        $campos['response'] = true;
        $campos['message'] = array();
        foreach($data->list_fields() as $row)
            $campos['message'][]= array('campo_nombre' =>$row);

        echo json_encode( $campos );
    }

    function guardar_query_reporte()
    {
        //Nombre y rol
        $rep_nombre=$this->input->post('rep_nombre');
        $rxr_id_rol=$this->input->post('rxr_id_rol');

        //cadenas de los datos del select y from de forma desordenada
        //$select=$this->input->post('sel');
        //$from=$this->input->post('fro');

        //cadenas de los datos del select y from de forma ordenada
        $from_html=$this->input->post('fro_html');
        $select_html=$this->input->post('sel_html');

        if($this->input->post('sel_fro_html')) {
            $rep_consulta=$this->input->post('sel_fro_html');
        }
        else {
            if($this->input->post('dis'))
                $dis="DISTINCT ";
            else
                $dis="";
            $rep_consulta=strtoupper("select ").$dis.$select_html." FROM ".$from_html;
        }
        $data_reporte=array(
                            "rep_nombre"=>$rep_nombre,
                            "rep_consulta"=>$rep_consulta,
							'rep_usu_mod' => $this->tank_auth->get_user_id()
                        );
        $this->reporteria->ingresar('rep_reporte', $data_reporte);
        
        $rxr_id_rep=$this->db->insert_id();
        $data_rxr=array(
                        "rxr_id_rep"=>$rxr_id_rep,
                        "rxr_id_rol"=>$rxr_id_rol
                    );
        
        if($this->reporteria->ingresar('rxr_reportexrol', $data_rxr)) {
            $guardado=true;
            if($this->db->query($rep_consulta)) {
                $resultado=true;
                $query=$rep_consulta;
            }
            else {
                $guardado=true;
                $resultado=false;
                $query="";
            }
        }
        else {
            $guardado=false;
            $resultado=false;
            $query="";
        }

        $arreglo= array(
           "guardado"=> $guardado,
           "resultado"=> $resultado,
           "query"=> $query,
           "rxr_id_rep"=>$rxr_id_rep
        );
        echo json_encode($arreglo);
    }

    function filtros($rep_id)
    {
        $data['reporte']=$this->reporteria->cargar_registro('rep_reporte','rep_id',$rep_id);
        $this->_cargarvista(null, $data);
    }

    function guardar_filtros()
    {
        $filtros_revision=$this->input->post('filtros_revision');
        $alias=$this->input->post('alias_nc'); 

        for($i=0;$i<count($filtros_revision);$i++) {
            if($filtros_revision[$i]!=0){
                $valores=explode(" ",$filtros_revision[$i]);
                if($alias[$i]=="") {
                    $posible_alias=explode("_",substr($valores[1],4));
                    if(count($posible_alias)>1)
                        $alias[$i]=ucwords($posible_alias[0]." ".$posible_alias[1]);
                    else
                        $alias[$i]=ucwords($posible_alias[0]);
                }
                $data_filtro=array(
                                    "fil_id_rep"=>$valores[0],
                                    "fil_nombre_tabla"=>$valores[1],
                                    "fil_nombre_campo"=>$valores[2],
                                    "fil_nombre_campo_relacion"=>$valores[3],
                                    "fil_alias_nombre_campo"=>$alias[$i]
                                );
                $this->reporteria->ingresar('fil_filtro', $data_filtro);
            }
        }   
        redirect('sistema/reportes/reporteria');
    }

    function gestion_consulta2()
    {
        $objeto_crud = new grocery_CRUD;
        $objeto_crud->set_table('rep_reporte');
        $objeto_crud->set_subject('Reporte');

        $objeto_crud->columns('rep_nombre');
        $objeto_crud->display_as('rep_nombre','Nombre del Reporte');
		$objeto_crud->unset_fields('rep_usu_mod','rep_fecha_mod');

        $objeto_crud->unset_add();  

        $output = $objeto_crud->render();
        $this->_cargarvista(null,$output);
    }

    function gestion_consulta1()
    {
        $objeto_crud = new grocery_CRUD;
        $objeto_crud->set_table('rxr_reportexrol');
        $objeto_crud->set_subject('Relación');
        $objeto_crud->set_relation('rxr_id_rep','rep_reporte','rep_nombre');
        $objeto_crud->set_relation('rxr_id_rol','rol_rol','rol_nombre');

        $output = $objeto_crud->render();
        $this->_cargarvista(null,$output);
    }

    function generar_reporte()
    {   
        $id_usu=$this->tank_auth->get_user_id();
        $rol=$this->reporteria->cargar_registro('uxr_usuarioxrol','uxr_id_usu',$id_usu);
        $data['reportes']=$this->reporteria->cargar_reportes($rol['uxr_id_rol']);
        $this->_cargarvista(null, $data);
    }

    function buscar_filtros()
    {
        $rep_id=$this->input->post('rep_id');
        $reporte=$this->reporteria->cargar_registro('rep_reporte','rep_id',$rep_id);
        $filtros=$this->reporteria->cargar_registros('fil_filtro','fil_id_rep',$rep_id);

        $rep_consulta=explode("FROM",$reporte['rep_consulta']);

        $data['valores'] = array();
        $data['response'] = true;

        foreach($filtros as $fil) {
            $tabla=$fil['fil_nombre_tabla'];
            $campo=$fil['fil_nombre_campo'];
            $alias_campo=$fil['fil_alias_nombre_campo'];
            $campo_relacion=$fil['fil_nombre_campo_relacion'];
            $tabla_id=substr($tabla, 0,3)."_id";

            if($tabla=="users")
                $tabla_id="id";

            if($tabla=="uni_unidad_medida")
                $tabla_id="uni_codigo";

            $alias="";
            $tabla_alias=explode(" ", $rep_consulta[1]);
            $tabla_alias[count($tabla_alias)]="";
            for($i=0;$i<count($tabla_alias);$i++) {
              if(substr($tabla_alias[$i],0,3)==substr($campo,0,3) && ($tabla_alias[$i+1]=="as" || $tabla_alias[$i+1]=="AS")) {
                    $alias=$tabla_alias[$i+2].".";
                    $i=count($tabla_alias);
                }				
            }

            $query="select distinct * from ".$tabla." where ".$tabla_id." IN (select ".$alias."".$campo." as ".$tabla_id." from ".$rep_consulta[1].")"; //ocupar es linea si solo se quieren los registros de la tabla relacionada que se encuetran como resultado de la consulta
			//$query="select distinct * from ".$tabla; //ocupar esta linea si se quieren todos los registros de la tabla relacionada con la consulta
            $mysql_query=$this->db->query($query);
            $opciones='';
            foreach($mysql_query->result() as $mq) {
                $opciones.='<option value="'.$mq->$tabla_id.'">'.$mq->$campo_relacion.'</option>';
            }
            $data['valores'][]=array("nombre_campo"=>$campo,"alias_campo"=>$alias_campo,"opciones"=>$opciones);
        }
        echo json_encode($data);
    }
	
	function buscar_filtros_campos()
	{
		$rep_id=$this->input->post('rep_id');
        $reporte=$this->reporteria->cargar_registro('rep_reporte','rep_id',$rep_id);
		
		$rep_consulta=explode("FROM",$reporte['rep_consulta']);
        $data['valores'] = array();
        $data['response'] = true;
		
		/*mysql_connect("192.168.1.117:3307","root","rootroot")or die(mysql_error());
		mysql_select_db("alcaldia");*/

		//$query="select * from ".$rep_consulta[1]; //ocupar esta linea si se quiere de filtros todos los campos de las tablas del query
		$query=$reporte['rep_consulta']; //ocupar esta linea si solo se quiere de filtros los campos que estan en el SELECT del query
		
		$resultado=mysql_query($query);
		$campos=mysql_num_fields($resultado);
		
		$opciones='';
		for ($i=0;$i<$campos;$i++) {
			$nombre=mysql_field_name($resultado, $i);
			$tip=mysql_field_type($resultado, $i);
			$banderas=mysql_field_flags($resultado, $i);
			$tipo=explode(" ",  $banderas);
			
			$alias="";
			//$mysql=mysql_query("SELECT * FROM ".$rep_consulta[1]); //ocupar esta linea si se quiere de filtros todos los campos de las tablas del query
			$mysql=mysql_query($query); //ocupar esta linea si solo se quiere de filtros los campos que estan en el SELECT del query
			for ($cc = 0; $cc < mysql_num_fields($mysql); ++$cc) {
				$campo_p = mysql_field_name($mysql, $cc);
				if($campo_p==$nombre) {
					$alias = mysql_field_table($mysql, $cc)."**";
				}
			}
			$opciones.='<option value="'.$alias."".$nombre.'">'.$nombre.'</option>';
		}	
		$data['valores'][]=array("nombre_campo"=>$nombre,"alias_campo"=>"Otros Filtros","opciones"=>$opciones);	
		echo json_encode($data);
	}

    function probar_query_reporte()
    {
        if($this->input->post('sel_fro_html')) {
            $rep_consulta=$this->input->post('sel_fro_html');
        }
        else {
            $from_html=$this->input->post('fro_html');
            $select_html=$this->input->post('sel_html');
            $rep_consulta="select ".$select_html." from ".$from_html;
        }
        
        if($this->db->simple_query($rep_consulta))
            $data['resultado']=true;
        else
            $data['resultado']=false;
        echo json_encode($data);
    }

    function generar_reporte_pantalla($consulta_externa="", $nombre_consulta_externa="")
    {
		if($consulta_externa!="") {
			$consulta=$consulta_externa;
			$reporte['rep_nombre']=$nombre_consulta_externa;
			$sumatoria="";
			$numero="";
		}
		else {
			$rep_id=$this->input->post('rep_id');        
			$reporte=$this->reporteria->cargar_registro('rep_reporte','rep_id',$rep_id);
			$filtros=$this->reporteria->cargar_registros('fil_filtro','fil_id_rep',$rep_id);
			
			
			$query_sin_from=explode("FROM",strtoupper($reporte['rep_consulta']));
			$query_mysql=mysql_query($reporte['rep_consulta']);
			
			$campo_alias_anterior="SELECT";
			$campo_nombre=array();
			for($z=0;$z<mysql_num_fields($query_mysql);$z++) {
				$campo_alias=strtoupper(mysql_field_name($query_mysql,$z));
				$campo_tabla=strtoupper(mysql_field_table($query_mysql,$z));
				if($campo_tabla!="") {
					if(strpos($query_sin_from[0],$campo_tabla.".".$campo_alias)) {
						$campo_nombre[$campo_alias]=$campo_tabla.".".$campo_alias;
					}
					else {
						if(strpos($query_sin_from[0],'"'.$campo_alias.'"')) {
							$busqueda_nombre_campo=explode('"'.$campo_alias.'"',$query_sin_from[0]);
							$busqueda_nombre_campo=explode(" ",$busqueda_nombre_campo[0]);
							if($busqueda_nombre_campo[count($busqueda_nombre_campo)-2]=="AS" || $busqueda_nombre_campo[count($busqueda_nombre_campo)-2]=="as")
								$campo_nombre[$campo_alias]=$busqueda_nombre_campo[count($busqueda_nombre_campo)-3];
						}
					}
				}
				else {
					$query_sin_from_sin_campo_anterior=explode($campo_alias_anterior,$query_sin_from[0]);	
					$query_sin_from_sin_campo_anterior_sin_campo_alias=explode($campo_alias,$query_sin_from_sin_campo_anterior[1]);
					$query_sin_from_sin_campo_anterior_sin_campo_alias=str_replace('",','',$query_sin_from_sin_campo_anterior_sin_campo_alias[0]);
					$query_sin_from_sin_campo_anterior_sin_campo_alias=str_replace('AS "','',$query_sin_from_sin_campo_anterior_sin_campo_alias);
					$campo_nombre[$campo_alias]=$query_sin_from_sin_campo_anterior_sin_campo_alias;
				}
				$campo_alias_anterior=$campo_alias;
			}
			
			$consulta=$reporte['rep_consulta'];
			$consulta_partida=explode("GROUP BY",strtoupper($consulta));
			$consulta=$consulta_partida[0];
			
			$i=1;
			$prueba_where=explode("WHERE",strtoupper($reporte['rep_consulta']));
			if(count($prueba_where)>1)
				$i=0;		
			foreach($filtros as $fil) {
				$campo=$fil['fil_nombre_campo'];
				$rep_consulta=explode("FROM",strtoupper($reporte['rep_consulta']));
				$tabla=$fil['fil_nombre_tabla'];
				$tabla_id=substr($tabla, 0,3)."_id";
	
				if($tabla=="users")
					$tabla_id="id";
	
				if($tabla=="uni_unidad_medida")
					$tabla_id="uni_codigo";
	
				$tabla_fil[]=$tabla;
				$campo_fil[]=$campo;
				$campo_alias_fil[]=$fil['fil_alias_nombre_campo'];
				$campo_rel_fil[]=$fil['fil_nombre_campo_relacion'];
				$campo_id_rel_fil[]=$tabla_id;
				$chk_campo[]=$this->input->post("chk_".$campo);
	
				$alias="";
				if($this->input->post($campo)!=0) {
					if($i) {
						$mysql=mysql_query("SELECT * FROM ".$rep_consulta[1]);
						for ($cc = 0; $cc < mysql_num_fields($mysql); ++$cc) {
							$campo_p = mysql_field_name($mysql, $cc);
							if($campo_p==$campo)
								$alias = mysql_field_table($mysql, $cc).".";
						}
						$consulta.=" WHERE ".$alias."".$campo."=".$this->input->post($campo);
						$i=0;
					}
					else 
						$consulta.=" AND ".$alias."".$campo."=".$this->input->post($campo);
				}
			}
			$query_sin_from=explode("FROM",strtoupper($consulta));
			if($this->input->post('bandera'))
				$vf=$this->input->post('vf');
			else {
				$vf="";
				$cv=$this->input->post('val_otros_filtros');
				for($zz=0;$zz<count($cv);$zz++)
					$vf.=$cv[$zz];
			}
			
			$condicion=explode("***",$vf);
			$sumatoria="";
			$numero="";
			for($u=0;$u<(count($condicion)-1);$u++) {
				$valores=explode("**",$condicion[$u]);
				$ali=strtoupper($valores[1]);
				$valores[1]=$campo_nombre[$ali];
				
				if($valores[2]=="is null" || $valores[2]=="is not null")
					if($i) {
						$consulta.=" WHERE ".$valores[1]." ".$valores[2];
						$i=0;
					}
					else {
						$consulta.=" AND ".$valores[1]." ".$valores[2];					
					}	
				else
					if($i) {
						$consulta.=" WHERE ".$valores[1]." ".$valores[2]." '".$valores[3]."'";
						$i=0;
					}
					else {
						$consulta.=" AND ".$valores[1]." ".$valores[2]." '".$valores[3]."'";					
					}	
				
				if($valores[4]==1) {
					if($numero!="")
						$numero.=", ";
					if(strpos(strtoupper($rep_consulta[0]),"DISTINCT"))
						$numero.=strtoupper("COUNT(DISTINCT(".$valores[1].")) AS '".$ali."'");
					else
						$numero.=strtoupper("COUNT(DISTINCT(".$valores[1].")) AS '".$ali."'");
				}
				
				if($valores[5]==1) {
					if($sumatoria!="")
						$sumatoria.=", ";
					if(strpos(strtoupper($rep_consulta[0]),"DISTINCT"))
						$sumatoria.=strtoupper("ROUND(SUM(".$valores[1]."),2) AS '".$ali."'");
					else
						$sumatoria.=strtoupper("ROUND(SUM(".$valores[1]."),2) AS '".$ali."'");
				}
			}	
		}
		if(isset($consulta_partida[1]))
			$consulta.=" GROUP BY".$consulta_partida[1];
		
		$cu=explode("FROM",$consulta);
		if($sumatoria!="")
			$consulta2="SELECT ".$sumatoria." FROM ".$cu[1];
		if($numero!="")
			$consulta3="SELECT ".$numero." FROM ".$cu[1];
		
        $mysql_query=mysql_query($consulta);
        $tabla='<br><br><h4 align="center">RESULTADOS</h4>';
        $tabla.='<table id="table_result" class="data_table dataTable highlight" aria-describedby="table_info">
            <thead>
                <tr role="row">';
        for($i=0;$i<mysql_num_fields($mysql_query);$i++) {
            $cam=mysql_field_name($mysql_query, $i);
            $tabla.='<th align="center" class="center sorting_asc"  role="columnheader" tabindex="0" aria-controls="table" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Column 1 : activate to sort column descending">
                        <span class="th">
                            <span class="arrow"></span>
                            <span class="icon en"></span>
                            <span class="title" align="center">'.str_replace("_"," ",strtoupper($cam)).'</span>
                        </span>
                    </th>';
        }
        $tabla.='</tr></thead><tbody id="sss" role="alert" aria-live="polite" aria-relevant="all">';

        $xx=1;
        while($mq=mysql_fetch_array($mysql_query)) {
            if($xx) {
                $css="impar";
                $xx=0;
            }
            else {
                $css="par";
                $xx=1;
            }
            $tabla.='<tr class="'.$css.'">';
            for($i=0;$i<mysql_num_fields($mysql_query);$i++) {
                $cam=mysql_field_name($mysql_query, $i);
                $tabla.="<td>";
                $valor=$mq[$cam];
                if($valor!="" && isset($tabla_fil)) {
                    for ($vv=0;$vv<count($campo_fil);$vv++) { 
                        if ($cam==$campo_fil[$vv]) {
                            if($valor>0) {
                                $nombre_tabla=$this->reporteria->verificar_registro($tabla_fil[$vv],array($campo_id_rel_fil[$vv]=>$valor));
                                $crf=$campo_rel_fil[$vv];
                                $valor=$nombre_tabla[$crf];
                            }
                        }
                        else {
                            if (strtoupper($cam)==strtoupper($campo_alias_fil[$vv])) {
                                if($valor>0) {
                                    $nombre_tabla=$this->reporteria->verificar_registro($tabla_fil[$vv],array($campo_id_rel_fil[$vv]=>$valor));
                                    $crf=$campo_rel_fil[$vv];
                                    $valor=$nombre_tabla[$crf];
                                }
                            }
                        }
                    }
                }
                $tabla.=$valor."</td>";
            }
            $tabla.="</tr>";
        }
        $tabla.='</tbody></table><br>';
		
		$valores_adicionales='<table>';
		
		if($this->input->post('bandera')) 
			$width=300;
		else {
			$width=175;
			$tabla.='<br>';
		}
		
		if($sumatoria!="") {
			$mysql_query2=mysql_query($consulta2);
			for($i=0;$i<mysql_num_fields($mysql_query2);$i++) {
				$cam=mysql_field_name($mysql_query2, $i);
				$val=mysql_result($mysql_query2, 0, $cam);
				$valores_adicionales.='<tr><td align="left" width="'.$width.'">Sumatoria de <strong>'.str_replace("_"," ",strtoupper($cam)).'</strong>: </td><td align="right" width="100">'.$val.'</td></tr>';
			}
		}
		
		if($numero!="") {
			$mysql_query3=mysql_query($consulta3);
			for($i=0;$i<mysql_num_fields($mysql_query3);$i++) {
				$cam=mysql_field_name($mysql_query3, $i);
				$val=mysql_result($mysql_query3, 0, $cam);
				$valores_adicionales.='<tr><td align="left" width="'.$width.'">Número de <strong>'.str_replace("_"," ",strtoupper($cam)).'</strong>: </td><td align="right" width="100">'.$val.'</td></tr>';
			}
		}
		
		$valores_adicionales.='</table>';
		
        $data['resultado']=$tabla.$valores_adicionales;

        if($this->input->post('bandera')) {
            $data['bandera']=true;
            echo json_encode($data);
        }
        else {
            $this->load->library('PDF');
           	$reporte=$this->pdf->reportePDF('sistema/reportes/generar_pdf_reporte',$data,$reporte['rep_nombre'],'L');
        }
    }
	
	function expotar_reporte()
	{
		$rep_id=$this->input->post('rep_id');        
		$reporte=$this->reporteria->cargar_registro('rep_reporte','rep_id',$rep_id);
		$filtros=$this->reporteria->cargar_registros('fil_filtro','fil_id_rep',$rep_id);

		$query_sin_from=explode("FROM",strtoupper($reporte['rep_consulta']));
		$query_mysql=mysql_query($reporte['rep_consulta']);
		
		$campo_alias_anterior="SELECT";
		$campo_nombre=array();
		for($z=0;$z<mysql_num_fields($query_mysql);$z++) {
			$campo_alias=strtoupper(mysql_field_name($query_mysql,$z));
			$campo_tabla=strtoupper(mysql_field_table($query_mysql,$z));
			if($campo_tabla!="") {
				if(strpos($query_sin_from[0],$campo_tabla.".".$campo_alias)) {
					$campo_nombre[$campo_alias]=$campo_tabla.".".$campo_alias;
				}
				else {
					if(strpos($query_sin_from[0],'"'.$campo_alias.'"')) {
						$busqueda_nombre_campo=explode('"'.$campo_alias.'"',$query_sin_from[0]);
						$busqueda_nombre_campo=explode(" ",$busqueda_nombre_campo[0]);
						if($busqueda_nombre_campo[count($busqueda_nombre_campo)-2]=="AS" || $busqueda_nombre_campo[count($busqueda_nombre_campo)-2]=="as")
							$campo_nombre[$campo_alias]=$busqueda_nombre_campo[count($busqueda_nombre_campo)-3];
					}
				}
			}
			else {
				$query_sin_from_sin_campo_anterior=explode($campo_alias_anterior,$query_sin_from[0]);	
				$query_sin_from_sin_campo_anterior_sin_campo_alias=explode($campo_alias,$query_sin_from_sin_campo_anterior[1]);
				$query_sin_from_sin_campo_anterior_sin_campo_alias=str_replace('",','',$query_sin_from_sin_campo_anterior_sin_campo_alias[0]);
				$query_sin_from_sin_campo_anterior_sin_campo_alias=str_replace('AS "','',$query_sin_from_sin_campo_anterior_sin_campo_alias);
				$campo_nombre[$campo_alias]=$query_sin_from_sin_campo_anterior_sin_campo_alias;
			}
			$campo_alias_anterior=$campo_alias;
		}
			
		$consulta=$reporte['rep_consulta'];
		$consulta_partida=explode("GROUP BY",strtoupper($consulta));
		$consulta=$consulta_partida[0];
		
		$i=1;
		$prueba_where=explode("WHERE",strtoupper($reporte['rep_consulta']));
		if(count($prueba_where)>1)
			$i=0;		
		foreach($filtros as $fil) {
			$campo=$fil['fil_nombre_campo'];
			$rep_consulta=explode("FROM",strtoupper($reporte['rep_consulta']));
			$tabla=$fil['fil_nombre_tabla'];
			$tabla_id=substr($tabla, 0,3)."_id";

			if($tabla=="users")
				$tabla_id="id";

			if($tabla=="uni_unidad_medida")
				$tabla_id="uni_codigo";

			$tabla_fil[]=$tabla;
			$campo_fil[]=$campo;
			$campo_alias_fil[]=$fil['fil_alias_nombre_campo'];
			$campo_rel_fil[]=$fil['fil_nombre_campo_relacion'];
			$campo_id_rel_fil[]=$tabla_id;
			$chk_campo[]=$this->input->post("chk_".$campo);

			$alias="";
			if($this->input->post($campo)!=0) {
				if($i) {
					$mysql=mysql_query("SELECT * FROM ".$rep_consulta[1]);
					for ($cc = 0; $cc < mysql_num_fields($mysql); ++$cc) {
						$campo_p = mysql_field_name($mysql, $cc);
						if($campo_p==$campo)
							$alias = mysql_field_table($mysql, $cc).".";
					}
					$consulta.=" WHERE ".$alias."".$campo."=".$this->input->post($campo);
					$i=0;
				}
				else 
					$consulta.=" AND ".$alias."".$campo."=".$this->input->post($campo);
			}
		}
		if($this->input->post('bandera'))
			$vf=$this->input->post('vf');
		else {
			$vf="";
			$cv=$this->input->post('val_otros_filtros');
			for($zz=0;$zz<count($cv);$zz++)
				$vf.=$cv[$zz];
		}
		
		$condicion=explode("***",$vf);
		$sumatoria="";
		$numero="";
		for($u=0;$u<(count($condicion)-1);$u++) {
			$valores=explode("**",$condicion[$u]);
			$ali=strtoupper($valores[1]);
			$valores[1]=$campo_nombre[$ali];

			if($valores[2]=="is null" || $valores[2]=="is not null")
				if($i) {
					$consulta.=" WHERE ".$valores[1]." ".$valores[2];
					$i=0;
				}
				else {
					$consulta.=" AND ".$valores[1]." ".$valores[2];					
				}	
			else
				if($i) {
					$consulta.=" WHERE ".$valores[1]." ".$valores[2]." '".$valores[3]."'";
					$i=0;
				}
				else {
					$consulta.=" AND ".$valores[1]." ".$valores[2]." '".$valores[3]."'";					
				}	
			
			if($valores[4]==1) {
				if($numero!="")
					$numero.=", ";
				if(strpos(strtoupper($rep_consulta[0]),"DISTINCT"))
					$numero.=strtoupper("COUNT(DISTINCT(".$valores[1].")) AS '".$ali."'");
				else
					$numero.=strtoupper("COUNT(DISTINCT(".$valores[1].")) AS '".$ali."'");
			}
			
			if($valores[5]==1) {
				if($sumatoria!="")
					$sumatoria.=", ";
				if(strpos(strtoupper($rep_consulta[0]),"DISTINCT"))
					$sumatoria.=strtoupper("ROUND(SUM(".$valores[1]."),2) AS '".$ali."'");
				else
					$sumatoria.=strtoupper("ROUND(SUM(".$valores[1]."),2) AS '".$ali."'");
			}
		}	
		if(isset($consulta_partida[1]))
			$consulta.=" GROUP BY".$consulta_partida[1];
		
		$cu=explode("FROM",$consulta);
		if($sumatoria!="")
			$consulta2="SELECT ".$sumatoria." FROM ".$cu[1];
		if($numero!="")
			$consulta3="SELECT ".$numero." FROM ".$cu[1];
		
        $mysql_query=mysql_query($consulta);
		$data['col']=mysql_num_fields($mysql_query);
        $tabla='
                <tr>';
        for($i=0;$i<mysql_num_fields($mysql_query);$i++) {
            $cam=mysql_field_name($mysql_query, $i);
            $tabla.='<td align="center"><strong>'.str_replace("_"," ",strtoupper($cam)).'</strong></td>';
        }
        $tabla.='</tr>';

        while($mq=mysql_fetch_array($mysql_query)) {
            $tabla.='<tr>';
            for($i=0;$i<mysql_num_fields($mysql_query);$i++) {
                $cam=mysql_field_name($mysql_query, $i);
                $tabla.="<td>";
                $valor=$mq[$cam];
                if($valor!="" && isset($tabla_fil)) {
                    for ($vv=0;$vv<count($campo_fil);$vv++) { 
                        if ($cam==$campo_fil[$vv]) {
                            if($valor>0) {
                                $nombre_tabla=$this->reporteria->verificar_registro($tabla_fil[$vv],array($campo_id_rel_fil[$vv]=>$valor));
                                $crf=$campo_rel_fil[$vv];
                                $valor=$nombre_tabla[$crf];
                            }
                        }
                        else {
                            if (strtoupper($cam)==strtoupper($campo_alias_fil[$vv])) {
                                if($valor>0) {
                                    $nombre_tabla=$this->reporteria->verificar_registro($tabla_fil[$vv],array($campo_id_rel_fil[$vv]=>$valor));
                                    $crf=$campo_rel_fil[$vv];
                                    $valor=$nombre_tabla[$crf];
                                }
                            }
                        }
                    }
                }
                $tabla.=utf8_decode($valor)."</td>";
            }
            $tabla.="</tr>";
        }
        $tabla.='</table>';
		
		$valores_adicionales='<br><table>';
		
		if($sumatoria!="") {
			$mysql_query2=mysql_query($consulta2);
			for($i=0;$i<mysql_num_fields($mysql_query2);$i++) {
				$cam=mysql_field_name($mysql_query2, $i);
				$val=mysql_result($mysql_query2, 0, $cam);
				$valores_adicionales.='<tr><td align="left">Sumatoria de <strong>'.str_replace("_"," ",strtoupper($cam)).'</strong>: </td><td align="right" width="100">'.$val.'</td></tr>';
			}
		}
		
		if($numero!="") {
			$mysql_query3=mysql_query($consulta3);
			for($i=0;$i<mysql_num_fields($mysql_query3);$i++) {
				$cam=mysql_field_name($mysql_query3, $i);
				$val=mysql_result($mysql_query3, 0, $cam);
				$valores_adicionales.='<tr><td align="left">Número de <strong>'.str_replace("_"," ",strtoupper($cam)).'</strong>: </td><td align="right" width="100">'.$val.'</td></tr>';
			}
		}
		
		$valores_adicionales.='</table>';
		
		$data['titulo']=$reporte['rep_nombre'];
        $data['resultado']=$tabla.$valores_adicionales;
	 	$this->load->view('sistema/reportes/expotar_reporte',$data);
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
?>