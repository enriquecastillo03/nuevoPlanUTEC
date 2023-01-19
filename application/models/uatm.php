<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Descripcion
 * Accede a BD y devuelve todos los datos obtenidos desde una consulta 
 * especifica de pago mensual de contribuyentes de UATM y establece cargos o 
 * abonos a cuenta corriente por inmueble y empresa
 * 
 * @author:     Alan Alvarenga - Grupo Satelite 
 * @version:    v0.9 - 2013/07/13
 * @since:      2013/06/10 
 * @package:    Alcaldia - Chalatenango
 * =================================================================================
 * Bitacora:
 * 2013/07/11
 * + _do_funo - Construye F1-ISAM para las cuotas de plan de pago
 * + get_detalle_cuota - Obtiene detalles de cuota y datos necesarios para 
 *                         la generacion de F1-ISAM
 * + get_detalle_plan - Obtiene detalles de cuotas por ID de plan de pago
 * 
 * + get_plan_pago - Obtiene Informacion basica del plan de pago por ID de inmueble 
 *                     o ID de cuenta corriente
 * ------------------------------------------------------------------------------
 * 2013/07/10
 * + get_estado_cuenta_plan - Retorna el monto de la sumatoria de los cargos 
 *                             realizados a una cuenta
 * + do_plan_pago - Genera plan de pago por ID de Cuenta Corriente 
 * 
 * por Alan Alvarenga
 * --------------------------------------------------------------------------------
 * 2013/07/03
 * + do_plan_pago_previa - Calcular # de cuotas pendiente por mensualidad a 
 *                         cancelar en plan de pago
 * 
 * + get_consolidad_cuenta - Obtener el consolidado de cuenta por ID de inmueble 
 *                             o ID de cuenta corriente
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/21
 * + do_cargo_mensual_inmueble - Realizar cargos a cuenta corriente de inmuebles 
 *                              (corte mensual)
 * + do_cargo_mensual_empresa - Realiza cargos a cuenta corriente de empresas 
 *                              (corte mensual)
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/17
 * + set_cargo_cuenta - Insercion por lotes de cargos o abonos a detalles de cuenta 
 *                      corriente calculando el monto de cargo o abono en cada 
 *                      insercion de acuerdo a la tasa de cobro correspondiente 
 *                      para la fecha de ingreso
 * por Alan Alvarenga
 * --------------------------------------------------------------------------------
 * 2013/06/14 
 * + get_diferencia_meses - Obtiene la diferencia en meses de un rango de fechas
 *                          determinado
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/13
 * + get_numero_cuenta_inm - Obtiene numero de cuenta por id de inmueble
 * por Alan Alvarenga
 * 2013/06/11
 * + do_funo_isam - Crea un nuevo F1-ISAM y asocia los detalles de cuenta corriente 
 *                  con el F1-ISAM recien creado[BACK-END]
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/10
 * + get_propiedad - Devuelve CI Obj con todas las propiedades pertenecientes a una
 *                   persona despues de realizar una busqueda por id persona[BACK-END]
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 * 2013/06/10
 * + get_estado_cuenta - Devuelve el estado de cuenta corriente por inmueble (Lista
 *                       todos los detalles de cuenta por servicio pertenecientes
 *                       a un inmueble especifico)[BACK-END]
 * por Alan Alvarenga
 * ---------------------------------------------------------------------------------
 */
class Uatm extends CI_Model {
    /**
     * Necesario para establecer la zona horaria
     */
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/El_Salvador');
    }
    /*
        Query String that contains all the parameter to be send into MYSQL
     */
    public $query;

    /**
     * Devuelve CI Obj con todas las propiedades pertenecientes a una
     * persona despues de realizar una busqueda por id persona
     * @param  Integer  $user_id    Persona ID
     * @return CI Object            Resultado de la busqueda de inmuebles por persona
     */
    public function get_propiedad( $user_id )
    {
        $this->query = "SELECT ixc_id, ixc_id_con,ixc_id_inm AS inm_id, con_id, inm_direccion AS direccion,
                        inm_descripcion AS descripcion, inm_estado, inm_niveles AS niveles,
                        inm_cod_catastral AS cod_castastral, ubi_zona AS zona, uxi_id_inm, uxi_id_uso, 
                        uso_descripcion AS uso
                        FROM ixc_inmueblexcontribuyente
                        INNER JOIN con_contribuyente 
                            ON con_id = ixc_id_con
                        INNER JOIN inm_inmueble 
                            ON inm_id = ixc_id_inm
                        INNER JOIN ubi_ubicacion 
                            ON ubi_ubicacion.ubi_id = inm_id_ubi
                        INNER JOIN uxi_usoxinmueble 
                            ON inm_id = uxi_id_inm
                        INNER JOIN uso_uso 
                            ON uso_id = uxi_id_uso
                        INNER JOIN cxi_cuenta_corrientexinmueble
                            ON cxi_id_inm = inm_id
                        INNER JOIN cnt_cuenta_corriente
                            ON cnt_id = cxi_id_cnt
                        WHERE con_id = ? 
                            AND inm_estado = 1
                            AND ( cnt_estado = 1 OR cnt_estado = 4) ";

        //Run query and Bind parameters
        $result = $this->db->query( $this->query, $user_id );
        

        return ( $result->num_rows() > 0 )
                ? $result 
                : false;
    }

    /**
     * Devuelve el estado de cuenta corriente por inmueble (Lista todos los detalles de
     * cuenta por servicio pertenecientes a un inmueble especifico)
     * @param  Integer $inm_id [description]
     * @return [type]         [description]
     */
    public function get_estado_cuenta( $inm_id )
    {
        
        
        $this->query = "SELECT inm_id,
                               cxi_id_inm,
                               cxi_id_cnt,
                               cnt_id,
                               sxc_cnt_id,
                               sxc_id_srv,
                               det_id,
                               det_fecha,
                               det_monto,
                               det_estado,
                               srv_id,
                               srv_nombre
                        FROM inm_inmueble
                        INNER JOIN cxi_cuenta_corrientexinmueble ON inm_id = cxi_id_inm
                        INNER JOIN cnt_cuenta_corriente ON cnt_id = cxi_id_cnt
                        INNER JOIN sxc_servicioxcuenta_corriente ON cnt_id = sxc_cnt_id
                        INNER JOIN det_detalle_cntc ON sxc_id = det_id_sxc
                        INNER JOIN srv_servicio ON srv_id = sxc_id_srv
                        WHERE det_estado = 1
                            AND inm_id = ?";

        //Run query and Bind parameters
        $result = $this->db->query( $this->query, $inm_id );
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //se agrega este arreglo con los meses pero con letras esto con el proposito de formatear la fecha;
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Deciembre");
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //se declaran 3 arreglos donde se ira almacenando las fechas transformadas $fechas2 y en $fecha se ira almacenando toda la informacion relacionada con dicha fecha
        $fechas = array();
        $fechas2 = array();
        $fecha = array();
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //recorremos cada data almacenada en $result
        foreach( $result->result() as $value ){
        
        $meses_dif = $this->get_diferencia_meses( $value->det_fecha,  date('Y-m-d') );
        
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //transformamos la fecha obtenida desde la base por ejemplo 2013/05/23 => May 2013 
        $fecha[0]  =(date('Y',strtotime($value->det_fecha)).' - '.$meses[date('n',strtotime($value->det_fecha))-1]);;
        $fecha[1]  = $value->srv_nombre; //name of services
        $fecha[2]  = (float)$value->det_monto; //cost of the services
        $fecha[3]  = $value->det_id; //id's of the services
        $fecha[4]  = $meses_dif->meses;
        $fechas[]  = $fecha;
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //aca comparamos fechas para no mostrar mas de 1 vez un mes en especifico       
        if(!in_array($fecha[0],$fechas2)){
            $fechas2[] = $fecha[0];
        }
        }
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //creamos otro array que sera el que se retornara a pagos.php
        $toda_informacion = array();
        
        //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
        //recorremos el array $fechas2 con el fin de obtener todos los meses transformados  y almacenarlos en una posicion de $informacion 
        foreach($fechas2 as $mes){
         $informacion[0] = $mes;
         $services       = '';
         $cost           = 0;
         $ids            = '';
         $valores        = '';
         //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
         //recorremos la data que contiene los nombres de los servicios los id's de los detalles y el monto final y se almacenan en las variables detalladas
         foreach($fechas as $row){
                 if($mes == $row[0]){
                                     $services.= $row[1] . ' ';
                                     $cost+= $row[2] ;
                                     $ids.=$row[3] . ' ';
                                     $valores = $row[4];
                                    }
                                 }
         //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
         //se almacena en otro arreglo la informacion obtenida de la data y se almacena en las posiciones de dicho arreglo
         $informacion[1] = $services;//se almacena todos los nombres de los servicios
         $informacion[2] = $cost; //se almacena todos los costos de los servicios
         $informacion[3] = $ids; //se almacena todos los id de los detalles
         $informacion[4] = $valores;
         //**Ronald Ortiz Sunday,June 22th,2013 11:47:10 md
         //se le pasa toda la data de $informacion al arreglo que retornaremos a pagos.php
         $toda_informacion[] = $informacion;
         
        }
        
        return ( count($toda_informacion) > 0 )
                ? $toda_informacion 
                : false;
                
    }

    /**
     * Obtiene un listado de empresas por contribuyente ID
     * @param  integer $id      ID del contribuyente
     * @return json             Resultado en formato json
     */
    public function get_user_empresa($id = 0)
    {
        
        $query_empresa =   "SELECT emp_id,
                                   nem_nombre,
                                   dir_ubicacion,
                                   group_concat(gir_nombre separator ', ') AS giros,
                                   ace_nombre,
                                   cnt_id
                            FROM exc_empresaxcontribuyente
                            INNER JOIN emp_empresa ON emp_id = exc_id_emp
                            INNER JOIN nem_nombre_empresa ON nem_id_emp = emp_id
                            INNER JOIN exc_empresaxcuentac ON exc_empresaxcuentac.exc_id_emp = emp_id
                            INNER JOIN cnt_cuenta_corriente ON cnt_id = exc_id_cnt
                            INNER JOIN dir_direccion ON dir_id_emp = emp_id
                            INNER JOIN exg_empresaxgiro ON exg_id_emp = emp_id
                            INNER JOIN gir_giro ON gir_id = exg_id_gir
                            INNER JOIN ace_actividad_economica ON ace_id = gir_id_ace
                            INNER JOIN con_contribuyente ON con_id = exc_id_con
                            WHERE con_id = ?
                                AND nem_tipo_id = 1
                                AND nem_estado = 1
                                AND emp_estado = 1
                                AND dir_estado = 1
                            GROUP BY nem_nombre";

        $data_empresa = array($id);

        $result = $this->db->query( $query_empresa, $data_empresa );

        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ($result->result() as $row ) {

                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->cnt_id,
                    'emp_nombre'        => $row->nem_nombre,
                    'emp_direccion'     => $row->dir_ubicacion,
                    'emp_giros'         => $row->giros,
                    'emp_actividad'     => $row->ace_nombre
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
            $data['message'][] = array(
                'id'                => false,
                'emp_nombre'        => '',
                'emp_direccion'     => '',
                'emp_giros'         => '',
                'emp_actividad'     => ''
                );
            
            return json_encode($data);
        }
    }

    /**
     * Obtiene el estado de cuenta de una empresa por ID de cuenta y devuelve un 
     * array de tipo json con los resultados obtenidos.
     * @param  integer $cnt_id      ID de cuenta Corriente
     * @return json                 Listado de cargos mensuales agrupados por mes   
     */
    public function get_estado_cuenta_empresa($cnt_id = 0)
    {
        $query_estado ="SELECT det_fecha,
                               GROUP_CONCAT(srv_nombre SEPARATOR ', ') AS servicios,
                               SUM(det_monto) AS monto,
                               GROUP_CONCAT(det_id SEPARATOR ' ') AS detalles
                        FROM sxc_servicioxcuenta_corriente
                        INNER JOIN cnt_cuenta_corriente ON cnt_id = sxc_cnt_id
                        INNER JOIN det_detalle_cntc ON sxc_id = det_id_sxc
                        INNER JOIN srv_servicio ON srv_id = sxc_id_srv
                        WHERE det_estado = 1
                            AND cnt_id = ?
                        GROUP BY det_fecha";

        $data_estado = array( $cnt_id );

        $result = $this->db->query( $query_estado, $data_estado );

        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ( $result->result() as $row ) {

                $meses_dif = $this->get_diferencia_meses( $row->det_fecha,  date('Y-m-d') );
                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->detalles,
                    'det_fecha'         => $row->det_fecha,
                    'det_monto'         => $row->monto,
                    'det_servicio'      => $row->servicios,
                    'det_fecha_old'     => $meses_dif->meses
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
            $data['message'][] = array(
                    'id'                => false,
                    'det_fecha'         => '',
                    'det_monto'         => '',
                    'det_servicio'      => '',
                    'det_fecha_old'     => 0
                    );
            
            return json_encode($data);
        }

    }

    /**
     * Crea un nuevo F1-ISAM y asocia los detalles de cuenta corriente con el F1-ISAM recien creado[BACK-END]
     * @param  array    $fun_detalle       Detalle(s) seleccionados a facturar en el recibo F1-ISAM
     * @param  string   $fun_contribuyente Nombre del contribuyente
     * @param  double   $fun_subtotal      Subtotal de compra
     * @param  double   $fun_concepto      Concepto a facturar en rebico F1-ISAM
     * @return int                         ID recibo a cancelar en caja
     */
     
     
    public function do_funo_isam($fun_detalle, $fun_contribuyente, $fun_subtotal, $fun_total, $fun_multa = 0, $fun_interes = 0, $fun_concepto)
    {
        //Calcular total a facturar en F1-ISAM - 5% correspondiente a fiestas patronales.
        $total = 0;
        $impuesto = ( $fun_subtotal * 0.05 );
        $total = $total + $impuesto + $fun_subtotal;
        
        //Datos a insertar en F1-ISAM
        $data = array(
            'fun_concepto'      => $fun_concepto,
            'fun_fecha'         => date('Y-m-d'),
            'fun_subtotal'      => $fun_subtotal,
            'fun_impuesto'      => $impuesto,
            'fun_total'         => $fun_total,
            'fun_contribuyente' => $fun_contribuyente,
            'fun_multa'         => $fun_multa,
            'fun_interes'       => $fun_interes,
            'fun_creado_por'    => $this->session->userdata('user_id')
             );

        /*
            Run query
            INSERT INTO fun_funo (fun_concepto,fun_fecha,fun_subtotal,fun_impuesto,fun_total,fun_contribuyente)
            VALUES {?}, {?}, {?}, {?}, {?}, {?}
         */
         
        $this->db->insert('fun_funo', $data);

        //Obtener id del F1-ISAM recien creado
        $funo_id = $this->db->insert_id();

        
        //Para cada detalle de cuenta corriente dentro de fun_detalle 
        foreach ($fun_detalle as $detalle) {
            
            //Obtener el id del detalle cuenta corriente
            //ronald 23-06-2013
            
            $detalle_id = explode( " ", $detalle['det_id'] );
            
            foreach($detalle_id as $id){
                if($id != ""){
                    //Datos a insertar en fxd_funoxdetalle_cntc
                    $data = array(
                        'fxd_id_det' => $id,
                        'fxd_id_fun' => $funo_id
                         );
                      
                      
                    //INSERT INTO fxd_funoxdetalle_cntc (fxd_id_det, fxd_id_fun) VALUES ({?}, {?})
                    $this->db->insert('fxd_funoxdetalle_cntc', $data);
                }
            }
            
        }

        return ( isset( $funo_id ) )
                ? $funo_id
                : false;
    }

    /**
     * Obtiene numero de cuenta por id de inmueble
     * @param  integer $inm_id  ID del inmueble
     * @return json             JSON response con los datos de la cuenta corriente por inmueble
     */
    public function get_numero_cuenta_inm( $inm_id )
    {

        $this->query ="SELECT cxi_id, cxi_id_inm, cxi_id_cnt, cnt_id,
                                cnt_numero, cnt_tipo_cnt, cnt_estado
                        FROM cxi_cuenta_corrientexinmueble 
                        INNER JOIN cnt_cuenta_corriente 
                            ON cnt_id = cxi_id_cnt
                        WHERE cxi_id_inm = ? 
                        AND ( cnt_estado = 1 OR cnt_estado = 4 )";

        //Run query 
        $result = $this->db->query( $this->query, $inm_id );

        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ($result->result() as $row ) {

                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->cnt_id,
                    'cnt_numero'        => $row->cnt_numero,
                    'cnt_tipo'          => $row->cnt_tipo_cnt,
                    'cnt_estado'        => $row->cnt_estado
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            $data['message'][] = array(
                    'id'                => false,
                    'cnt_numero'        => '',
                    'cnt_tipo'          => '',
                    'cnt_estado'        => ''
                    );
            
            return json_encode($data);
        }
    }




public function get_numero_cuenta_emp( $inm_id )
    {

        $this->query ="SELECT exc_id, exc_id_emp, exc_id_emp, cnt_id,
                                cnt_numero, cnt_tipo_cnt, cnt_estado
                        FROM `exc_empresaxcuentac` 
                        INNER JOIN cnt_cuenta_corriente 
                            ON cnt_id = exc_id_cnt
                        WHERE exc_id_cnt= ? 
                        AND ( cnt_estado = 1 OR cnt_estado = 4 )";

        //Run query 
        $result = $this->db->query( $this->query, $inm_id );
   
        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */


        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ($result->result() as $row ) {

                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->cnt_id,
                    'cnt_numero'        => $row->cnt_numero,
                    'cnt_tipo'          => $row->cnt_tipo_cnt,
                    'cnt_estado'        => $row->cnt_estado
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            $data['message'][] = array(
                    'id'                => false,
                    'cnt_numero'        => '',
                    'cnt_tipo'          => '',
                    'cnt_estado'        => ''
                    );
            
            return json_encode($data);
        }
    }
    /**
     * Obtiene servicios por ID de cuenta corriente 
     * @param  integer  $id    id cuenta corriente
     * @param  integer  $corte tiempo de corte 
     * @return [type]         [description]
     */
    public function get_servicios_cuenta( $id, $corte = 30 )
    {

        $this->query ="SELECT sxc_id, sxc_id_srv, srv_nombre, sxc_cnt_id, sxc_base_imponible, srv_tiempo_corte 
                        FROM sxc_servicioxcuenta_corriente
                        INNER JOIN srv_servicio 
                            ON sxc_id_srv = srv_id
                        WHERE sxc_cnt_id = ? 
                        AND srv_tiempo_corte = ?
                        AND sxc_estado =1
                        ";

        $data = array(
            'sxc_cnt_id'        => $id,
            'srv_tiempo_corte'  => $corte
            );

        //Run query 
        $result = $this->db->query( $this->query, $data );

        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ($result->result() as $row ) {

                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->sxc_id,
                    'inm_base_imp'      => $row->sxc_base_imponible,
                    'inm_corte'         => $row->srv_tiempo_corte,
                    'inm_cnt_id'        => $row->sxc_cnt_id,
                    'inm_srv_nombre'    => $row->srv_nombre
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            $data['message'][] = array(
                    'id'                => false,
                    'inm_base_imp'      => '',
                    'inm_corte'         => '',
                    'inm_cnt_id'        => '',
                    'inm_srv_nombre'    => ''
                    );
            }
            
            return json_encode($data);
    }


        public function get_servicios_cuenta_empresa( $id, $corte = 360 )
    {

        $this->query ="SELECT sxc_id, sxc_id_srv, srv_nombre, sxc_cnt_id, sxc_base_imponible, srv_tiempo_corte 
                        FROM sxc_servicioxcuenta_corriente
                        INNER JOIN srv_servicio 
                            ON sxc_id_srv = srv_id
                        WHERE sxc_cnt_id = ? 
                        AND srv_tiempo_corte = ?
                        AND sxc_estado =1
                        ";

        $data = array(
            'sxc_cnt_id'        => $id,
            'srv_tiempo_corte'  => $corte
            );

        //Run query 
        $result = $this->db->query( $this->query, $data );
 
        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $result->num_rows() > 0 ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            foreach ($result->result() as $row ) {

                //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'id'                => $row->sxc_id,
                    'inm_base_imp'      => $row->sxc_base_imponible,
                    'inm_corte'         => $row->srv_tiempo_corte,
                    'inm_cnt_id'        => $row->sxc_cnt_id,
                    'inm_srv_nombre'    => $row->srv_nombre
                    );
            }

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            $data['message'][] = array(
                    'id'                => false,
                    'inm_base_imp'      => '',
                    'inm_corte'         => '',
                    'inm_cnt_id'        => '',
                    'inm_srv_nombre'    => ''
                    );
            }
            
            return json_encode($data);
    }

    /**
     * Obtiene la diferencia en meses de un rango de fechas determinado
     * @param  date $inicio Inicio rango de fecha
     * @param  date $fin    Fin rango de fecha
     * @return CI Query Object         Diferencia en meses de los rangos de fecha
     */
    public function get_diferencia_meses( $inicio, $fin )
    {

        //Query
        $this->query ="SELECT TIMESTAMPDIFF(MONTH, ?, ?) as meses";

        //Set rango de fechas
        $data = array($inicio, $fin);

        //Run query 
        $result = $this->db->query( $this->query, $data )->row();

        return $result;

    }

    /**
     * Insercion por lotes de cargos o abonos a detalles de cuenta corriente 
     * calculando el monto de cargo o abono en cada insercion de acuerdo a la
     * tasa de cobro correspondiente para la fecha de ingreso
     * @param date      $inicio   Fecha inicio rango
     * @param date      $fin      Fecha fin rango
     * @param array     $detalles ID de servicio por cuenta corriente para hacer el cargo
     * @param boolean   $estado   true = pendiente, 0 = abonado
     */
    public function set_cargo_cuenta($inicio, $fin, $detalles, $estado)
    {
        
        //Encontrar diferencia de tiempo, para calcular el numero de iteraciones 
        //en que se hara cada cargo
        
        $numero_iteraciones = $this->get_diferencia_meses($inicio, $fin)
                                ->meses
                                +1; //Suma 1 para tomar en cuenta el ultimo mes

        //Contador de iteraciones
        $iteraciones = 1;

        //Fecha sepcionada
        $year = explode('-', $inicio)[0];
        $month = explode('-', $inicio)[1];
        $day = explode('-', $inicio)[2];

        //Queries
        //Detalles Servicio por Cuenta Corriente
        $query_servicio = "SELECT * FROM sxc_servicioxcuenta_corriente WHERE sxc_id = ?"; 

        //Taria vigente para insercion historico
        $query_tarifa = "SELECT * FROM trf_tarifa 
                            WHERE trf_desde <= ? 
                                AND trf_id_srv = ? 
                            ORDER BY ABS( DATEDIFF( trf_desde , ? ) ), trf_hasta DESC 
                            LIMIT 1";


        while( $iteraciones <= $numero_iteraciones ){

            //Contador de meses 
            if( $month == 13 ){
                $month = '01';
                $year++;
            }

            foreach ( $detalles as $fila => $servicio ) {
                
                $data_servicio = array( $servicio['id'] );
                
                $datos_basicos = $this->db->query($query_servicio, $data_servicio)->row();
                
                $base_imponible = $datos_basicos->sxc_base_imponible;

                $servicio_id = $datos_basicos->sxc_id_srv;

                //Fecha de cargo 
                $fecha_cargo = $year.'-'.$month.'-'.$day;
                
                //Obtener datos de tarifa activa, dependiendo de la fecha de cargo
                //EJ. 2008-01-01 - tarifa activa para servicio ID 1 = $0.15
                $data_tarifa = array( $fecha_cargo, $servicio_id, $fecha_cargo );
                
                $tasa_tarifa = $this->db->query($query_tarifa, $data_tarifa);
                
                //Si algun dato fue retornado
                $tasa_tarifa = ( $tasa_tarifa->num_rows() > 0 ) 
                                ? $tasa_tarifa->row()
                                : 0;

                //Calcular el monto de cargo
                $monto_servicio = ( is_object( $tasa_tarifa ) ) 
                                    ? $base_imponible * $tasa_tarifa->trf_precio
                                    : $base_imponible * $tasa_tarifa;

                //Obtener el ultimo dia del mes de la fecha de cargo
                //date( "Y-m-t" , strtotime( $fecha_cargo ) )
                
                $data_detalle = array(
                    'det_id_sxc'    => $servicio['id'],
                    'det_fecha'     => date( "Y-m-t" , strtotime( $fecha_cargo ) ),
                    'det_fecha_mod' => date('Y-m-d'),
                    'det_user_mod'  => 'SYSTEM', 
                    'det_estado'    => $estado,
                    'det_monto'     => $monto_servicio 
                     );

                //Comparar fechas - Fecha de cargo
                $fecha1 = new DateTime($data_detalle['det_fecha']);
                //Fecha actual con el ultimo dia del mes
                $fecha_actual_provicional = date('Y-m-t');
                //Fecha actual convertida a object
                $fecha2 = new DateTime( $fecha_actual_provicional );
                $cargo_valido = true;
                //verificar igualdad de fechas
                if ( $fecha1 >= $fecha2 ) {
                    //Consultar listado de cargos en det_detalle por id de servicio
                    $cargos_futuros = $this->db->get_where( 'det_detalle_cntc', 
                                                            array( 
                                                                'det_id_sxc' => $servicio['id'],
                                                                'det_estado' => 1
                                                                 )
                                                        );
                    //Verificar si es posible realizar cargos a futuro
                    if ( is_object( $cargos_futuros ) && $cargos_futuros->num_rows() > 0 ) {
                        $cargo_valido = false;
                    }
                } 
                //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER 
                $this->db->db_debug = FALSE;

                
                //Verificar si el monto es mayor a 0, implica que el servicio existe y posee tarifa 
                //en el momento especifico en la linea de tiempo en fue cargado o abonado a la cuenta
                if( $data_detalle['det_monto'] > 0 ){
                    //Verificar si el usuario esta al corriente con los pagos para ingresar pagos a futuro
                    if ( $cargo_valido != false ) {
                        $result = $this->db->insert( 'det_detalle_cntc', $data_detalle );
                    } else {
                        $result = true;
                    }
                    
                }

                //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER 
                $this->db->db_debug = TRUE;
                
            }

            $iteraciones++;
            $month++;

            if($month < 10){
                $month = '0'.$month;
            }
        }

        /*
            Verify results
        */
        unset($data);
        $data = array();
        if( $result ){
            
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = true;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
            $data['message'][] = array(
                'success'   => true
                );
            
            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = true;
            $data['message'] = array();

            $data['message'][] = array(
                    'success'   => false
                    );
            }
            
            return json_encode($data);

    }

    

    /**
     * Realizar cargos a cuenta corriente de inmuebles (corte mensual)
     * @return json cantidad de cuentas afectadas y cargos realizados
     */
    public function do_cargo_mensual_inmueble()
    {   

        //Proceso Almacenado que realiza los cortes mensuales para inmueble
        $this->query ="CALL cargo_mensual_inmueble()";
        
        //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER 
        $this->db->db_debug = FALSE;

        //Run query 
        $result = $this->db->query( $this->query );
        
        //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER
        $this->db->db_debug = TRUE;
        
        //Cantidad de cuentas corrientes afectadas  
        $query_cuenta = "SELECT COUNT(*) as cuentas 
                            FROM cnt_cuenta_corriente  
                            WHERE (cnt_estado = ? OR cnt_estado = ?)
                            AND cnt_tipo_cnt = ?";
        
        //Estado de cuenta = Activa o Cancelacion denegada, Tipo de Cuenta = Inmueble
        $cuentas = $this->db->query( $query_cuenta, array( 1, 4, 1 ) )
                    ->row()
                    ->cuentas;

        /*
            Tarifa estado = activa, Servicio por Cuenta Corriente = Activo,
            Estado de Cuenta = Activo o Cancelacion denegada,
            Tipo de cuenta = Inmueble
         */
        $query_cargos = "SELECT COUNT(*) as cargos
                            FROM sxc_servicioxcuenta_corriente 
                            INNER JOIN cnt_cuenta_corriente 
                                ON sxc_cnt_id = cnt_id
                            INNER JOIN cxi_cuenta_corrientexinmueble
                                ON cnt_id = cxi_id_cnt
                            INNER JOIN srv_servicio 
                                ON sxc_id_srv = srv_id
                            INNER JOIN trf_tarifa 
                                ON srv_id = trf_id_srv
                            WHERE trf_estado = ? 
                            AND sxc_estado = ? 
                            AND (cnt_estado = ? OR cnt_estado = ?)
                            AND cnt_tipo_cnt = ?";

        $cargos = $this->db->query($query_cargos, array( 1, 1, 1 , 4, 1 ))
                    ->row()
                    ->cargos;

        
        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $cuentas && $cargos ){
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = TRUE;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            
            $data['message'][] = array(
                'car_respuesta'     => $cuentas.":".$cargos,
                );
            

            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = TRUE;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'car_respuesta'     => FALSE,
                    );
            }
            
            return json_encode($data);
    }

    /**
     * Realiza cargos a cuenta corriente de empresas (corte mensual)
     * @return json cantidad de cuentas afectadas y cargos realizados
     */
    public function do_cargo_mensual_empresa()
    {   

        //Proceso Almacenado que realiza los cortes mensuales para inmueble
        $this->query ="CALL cargo_mensual_empresa()";
        
        //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER 
        $this->db->db_debug = FALSE;

        //Run query 
        $result = $this->db->query( $this->query );
        
        //Indica si deben mostrarse los errores de la base de datos - INSERT ERROR HANDLER
        $this->db->db_debug = TRUE;
        
        //Cantidad de cuentas corrientes afectadas  
        $query_cuenta = "SELECT COUNT(*) as cuentas 
                            FROM cnt_cuenta_corriente  
                            WHERE (cnt_estado = ? OR cnt_estado = ?)
                            AND cnt_tipo_cnt = ?";
        
        //Estado de cuenta = Activa o Cancelacion denegada, Tipo de Cuenta = Empresa
        $cuentas = $this->db->query( $query_cuenta, array( 1, 4, 2 ) )
                    ->row()
                    ->cuentas;

        /*
            Tarifa estado = activa, Servicio por Cuenta Corriente = Activo,
            Estado de Cuenta = Activo o Cancelacion denegada,
            Tipo de cuenta = Empresa
         */
        $query_cargos = "SELECT COUNT(*) as cargos
                            FROM sxc_servicioxcuenta_corriente 
                            INNER JOIN cnt_cuenta_corriente 
                                ON sxc_cnt_id = cnt_id
                            INNER JOIN exc_empresaxcuentac
                                ON cnt_id = exc_id_cnt
                            INNER JOIN srv_servicio 
                                ON sxc_id_srv = srv_id
                            INNER JOIN trf_tarifa 
                                ON srv_id = trf_id_srv
                            WHERE trf_estado = ? 
                            AND sxc_estado = ? 
                            AND (cnt_estado = ? OR cnt_estado = ?)
                            AND cnt_tipo_cnt = ?";

        $cargos = $this->db->query($query_cargos, array( 1, 1, 1 , 4, 2 ))
                    ->row()
                    ->cargos;

        //Set Ajax data response to false
        $data['response'] = FALSE;
        /*
            Verify results
        */
        if( $result && $cuentas && $cargos ){
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = TRUE;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            
            $data['message'][] = array(
                'car_respuesta'     => $cuentas.":".$cargos,
                );
            
            //Encode result array into JSON
            return json_encode($data);

        }else{

            //Si no se encontro ningun registro
            $data['response'] = TRUE;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
                $data['message'][] = array(
                    'car_respuesta'     => FALSE,
                    );
            }
            
            return json_encode($data);
    }

    /**
     * Obtener el consolidado de cuenta por ID de inmueble o ID de cuenta corriente
     * @param  integer $inm_id [description]
     * @param  integer $tiempo [description]
     * @return [type]          [description]
     */
    public function get_consolidado_cuenta( $inm_id = 0, $mode = 1 )
    {   
        //Obtener detalles de cuenta corriente por ID Inmueble
        $detalles_cuenta = $this->get_estado_cuenta_plan( $inm_id, $mode );

        //Sumatoria total de cargos por pagar (mora)
        $mora = 0;

        //Set Ajax data response to false
        $data['response'] = FALSE;

        //Verificar si la cuenta tiene cargos por pagar
        if( $detalles_cuenta != false ){

            /*
                Obtener diferencia de meses para calcular multa
             */ 
            $cantidad = $detalles_cuenta->result_array();

            reset($cantidad); //Poner el array en posicion 0

            //Obtener primera posicion del array
            $inicio = current($cantidad)['det_fecha']; 

            //Obtener ultima posicion del array
            $fin = end($cantidad)['det_fecha']; 
            
            //Obtener diferencia de meses;
            $diferencia_meses = $this->get_diferencia_meses( $inicio, $fin );

            //Pago mensual
            $pago_mensual = 0; 

            //Recorrer data
            foreach ( $detalles_cuenta->result() as $key ) {
                //Sumar todos los cargos para obtener mora actual
                $mora = $mora + $key->det_monto;
                
                //Sumar pagos de un mes para obtener 
                if( $key->det_fecha == $inicio ){
                    $pago_mensual = $key->det_monto;
                }
            }

            $multa = 0; //Inicializar multa

            //Obtener cantidad total de multa
            if ( $diferencia_meses->meses >= 1 && $diferencia_meses->meses <= 3  ){

                //De 1 - 3 Meses factor 5%
                $multa = $mora * 0.05;

                //No multa menor a 2.86
                if ( $multa < 2.86 )  $multa = 2.86;

            } elseif ( $diferencia_meses->meses > 3 ) {

                //+3 Meses factor 10%
                $multa = $mora * 0.1;

            }
            
            $intereses = $mora * 0.12;
            
            //---------------------- JSON ------------------------//
            /*
                Si el resultado de la consulta es mayor a 0 establecer la respuesta de Ajax = true
                y crear un nuevo index para guardar los resultados obtenidos
             */
            $data['response'] = TRUE;
            $data['message'] = array();
            
            /*
                Obtener informacion y almacenarla en la respuesta de Ajax message
             */
            
            $data['message'][] = array(
                'mor_meses'     => $diferencia_meses->meses+1,
                'mor_mora'      => $mora,
                'mor_multa'     => $multa,
                'mor_interes'   => $intereses
                );

        } else {

            //Si no se encontro ningun registro
            $data['response'] = TRUE;
            $data['message'] = array();

            //Contruir message con los datos de numero de cuenta por inmueble
            $data['message'][] = array(
                    'mor_meses'     => false,
                    'mor_mora'      => 0,
                    'mor_multa'     => 0    ,
                    'mor_interes'   => 0
                    );
            
        }
        
        //Encode result array into JSON
        return json_encode($data);
        
    }

    /**
     * Calcular # de cuotas pendiente por mensualidad a cancelar en plan de pago
     * @param  integer $meses   Numero de meses en los que se va a cancelar la deuda
     * @param  integer $inm_id  ID del inmueble al que se le generara el plan de pagos
     * @return json             Numero de cuotas con cantidad de mensualidad a pagar por cada cuota
     */
    public function do_plan_pago_previa( $meses, $inm_id, $mode = 1 )
    {   
        
        //Obtener detalles de cuenta corriente por ID Inmueble
        $detalles_cuenta = $this->get_estado_cuenta_plan( $inm_id, $mode );

        //Numero de coutas a pagar = cantidad de cargos pendientes entre tiempo a pagar
        $numero_cuotas = round( $detalles_cuenta->num_rows() / $meses );
        
        //Inicializacion de datos
        $cuota = 1;
        $detalles = array();
        $cuenta_id = 0;
        $cuotas_pendientes = $detalles_cuenta->num_rows();
        $mes=1;
        $numero_row=0;
        //Recorer todos los meses
        while ( $meses > 0 ){

            /*
                agregar todos los meses que se van a agrupar en una sola cuota, dependiendo
                del numero de cuotas calculadas
             */
            $cuota = 1;
            $numero_cuotas = round($cuotas_pendientes / $meses);
            
            while( $cuota <= $numero_cuotas )
            {
                //agregar en el mes y cuota correspondiente la fecha de la cuota que se va a pagar 
                //durante este mes

                $detalles[$mes][$cuota]['fecha'] = $detalles_cuenta->row($numero_row)->det_fecha;
                $detalles[$mes][$cuota]['monto'] = floatval($detalles_cuenta->row($numero_row)->det_monto);
                $cuenta_id =    $detalles_cuenta->row($numero_row)->cnt_id;
                $numero_row++;

                $cuota++;
            }

                //Incrementos y Declaraciones
                
                $meses--;
                $cuotas_pendientes-=$numero_cuotas;
                $mes++;
            
            
        }

        $data['response'] = TRUE;
        $data['message'] = array();
        $data['message']= $detalles;
        $data['cuenta'] = $cuenta_id;

        return json_encode($data);
        
    }

    /**
     * Genera plan de pago por ID de Cuenta Corriente 
     * @param  array   $consolidado [description]
     * @param  integer $cuenta_id   [description]
     * @param  integer $total       [description]
     * @return [type]               [description]
     */
    public function do_plan_pago( $consolidado = array(), $cuenta_id = 0, $total =0 )
    {   
        $numero_plan = $this->db->get('pla_plan')->num_rows()+1;
        $data = array(
        'pla_id_cnt' => $cuenta_id,
        'pla_numero' => $numero_plan,
        'pla_monto_deuda' => $total,
        'pla_fecha_convenio' => date('Y-m-d'),
        'pla_user_mod' => 'SYSTEM'
         );
        

        $result = $this->db->insert('pla_plan', $data);

        if($result){
            $plan_id = $this->db->insert_id();
        }

        $numero = 1;
    
        foreach ($consolidado as $key ) {
            
            reset($key);

            $data = array(
                'cxp_id_pla'        => $plan_id,
                'cxp_id_cnt'        => $cuenta_id,
                'cxp_numero'        => $numero,
                'cxp_estado'        => 1,
                'cxp_user_mod'      => 'SYSTEM',
                'cxp_fecha_desde'   => date('Y-m-d', strtotime( current($key)['fecha'] ) ),
                'cxp_fecha_hasta'   => date('Y-m-d', strtotime( end($key)['fecha'] ) )
                 );
            
            $result = $this->db->insert( 'cxp_cuotaxplan', $data );

            $numero++;
        }

        $data = array();
        $data['response'] = TRUE;
        $data['message'] = array();

        if($result){

        $data['message'][] = array(
                'result'    => TRUE,
                'plan_id'   => $plan_id
            );
        

        
        }
        return json_encode($data);
    }

    /**
     * Retorna el monto de la sumatoria de los cargos realizados a una cuenta
     * corriente por ID de inmueble
     * @param  interger $inm_id     ID de inmueble
     * @return CI Object            CI Object result
     */
    public function get_estado_cuenta_plan( $inm_id, $mode = 1 )
    {

        if($mode == 1){
            $this->query = "SELECT SUM(det_monto) AS det_monto, det_fecha, cxi_id_cnt AS cnt_id, det_estado
                        FROM inm_inmueble
                        INNER JOIN cxi_cuenta_corrientexinmueble
                            ON inm_id = cxi_id_inm
                        INNER JOIN cnt_cuenta_corriente 
                            ON cnt_id = cxi_id_cnt
                        INNER JOIN sxc_servicioxcuenta_corriente
                            ON cnt_id = sxc_cnt_id
                        INNER JOIN det_detalle_cntc
                            ON sxc_id = det_id_sxc
                        INNER JOIN srv_servicio
                            ON srv_id = sxc_id_srv
                        WHERE det_estado = ? AND inm_id = ? GROUP BY det_fecha ASC";
        
            $data = array(
                'det_estado' => 1,
                'inm_id'     => $inm_id,
                );    
        }else{

            $this->query = "SELECT sum(det_monto) AS det_monto,
                                   det_fecha,
                                   cnt_id,
                                   det_estado
                            FROM det_detalle_cntc
                            INNER JOIN sxc_servicioxcuenta_corriente ON sxc_id = det_id_sxc
                            INNER JOIN cnt_cuenta_corriente ON sxc_cnt_id = cnt_id
                            INNER JOIN exc_empresaxcuentac ON exc_id_cnt = cnt_id
                            INNER JOIN srv_servicio ON srv_id = sxc_id_srv
                            WHERE det_estado = ?
                                AND cnt_id = ?
                            GROUP BY det_fecha ASC";
            $data = array(
                'det_estado' => 1,
                'cnt_id'     => $inm_id,
                );
        }
        
        $result = $this->db->query( $this->query, $data );

        return ( is_object($result) && $result->num_rows() > 0 )
                ? $result 
                : false;
    }

    /**
     * Obtiene Informacion basica del plan de pago por ID de inmueble o ID de cuenta corriente
     * @param  integer $inm_id [description]
     * @return json          Datos basicos del Plan
     */
    public function get_plan_pago( $inm_id = 0 , $mode )
    {   
        
        if($mode == 1){
            $numero_cuenta = $this->get_numero_cuenta_inm( $inm_id );  
            $numero_cuenta = json_decode($numero_cuenta)
                            ->message[0]
                            ->cnt_numero;  
        }else{
            $numero_cuenta = $inm_id;
        }
        
        $data = array( $numero_cuenta );

        $this->query = "SELECT * FROM pla_plan WHERE pla_id_cnt = ? ORDER BY pla_id ASC";

        $query_result = $this->db->query($this->query, $data);

        $data = array();
        if($query_result && $query_result->num_rows() > 0){
            
            $data['response'] = TRUE;
        
            foreach ( $query_result->result() as $row ) {
                $data['message'][] = array(
                    'pla_id' => $row->pla_id,
                    'pla_numero' => $row->pla_numero,
                    'pla_monto' =>  $row->pla_monto_deuda,
                    'pla_fecha' => $row->pla_fecha_convenio
                     );
             } 
        }else{

            $data['response'] = TRUE;
                    
                $data['message'][] = array(
                    'pla_id' => false,
                    'pla_numero' => '',
                    'pla_monto' =>  '',
                    'pla_fecha' => ''
                     );
        }

        return json_encode($data);
    }

    /**
     * Obtiene detalles de cuotas por ID de plan de pago
     * @param  integer $plan_id ID plan de pago
     * @return json          Listado de cuotas y montos a cancelar
     */
    public function get_detalle_plan( $plan_id )
    {
        $this->query = "SELECT * FROM cxp_cuotaxplan WHERE cxp_id_pla = ?";
        
        $data = array( $plan_id );

        $query_result = $this->db->query($this->query, $data);


        $data = array();
        $monto_query = "SELECT SUM(det_monto) as cxp_monto FROM det_detalle_cntc
                        INNER JOIN sxc_servicioxcuenta_corriente
                            ON sxc_id = det_id_sxc
                        WHERE det_fecha BETWEEN ? AND ?
                            AND sxc_cnt_id = ?";

        if($query_result && $query_result->num_rows() > 0){
            
            $data['response'] = TRUE;
        
            foreach ( $query_result->result() as $row ) {
                
                
                $desde = $row->cxp_fecha_desde;
                $hasta = $row->cxp_fecha_hasta;
                $cuenta = $row->cxp_id_cnt;
                $datos = array( $desde, $hasta, $cuenta );

                $monto = $this->db->query($monto_query, $datos)->row();

                $data['message'][] = array(
                    'cxp_id'        => $row->cxp_id,
                    'cxp_numero'    => $row->cxp_numero,
                    'cxp_estado'    => ($row->cxp_estado == 1) ? 'Pendiente' : 'Pagado',
                    'cxp_monto'     => round( ( $monto->cxp_monto + ( $monto->cxp_monto * 0.05 ) ), 2 ) ,
                    'desde' => $desde,
                    'hasta' => $hasta
                     );
             } 
        }else{

            $data['response'] = TRUE;
                    
                $data['message'][] = array(
                    'pla_id' => false,
                    'pla_numero' => '',
                    'pla_monto' =>  '',
                    'pla_fecha' => ''
                     );
        }

        return json_encode($data);
    }

    /**
     * Obtiene detalles de cuota y datos necesarios para la generacion de F1-ISAM
     * @param  integer $cuota_id    ID de cuota
     * @param  integer $inm_id      ID de inmueble
     * @return json                 Resultado de generacion de F1-ISAM
     */
    public function get_detalle_cuota($cuota_id = 0, $inm_id = 0, $mode )
    {

        
        $query_cuota = "SELECT * FROM cxp_cuotaxplan WHERE cxp_id = ?";

        $query_detalles = "SELECT det_id FROM det_detalle_cntc
                            INNER JOIN sxc_servicioxcuenta_corriente
                                ON sxc_id = det_id_sxc
                            WHERE det_fecha BETWEEN ? AND ?
                                AND sxc_cnt_id = ?
                            ORDER BY det_id";

        $query_monto = "SELECT SUM(det_monto) AS subtotal FROM det_detalle_cntc
                            INNER JOIN sxc_servicioxcuenta_corriente
                                ON sxc_id = det_id_sxc
                            WHERE det_fecha BETWEEN ? AND ?
                                AND sxc_cnt_id = ?
                            ORDER BY det_id";
        if( $mode == 1 ){
            $query_contribuyente = "SELECT con_id, con_direccion AS direccion, 
                                    CONCAT_WS(' ', con_apellido1, con_apellido2, '-', con_nombre1, con_nombre2) as per_full_name 
                                    FROM inm_inmueble
                                    INNER JOIN ixc_inmueblexcontribuyente
                                        ON inm_id = ixc_id
                                    INNER JOIN con_contribuyente
                                        ON con_id = ixc_id_con
                                    WHERE inm_id = ?";    
        } else {
            
            $query_contribuyente = "SELECT con_id,
                                           con_direccion AS direccion,
                                           concat_ws(' ', con_apellido1, con_apellido2, '-', con_nombre1, con_nombre2) AS per_full_name
                                    FROM con_contribuyente
                                    INNER JOIN exc_empresaxcontribuyente ON exc_id_con = con_id
                                    INNER JOIN emp_empresa ON emp_id = exc_id_emp
                                    INNER JOIN exc_empresaxcuentac AS excc ON excc.exc_id_emp = emp_id
                                    INNER JOIN cnt_cuenta_corriente ON cnt_id = excc.exc_id_cnt
                                    WHERE cnt_id = ?
                                    ORDER BY exc_id_pet LIMIT 1";
        }
        

        $query_plan = "SELECT * FROM pla_plan WHERE pla_id = ? ";

        /*
            Obtener Numero de Cuenta Corriente por ID de Inmueble
         */
        
        if($mode == 1){
            $numero_cuenta = $this->get_numero_cuenta_inm( $inm_id );
        
            $numero_cuenta = json_decode($numero_cuenta)
                                ->message[0]
                                ->cnt_numero;    
        } else {
            $numero_cuenta = $inm_id;
        }
        

        //Obtener datos de la cuota a cancelar 
        $data_cuota = array($cuota_id);

        $cuota = $this->db->query($query_cuota, $data_cuota)->row();

        //Obtener ID's de destalle de cuenta corriente correspondientes a la cuota a cancelar
        $data_detalles = array($cuota->cxp_fecha_desde, $cuota->cxp_fecha_hasta, $numero_cuenta );

        $detalles = $this->db->query($query_detalles, $data_detalles)->result();

        //Obtener monto a cancelar por cuota
        $monto = $this->db->query($query_monto, $data_detalles)->row();

        //Obtener nombre del contribuyente 
        $data_contribuyente = array( $inm_id );

        $contribuyente = $this->db->query($query_contribuyente, $data_contribuyente )->row();

        //Obtener datos basicos del plan
        $data_plan = array($cuota->cxp_id_pla);

        $plan = $this->db->query( $query_plan, $data_plan )->row();

        //Datos a insertar en F1-ISAM
        $data_funo = array(
            'fun_concepto'      => 'Pago de Cuota # '.$cuota->cxp_numero.' del Plan de Pago a Plazos # '. $plan->pla_numero,
            'fun_fecha'         => date('Y-m-d'),
            'fun_subtotal'      => $monto->subtotal,
            'fun_impuesto'      => ($monto->subtotal * 0.05),
            'fun_total'         => round( ( $total = ( $monto->subtotal * 0.05 ) + $monto->subtotal ), 2),
            'fun_contribuyente' => $contribuyente->per_full_name.', '.$contribuyente->direccion
              );

        //Generar F1-ISAM 
        $result = $this->_do_funo( $data_funo, $detalles );

        return ( $result )
                ? $result
                : $result;
        
    }

    /**
     * Construye F1-ISAM para las cuotas de plan de pago
     * @param  array  $data_funo    Informacion basica a insertar en F1-ISAM
     * @param  array  $detalles     Listado de ID de detalle a insertar en FXD
     * @return json                 Resultado de la insercion de F1-ISAM
     */
    protected function _do_funo( $data_funo = array(), $detalles = array() )
    {
        //Verificar si la lista de ID de detalle esta llena
        if( count( $detalles ) > 0 ){

            //Crear nuevo F1-ISAM
            $this->db->insert( 'fun_funo', $data_funo );

            //Obtener id del F1-ISAM recien creado
            $funo_id = $this->db->insert_id();    
            
        }
        
        /*
            INICIO TEST APP
         */
            //$funo_id = 1;
        /*
            FIN TEST APP
         */
    
        foreach ( $detalles as $row ) {
            
            $data_fxd = array(
                'fxd_id_det' => $row->det_id,
                'fxd_id_fun' => $funo_id
                 );

            $this->db->insert('fxd_funoxdetalle_cntc', $data_fxd);
            
        }

        if( is_integer( $funo_id ) && count( $detalles ) > 0 ){
            
            $data['response'] = true;

            $data['message'] = array( 'funo_id' => $funo_id );
        }else{
            $data = array();
            $data['response'] = true;

            $data['message'] = array( 'funo_id' => false );
        }
        
        return json_encode($data);
    }
    
    /**
     * Obtener porcentaje de interes vigente que aplica para inmueble y empresas
     * @param  integer $tipo 1 = inmueble, 2 = empresa
     * @return json porcentaje de interes a aplicar
     */
    public function get_porcentaje_interes_vigente( $tipo = 0 )
    {
 
  $periodos_dispensa = $this->db->query('SELECT * FROM pdi_periodo_dispensa WHERE pdi_estado = 1 AND CURDATE() BETWEEN pdi_inicio AND pdi_final')->row_array();
 // print_r($periodos_dispensa);
  if(count($periodos_dispensa)>0){
    $start_date = $periodos_dispensa['pdi_inicio'];
   $end_date  =   $periodos_dispensa['pdi_final'];
    $start_ts = strtotime($start_date);
    $end_ts = strtotime($end_date);
    $user_ts = strtotime($this->input->post('output'));

    $valor = (($user_ts >= $start_ts) && ($user_ts <= $end_ts));
     if($valor == true){
        $data =array(
         'interes' => 0.00
            );

        return json_encode( $data ); 
     }else{
      //significa quela dispoensa esta inactiva
       $multa = 0;
      echo $multa;
     }
  }else{
    $query = $this->db->get_where( 'inm_interes_moratorio', array( 
                                                                'inm_tipo' => $tipo,
                                                                'inm_estado' => 1 
                                                                )
                                    );
        $data = array();
        $data['response'] = false;

        if ( is_object( $query ) && $query->num_rows() > 0 ){
            $data['response'] = true;
            $data['interes'] = $query->row()->inm_porcentaje;
        } else {
            $data['response'] = true;
            $data['interes'] = false;
        }

        return json_encode( $data ); 
     }
    }

    public function check_plan_pago_activo( $cnt_id = 0 )
    {
        $query_check = "SELECT * FROM pla_plan 
                        WHERE pla_id_cnt = ? AND pla_estado = 1";

        $query_result = $this->db->query( $query_check, array( 'pla_id_cnt' => $cnt_id ) );

        $data = array();
        $data['response'] = false;

        if ( is_object( $query_result ) && $query_result->num_rows() > 0 ){
            $data['response'] = true;
            $data['activo'] = 1;
        } else {
            $data['response'] = true;
            $data['activo'] = 0;
        }

        return json_encode( $data );
    }

    public function get_contribuyente($cnt_id = 0)
    {
        $this->db->select();
        $this->db->from('cnt_cuenta_corriente');
        $this->db->join('cxi_cuenta_corrientexinmueble','cxi_cuenta_corrientexinmueble.cxi_id_cnt = cnt_cuenta_corriente.cnt_id');
        $this->db->join('inm_inmueble','cxi_cuenta_corrientexinmueble.cxi_id_inm = inm_inmueble.inm_id');
        $this->db->join('ixc_inmueblexcontribuyente','ixc_inmueblexcontribuyente.ixc_id_inm = inm_inmueble.inm_id');
        $this->db->join('con_contribuyente','ixc_inmueblexcontribuyente.ixc_id_con = con_contribuyente.con_id');

        $this->db->join('dxc_documentoxcontribuyente','dxc_documentoxcontribuyente.dxc_id_con = con_contribuyente.con_id');
        $this->db->join('doc_documento','dxc_documentoxcontribuyente.dxc_id_doc = doc_documento.doc_id');
        $this->db->join('tid_tipo_documento','doc_documento.doc_tid_id = tid_tipo_documento.tid_id');

        $this->db->where('cnt_cuenta_corriente.cnt_id =',$cnt_id);
        $this->db->where('tid_tipo_documento.tid_id =',1);
       
        $query=$this->db->get();
        
        if(count($query->result_array())==0){
            $this->db->select();
            $this->db->from('cnt_cuenta_corriente');
            $this->db->join('exc_empresaxcuentac','exc_empresaxcuentac.exc_id_cnt = cnt_cuenta_corriente.cnt_id');
            $this->db->join('emp_empresa','exc_empresaxcuentac.exc_id_emp = emp_empresa.emp_id');
            $this->db->join('exc_empresaxcontribuyente','exc_empresaxcontribuyente.exc_id_emp = emp_empresa.emp_id');
            $this->db->join('con_contribuyente','ixc_inmueblexcontribuyente.ixc_id_con = con_contribuyente.con_id');

            $this->db->join('dxc_documentoxcontribuyente','dxc_documentoxcontribuyente.dxc_id_con = con_contribuyente.con_id');
            $this->db->join('doc_documento','dxc_documentoxcontribuyente.dxc_id_doc = doc_documento.doc_id');
            $this->db->join('tid_tipo_documento','doc_documento.doc_tid_id = tid_tipo_documento.tid_id');

            $this->db->where('cnt_cuenta_corriente.cnt_id =',$cnt_id);
            $this->db->where('tid_tipo_documento.tid_id =',1);
           
            $query=$this->db->get();
        }

        return $query->result_array();
    }

    /**
     * Listado de planes de pago por id de contribuyente 
     * @param integer $id_contribuyente ID del contribuyente
     * @return json listado de planes
     */
    public function get_planes_pago($id_contribuyente = 0)
    {
        //Obtener ID de cuenta corriente de todos los inmuebles que este usuario posee
        $query_inmuebles = "SELECT con_id, GROUP_CONCAT(cxi_id_cnt SEPARATOR ', ') AS id_cuenta 
                            FROM con_contribuyente
                            INNER JOIN ixc_inmueblexcontribuyente ON ixc_id_con = con_id
                            INNER JOIN cxi_cuenta_corrientexinmueble ON cxi_id_inm = ixc_id_inm
                            WHERE con_id = ?";
        //Obtener ID de cuenta corriente de todas las empresas que este usuario posee 
        $query_empresas = "SELECT con_id, 
                            GROUP_CONCAT(exc_id_cnt SEPARATOR ', ') AS id_cuenta 
                            FROM con_contribuyente
                            INNER JOIN exc_empresaxcontribuyente ON exc_id_con = con_id
                            INNER JOIN exc_empresaxcuentac ON exc_empresaxcuentac.exc_id_emp = exc_empresaxcontribuyente.exc_id_emp
                            WHERE con_id = ?";
        //Obtener data
        $resultado_inmuebles = $this->db->query($query_inmuebles, array('con_id' => $id_contribuyente));
        $resultado_empresas = $this->db->query($query_empresas, array('con_id' => $id_contribuyente));

        //Obtener planes de pago con su respectivo origen de generacion
        $query_planes = "SELECT pla_id, 
                        pla_numero, 
                        pla_monto_deuda, 
                        pla_fecha_convenio, 
                        pla_estado,
                        CASE 
                            WHEN inm_id IS NOT NULL THEN CONCAT(inm_direccion, ' - c&oacute;digo: ', inm_cod_catastral)
                            ELSE NULL
                        END AS nombre_inmueble,
                        CASE 
                            WHEN emp_id IS NOT NULL THEN CONCAT(nem_nombre, 'NIT: ', emp_nit)
                            ELSE NULL
                        END AS nombre_empresa
                        FROM pla_plan 
                        LEFT JOIN cxi_cuenta_corrientexinmueble ON cxi_id_cnt = pla_id_cnt
                        LEFT JOIN inm_inmueble ON inm_id = cxi_id_inm
                        LEFT JOIN exc_empresaxcuentac ON exc_id_cnt = pla_id_cnt
                        LEFT JOIN emp_empresa ON emp_id = exc_id_emp
                        LEFT JOIN nem_nombre_empresa ON nem_id_emp = emp_id
                        WHERE pla_id_cnt IN ( ";

        if (is_object($resultado_inmuebles) && $resultado_inmuebles->num_rows() > 0 ) {
            $query_planes .= $resultado_inmuebles->row()->id_cuenta;
        }

        if (is_object($resultado_empresas) && $resultado_empresas->num_rows() > 0) {
            $query_planes .= ", ".$resultado_empresas->row()->id_cuenta;
        }

        $query_planes .= " )";
        //run query
        $query_result = $this->db->query($query_planes);
        //Preparar respuesta en JSON
        $data = array();
        $data['response'] = false;
        if (is_object($query_result) && $query_result->num_rows() > 0) {
            //Estableciendo respuesta
            $data['response'] = true;
            $data['message']  = array();
            //Almacenando resultados
            foreach ($query_result->result() as $fila) {
                $data['message'][] = array(
                    'id'             => $fila->pla_id,
                    'numero'         => $fila->pla_numero,
                    'monto'          => $fila->pla_monto_deuda,
                    'fecha_convenio' => $fila->pla_fecha_convenio,
                    'estado'         => $fila->pla_estado,
                    'dependiente'    => ($fila->nombre_inmueble != "") ? $fila->nombre_inmueble : $fila->nombre_empresa
                    );
            }
        }

        return json_encode($data); 
    }

    public function get_informacion_plan($id_plan = 0)
    {   
        $resultado = $this->db->get_where('pla_plan', array('pla_id' => $id_plan));
        $data = array();
        $data['response'] = false;

        if (is_object($resultado) && $resultado->num_rows() > 0) {
            $data['response'] = true;
            foreach ($resultado->result() as $fila) {
                $data['estado'] = $fila->pla_estado;
            }
        }
        
        return json_encode($data);
    }

    public function set_estado_plan($id_plan = 0, $estado = 0)
    {
        $this->db->where('pla_id', $id_plan);
        $this->db->update('pla_plan', array('pla_estado' => $estado));
        return json_encode(array('response' => true));
    }
}

/* End of file uatm_payment.php */
/* Location: ./application/models/uatm_payment.php */