<?php

/**
 * Planilla_Model
 * 
 * 2013-07-11
 * 
 * @package erpConamype
 * @author Alexis Beltran
 * @copyright 2013
 * @version RC1
 * @access public
 */
/**
 * Planilla_Model
 * 
 * @package municipalidad
 * @author satelite
 * @copyright 2013
 * @version $Id$
 * @access public
 */
class Planilla_Model extends CI_Model
{
    protected $debug = 0;
    
    //listado de tablas
    protected $empleados    = 'emp_empleado';
    protected $descuentos   = 'dss_descuento';
    protected $ingresos     = 'inn_ingreso';
    protected $tipo_descuentos = 'tdc_tipo_descuento';
    protected $tipo_ingresos   = 'tig_tipo_ingreso';
    protected $tipo_planillas   = 'tpl_tipo_planilla';
    protected $planillas = 'pla_planilla';
    protected $detalle_descuentos = 'ddd_detalle_descuento';
    protected $detalle_ingresos = 'ddi_detalle_ingreso';
    protected $desxplanilla = 'dtp_descuentoxtipo_planilla';
    protected $ingxplanilla = 'itp_ingresoxtipo_planilla';
    protected $afps = 'afp_afp';
    
    protected $tipos_descuento = null;
    protected $tipos_ingreso = null;
    protected $afp = null;
    
    protected $agrupador_isr = 3;
    protected $agrupador_isss = 1;
    protected $agrupador_salario = 6;
    
    protected $tdc_isr = 3;
    protected $tdc_isss = 1;
    protected $tdc_afp = 6;
    
    protected $tig_salario = 13;
    
    function __construct()
    {
        parent::__construct();
    }
    
    //--------- Planilla
    protected function calcular_ingresos($planilla, $empleado)
    {
        //los ingresos del periodo actual
        $this->db->select('
  inn_ingreso.inn_id,
  tig_tipo_ingreso.tig_id,
  tig_tipo_ingreso.tig_nombre,
  inn_ingreso.inn_valor,
  inn_ingreso.inn_cuotas_pagadas,
  inn_ingreso.inn_cuotas
        ');
        $this->db->join($this->tipo_ingresos, 'inn_ingreso.inn_id_tig = tig_tipo_ingreso.tig_id');
        $this->db->where('inn_cuotas_pagadas < inn_cuotas');
        $this->db->where(array(
            'inn_fecha_hasta <=' => "$planilla->pla_anyo-$planilla->pla_mes-$planilla->pla_dia",
            'inn_id_emp' => $empleado->emp_id
        ));
        $ingresos = $this->db->get($this->ingresos)->result();
        //los ingresamos en detalle
        foreach($ingresos as $ing){
            $this->add_ingreso_detalle($planilla->pla_id, $empleado->emp_id, $ing->tig_id, $ing->inn_valor, $ing->inn_id, $planilla->tpl_frecuencia);
            $this->_debug("emp: $empleado->emp_id, tig: $ing->tig_id, valor: $ing->inn_valor");
        }
    }
    
    protected function calcular_descuentos($planilla, $empleado)
    {
        //descuentos del periodo actual
        $this->db->select('
  dss_descuento.dss_id,
  tdc_tipo_descuento.tdc_id,
  tdc_tipo_descuento.tdc_nombre,
  dss_descuento.dss_valor,
  dss_descuento.dss_cuotas_pagadas,
  dss_descuento.dss_cuotas
        ');
        $this->db->join($this->tipo_descuentos, 'dss_descuento.dss_id_tdc = tdc_tipo_descuento.tdc_id');
        $this->db->where('dss_cuotas_pagadas < dss_cuotas');
        $this->db->where(array(
            'dss_fecha_hasta <=' => "$planilla->pla_anyo-$planilla->pla_mes-$planilla->pla_dia",
            'dss_id_emp' => $empleado->emp_id
        ));
        $descuentos = $this->db->get($this->descuentos)->result();
        //lon ingresamos al detalle
        foreach($descuentos as $des){
            $this->add_descuento_detalle($planilla->pla_id, $empleado->emp_id, $des->tdc_id, $des->dss_valor, 0, $des->dss_id, 0,$planilla->tpl_frecuencia);
            $this->_debug("emp: $empleado->emp_id, tig: $des->tdc_id, valor: $des->dss_valor");
        }
    }
    
    protected function calcular_isr($planilla, $empleados)
    {
        //obtener tablas de isr para la planilla
        $isr = $this->get_tabla_isr($planilla->pla_id);
        //print_r($isr);
        
        foreach($empleados as $emp){
            $salario = $this->calcular_agrupado($planilla, $emp->emp_id, $this->agrupador_isr);
            
            foreach($isr as $tramo){
                if($salario >= $tramo->isr_desde && $salario <= $tramo->isr_hasta){
                    $renta = $tramo->isr_cuota + ($salario - $tramo->isr_exceso) * $tramo->isr_porcentaje / 100;
                    $this->_debug("pla: $planilla->pla_id, emp: $emp->emp_id, base: $salario, renta: $renta");
                    $this->add_descuento_detalle($planilla->pla_id, $emp->emp_id, $this->tdc_isr, $renta, $salario, 0, 0, $planilla->tpl_frecuencia);
                }
            }
        }
    }
    
    protected function get_tabla_isr($planilla_id)
    {
        $this->db->select('isr_isr.*');
        $this->db->join('tpl_tipo_planilla', 'pla_planilla.pla_id_tpl = tpl_tipo_planilla.tpl_id');
        $this->db->join('txt_tipo_planillaxtipo_isr', 'txt_tipo_planillaxtipo_isr.txt_id_tpl = tpl_tipo_planilla.tpl_id');
        $this->db->join('tis_tipo_isr', 'txt_tipo_planillaxtipo_isr.txt_id_tis = tis_tipo_isr.tis_id');
        $this->db->join('isr_isr', 'isr_isr.isr_id_tis = tis_tipo_isr.tis_id');
        return $this->db->get_where($this->planillas, array('pla_id' => $planilla_id))->result();
    }
    
    protected function calcular_isss($planilla, $empleado)
    {
        $salario = $this->calcular_agrupado($planilla,$empleado->emp_id, $this->agrupador_isss);
        $isss = $this->db->get_where($this->tipo_descuentos,array('tdc_id' => $this->tdc_isss),1)->row();
        $asegurado = $salario * $isss->tdc_porcentaje / 100;
        $patrono = $salario * $isss->tdc_porcentaje_patronal /100;
        $this->_debug("emp: $empleado->emp_id, base: $salario, asegurado: $asegurado, patrono: $patrono");
        $this->add_descuento_detalle($planilla->pla_id, $empleado->emp_id, $this->tdc_isss, $asegurado, $salario, 0, $patrono,$planilla->tpl_frecuencia);
    }
    
    
    protected function calcular_afp($emp_id, $periodo_ini, $periodo_fin)
    {
        
    }
    
    //TODO: pasaar a protected
    function calcular_agrupado($planilla, $emp_id, $agrupador_id)
    {
        //DELETE:
        $this->get_tipos();
        
        $query = $this->db->query('
Select
  deg_detalle_agrupador.deg_id_tig as tig,
  deg_detalle_agrupador.deg_id_tdc as tdc,
  deg_detalle_agrupador.deg_signo,
  deg_detalle_agrupador.deg_aplicacion,
  deg_detalle_agrupador.deg_valor,
  (Select
    Sum(inn_ingreso.inn_valor)
  From
    inn_ingreso
  Where
    inn_ingreso.inn_id_tig = deg_detalle_agrupador.deg_id_tig And
    inn_ingreso.inn_id_emp = ' . $emp_id . ' And
    inn_ingreso.inn_fecha_hasta <= ' . "$planilla->pla_anyo-$planilla->pla_mes-$planilla->pla_dia" . ' And
    inn_ingreso.inn_cuotas_pagadas < inn_ingreso.inn_cuotas
  Group By
    inn_ingreso.inn_id_emp) As ingresos,
  (Select
    Sum(dss_descuento.dss_valor)
  From
    dss_descuento
  Where
    dss_descuento.dss_id_tdc = deg_detalle_agrupador.deg_id_tdc And
    dss_descuento.dss_id_emp = ' . $emp_id . ' And
    dss_descuento.dss_fecha_hasta <= ' . "$planilla->pla_anyo-$planilla->pla_mes-$planilla->pla_dia" . ' And
    dss_descuento.dss_cuotas_pagadas < dss_descuento.dss_cuotas
  Group By
    dss_descuento.dss_id_emp) As descuentos
From
  deg_detalle_agrupador
Where
  deg_detalle_agrupador.deg_id_tag = ' . $agrupador_id . '
Group By
  deg_detalle_agrupador.deg_id_tig, deg_detalle_agrupador.deg_id_tdc
        ');
        
        $resultado = 0.0;
        $salario = $this->db->get_where($this->empleados, array('emp_id' => $emp_id), 1)->row()->emp_salario / $planilla->tpl_cantidad;
        $this->_debug("Salario: $salario");
        foreach($query->result() as $row){

            $pre_valor = 0;
            
            $this->_debug("emp: $emp_id, tig: $row->tig, tdc: $row->tdc, ing: $row->ingresos, des: $row->descuentos");
            
            //obtenemos valor
            if($row->tig > 0){
                //ingresos
                switch ($row->tig){
                	//Salario
                    case $this->tig_salario:  
                        $pre_valor = $salario;
                    break;
                    
                    default:
                        $pre_valor = $row->ingresos;
                }
                //$this->_debug("tig: $row->tig, pre_valor: $pre_valor");
            }else if ($row->tdc > 0){
                //decuentos
                switch ($row->tdc){
                    //fondo de penciones AFP (debe de haberse calculado)
                    case $this->tdc_afp:
                        
                        $this->db->where(array(
                            'ddd_id_emp' => $emp_id,
                            'ddd_id_tdc' => $this->tdc_afp,
                            'ddd_id_pla' => $planilla->pla_id 
                        ));
                        $pre_valor = $this->db->get($this->detalle_descuentos, 1)->row()->ddd_valor;
                        $this->_debug("Calcular AFP: $pre_valor");
                    break;
                    
                    //isss
                    case $this->tdc_isss:
                        
                        $this->db->where(array(
                            'ddd_id_emp' => $emp_id,
                            'ddd_id_tdc' => $this->tdc_isss,
                            'ddd_id_pla' => $planilla->pla_id 
                        ));
                        $pre_valor = $this->db->get($this->detalle_descuentos, 1)->row()->ddd_valor;
                        $this->_debug("Calcular ISSS: $pre_valor");
                    break;
                    
                    default :
                    $tipo = $this->tipos_descuento[$row->tdc];
                    switch ($tipo->tdc_tipo){ 
                    	//Porcentaje
                        case 2:  
                            $pre_valor = $pre_valor;
                        break;
                        
                        //Min_MAx
                    	case 3:  
                            $pre_valor = $pre_valor;    
                        break;
                        
                        //Min_MAx + procentaje
                    	case 4:  
                            $pre_valor = $pre_valor;    
                        break;
                        
                        //Cuota
                        case 5:
                            $pre_valor = $tipo->tdc_cuota;
                        break;
                        
                        //En base a un agrupador
                        case 6:
                            $pre_valor = $this->calcular_agrupado($emp_id, $tipo->tdc_agrupador);
                        break;
                    
                    	//Monto u otro
                        case 1:
                        default :
                            $pre_valor = $row->descuentos;
                    }
                }
                
                //$this->_debug("tdc: $row->tdc, pre_valor: $pre_valor");
            }else{
                $pre_valor = 0;
            }
            
            //aplicamos regla
            switch ($row->deg_aplicacion){ 
            	//Porcentaje  
                case 'P':  $valor =  $pre_valor * ( $row->deg_valor /100); break;
                
                //Valor REGLAS NO DEFINIDAS
            	case 'V':  $valor = $pre_valor;    break;
            
            	default :
            }
            
            //operamos
            switch ($row->deg_signo){ 
            	case '+':  $resultado += $valor; break;
            
            	case '-':  $resultado -= $valor; break;
            
            	default :
            }
        }
        
        return $resultado;
    }
    
    protected function detalle_agrupador($emp_id, $agrupados)
    {
        
    }
    
    public function generar_planilla($data)
    {
        $this->_debug('Planilla Ini');      
        
        //Creamos planilla
        $this->db->insert($this->planillas, array(
            'pla_id_tpl' => $data['tpl_id'],
            'pla_anyo'   => $data['anyo'],
            'pla_mes'    => $data['mes'],
            'pla_dia'    => $data['dia'],
            'pla_fecha'  => date('Y-m-d'),
            'pla_usu_mod'=> $this->tank_auth->get_user_id(),
            'pla_estado' => 0
        ));
        
        $this->db->join($this->tipo_planillas, 'pla_id_tpl = tpl_id');
        $planilla = $this->db->get_where($this->planillas, array('pla_id' => $this->db->insert_id()),1)->row();
        
        $this->_debug("Planilla: $planilla->pla_id");
        
        //Pre cargamos datos
        $this->get_tipos();
        $tpl = $this->db->get_where($this->tipo_planillas, array('tpl_id' => $data['tpl_id']),1)->row();
        $descuentos_base = $this->db->get_where($this->desxplanilla, array('dtp_id_tpl' => $data['tpl_id']))->result();
        $ingresos_base = $this->db->get_where($this->ingxplanilla, array('itp_id_tpl' => $data['tpl_id']))->result();
        
        //obtenemos empleados activos
        $empleados = $this->db->get_where($this->empleados, array('emp_estado' => 1))->result();
        
        $this->_debug('Ingresos INI');
        //calcular los ingresos de cada empleado
        foreach($empleados as $emp){
            
           //print_r($ingresos_base); die();
            
            //los ingresos base de la planilla
            foreach($ingresos_base as $ing){
                if($ing->itp_id_tig == $this->tig_salario){
                    $valor = $this->calcular_agrupado($planilla, $emp->emp_id, $this->agrupador_salario);
                    $this->add_ingreso_detalle($planilla->pla_id, $emp->emp_id, $ing->itp_id_tig, $valor,0,$planilla->tpl_frecuencia);
                }
            }
            
            //los ingresos de cada empleado
            $this->calcular_ingresos($planilla, $emp);
            
            //los descuentos de cada empleado
            $this->calcular_descuentos($planilla, $emp);
        }
        
        $this->_debug('Descuentos INI');
        foreach($descuentos_base as $row)
        {
            //si calculo de ISS
            if($row->dtp_id_tdc == $this->tdc_isss){
                $this->_debug("ISSS Inicio\n");
                foreach($empleados as $emp){
                    $this->calcular_isss($planilla, $emp);
                }
                
                $this->_debug("ISSS Fin");
            }
            
            //si calculo de AFP
            if($row->dtp_id_tdc == $this->tdc_afp){
                $this->_debug("AFP Inicio\n");
                //obtenemos afps
                $this->db->select('
            emp_empleado.emp_id,
            afp_afp.afp_id,
            emp_empleado.emp_salario,
            (emp_empleado.emp_salario / ' . $tpl->tpl_cantidad . ') * (afp_afp.afp_porcentaje / 100) As asegurado,
            (emp_empleado.emp_salario / ' . $tpl->tpl_cantidad . ') * (afp_afp.afp_porcentaje_patrono / 100) As patrono,
            afp_afp.afp_maximo
                ');
                $this->db->join($this->afps, 'emp_id_afp = afp_id');
                $this->db->where(array(
                    'emp_estado' => 1,
                    'afp_activo' => 1
                ));
                $afp_emp = $this->db->get($this->empleados)->result();
                
                //insercion en detalle dss
                foreach($afp_emp as $row_afp){
                    $this->add_descuento_detalle($planilla->pla_id, $row_afp->emp_id, 6, $row_afp->asegurado, $row_afp->emp_salario / $tpl->tpl_cantidad, 0, $row_afp->patrono, $tpl->tpl_frecuencia);
                    $this->_debug("emp_id: $row_afp->emp_id, base: $row_afp->emp_salario, asegurado: $row_afp->asegurado, patrono: $row_afp->patrono");
                }
                
                $this->_debug("AFP Fin");
            }
            
            //si calculo de ISR
            
            if($row->dtp_id_tdc == $this->tdc_isr){
                $this->_debug("ISR Inicio\n");
                $this->calcular_isr($planilla, $empleados);
                $this->_debug("ISR Fin");
            }
            
        }
        
        
        
        //cambiamos estado de planilla
        $this->db->update($this->planillas, array('pla_estado' => 10), array('pla_id' => $planilla->pla_id));
        return $planilla->pla_id;
    }
    
    function print_planilla($planilla_id)
    {
        //planilla
        $this->db->join($this->tipo_planillas, 'pla_id_tpl = tpl_id');
        $planilla = $this->db->get_where($this->planillas, array('pla_id' => $planilla_id),1)->row();
        
        $this->db->select('
  emp_empleado.emp_id,
  emp_empleado.emp_codigo,
  emp_empleado.emp_nombres,
  emp_empleado.emp_apellidos,
  emp_empleado.emp_salario,
  pto_puesto.pto_nombre,
  (Select
    Sum(ddi_detalle_ingreso.ddi_valor)
  From
    ddi_detalle_ingreso
  Where
    ddi_detalle_ingreso.ddi_id_emp = emp_empleado.emp_id And
    ddi_detalle_ingreso.ddi_id_pla = ' . $planilla->pla_id . '
  Group By
    ddi_detalle_ingreso.ddi_id_emp, ddi_detalle_ingreso.ddi_id_pla) As ingresos,
  (Select
    Sum(ddd_detalle_descuento.ddd_valor)
  From
    ddd_detalle_descuento
  Where
    ddd_detalle_descuento.ddd_id_emp = emp_empleado.emp_id And
    ddd_detalle_descuento.ddd_id_pla = ' . $planilla->pla_id . '
  Group By
    ddd_detalle_descuento.ddd_id_emp, ddd_detalle_descuento.ddd_id_pla) As
  descuentos
        ');
        $this->db->join('pto_puesto', 'emp_empleado.emp_id_plz = pto_puesto.pto_id');
        $this->db->group_by('emp_empleado.emp_id');
        $empleados = $this->db->get($this->empleados)->result();
        
        //Detalle de la planilla
        $detalle = ''; $i = 1;
        $renta = $isss = $afp = $des_otros = $des_total = $cancelar = 0;
        $x=1;//bandera que controla la clase de los tr
        foreach($empleados as $empleado){
            
            $descuentos = array();
            foreach($this->get_descuentos_pla_emp($planilla, $empleado) as $row){
                $descuentos[$row->tdc_id] = $row;
            }
            
            $ingresos = array();
            foreach($this->get_ingresos_pla_emp($planilla, $empleado) as $row){
                $ingresos[$row->tig_id] = $row;
            }
            
            $salario = $empleado->emp_salario / $planilla->tpl_cantidad;
            $otros_ingresos = $empleado->ingresos - ($salario);
            $otros_descuentos = $empleado->descuentos - ($descuentos[3]->ddd_valor + $descuentos[6]->ddd_valor + $descuentos[1]->ddd_valor);
            $total = $empleado->ingresos - $empleado->descuentos;
            
            $renta += $descuentos[3]->ddd_valor;
            $afp   += $descuentos[6]->ddd_valor;
            $isss  += $descuentos[1]->ddd_valor;
            $des_otros += $otros_descuentos;
            $des_total += $empleado->descuentos;
            $cancelar += $total;
            
            if($x)
                {
                $valor="impar";
                $x=0;
                }
            else
                {
                $valor="par";
                $x=1;
                }
            $detalle .= "
    <tr class=\"".$valor."\">
        <td  width=\"15\">$i</td>
        <td>$empleado->emp_codigo</td>
        <td>$empleado->emp_nombres $empleado->emp_apellidos</td>
        <td>$empleado->pto_nombre</td>
        
        <td align=\"right\">" . number_format($salario, 2) . "</td>
        <td align=\"right\">" . number_format($otros_ingresos, 2) . "</td>
        <td align=\"right\">" . number_format($empleado->ingresos, 2) . "</td>
        
        <td align=\"right\">" . number_format($descuentos[3]->ddd_valor, 2) . "</td>
        <td align=\"right\">" . number_format($descuentos[6]->ddd_valor, 2) . "</td>
        <td align=\"right\">" . number_format($descuentos[1]->ddd_valor, 2) . "</td>
        <td align=\"right\">" . number_format($otros_descuentos, 2) . "</td>
        <td align=\"right\">" . number_format($empleado->descuentos, 2) . "</td>
        
        <td align=\"right\" width=\"60\">" . number_format($total, 2) . "</td>
    </tr>
            ";
            $i++;
        }
        
        //Totales
        $detalle .= '
   <tr>
        <td colspan="7"></td>
        
        <td align="right">' . number_format($renta,2) . '</td>
        <td align="right">' . number_format($afp,2) . '</td>
        <td align="right">' . number_format($isss,2) . '</td>
        <td align="right">' . number_format($des_otros,2) . '</td>
        <td align="right">' . number_format($des_total,2) . '</td>
        
        <td align="right">' . number_format($cancelar,2) . '</td>
    </tr>
        ';
        
        $data['detalle_planilla'] = $detalle;
        $data['titulo'] = "Planilla";
        
        return $this->load->view('utm/planilla/pla_print', $data, true);
    }
    
    protected function get_ingresos_pla_emp($planilla, $empleado)
    {
        $this->db->select('
  tig_tipo_ingreso.tig_id,
  tig_tipo_ingreso.tig_nombre,
  Sum(Distinct ddi_detalle_ingreso.ddi_valor) As ddi_valor,
  ddi_detalle_ingreso.ddi_dias 
        ');
        $this->db->join($this->tipo_ingresos, 'ddi_detalle_ingreso.ddi_id_tig = tig_tipo_ingreso.tig_id');
        $this->db->where('ddi_id_pla', $planilla->pla_id);
        $this->db->where('ddi_id_emp', $empleado->emp_id);
        $this->db->group_by('tig_id');
        return $this->db->get($this->detalle_ingresos)->result();
    }
    
    protected function get_descuentos_pla_emp($planilla, $empleado)
    {
        $this->db->select('
  tdc_tipo_descuento.tdc_id,
  tdc_tipo_descuento.tdc_nombre,
  ddd_detalle_descuento.ddd_valor,
  ddd_detalle_descuento.ddd_valor_patronal,
  ddd_detalle_descuento.ddd_ingreso_afecto,
  ddd_detalle_descuento.ddd_dias_descuento  
        ');
        $this->db->join($this->tipo_descuentos, 'ddd_detalle_descuento.ddd_id_tdc = tdc_tipo_descuento.tdc_id');
        $this->db->where('ddd_id_pla', $planilla->pla_id);
        $this->db->where('ddd_id_emp', $empleado->emp_id);
        return $this->db->get($this->detalle_descuentos)->result();
    }
    
    protected function add_descuento_detalle($planilla, $empleado, $tdc_id, $valor, $afecto, $dss_id = 0, $patronal = 0, $dias = 0)
    {
        if($dss_id){
            $this->db->set('dss_cuotas_pagadas', 'dss_cuotas_pagadas + 1', false);
            $this->db->where('dss_id', $dss_id);
            $this->db->update($this->descuentos);
        }
        
        return $this->db->insert($this->detalle_descuentos,array(
                    'ddd_id_pla'  => $planilla,
                    'ddd_id_emp'  => $empleado,
                    'ddd_id_tdc'  => $tdc_id,
                    'ddd_valor'   => $valor,
                    'ddd_valor_patronal' => $patronal,
                    'ddd_ingreso_afecto' => $afecto,
                    'ddd_dias_descuento' =>$dias
                    ));
    }
    
    protected function add_ingreso_detalle($planilla, $empleado, $tig_id, $valor, $inn_id = 0, $dias = 0)
    {
        if($inn_id){
            $this->db->set('inn_cuotas_pagadas', 'inn_cuotas_pagadas + 1', false);
            $this->db->where('inn_id', $inn_id);
            $this->db->update($this->ingresos);
        }
        
        return $this->db->insert($this->detalle_ingresos, array(
                    'ddi_id_pla'  => $planilla,
                    'ddi_id_emp'  => $empleado,
                    'ddi_id_tig'  => $tig_id,
                    'ddi_valor'   => $valor,
                    'ddi_dias'    => $dias,
                    'ddi_usu_mod' => $this->tank_auth->get_user_id()
            ));
    }
    
    function generar_boletas($planilla_id)
    {
        $this->_debug('Generar Planilla: ini');
        //planilla
        $this->db->join($this->tipo_planillas, 'pla_id_tpl = tpl_id');
        $planilla = $this->db->get_where($this->planillas, array('pla_id' => $planilla_id),1)->row();
        $this->_debug($this->db->last_query());
        $this->_debug($planilla,'die');
        if(!count($planilla)){
            $this->_debug('Error: Planilla no existe');
            return false;
        }
        
        //obtner empleados afectados
        $this->db->join($this->detalle_ingresos, 'ddi_id_emp = emp_id');
        $empleados = $this->db->get_where($this->empleados, array('ddi_id_pla' => $planilla->pla_id))->result();
        $this->_debug($this->db->last_query());
        $this->_debug($empleados,'die');
        if(!count($empleados)){
            $this->_debug('Sin Empleados');
            return false;
        }
        
        $plazas = array();
        foreach($this->db->get('pto_puesto')->result() as $row){
            $plazas[$row->pto_id] = $row;
        }
        
        $sucursales = array();
        foreach($this->db->get('suc_sucursal')->result() as $row){
            $sucursales[$row->suc_id] = $row;
        }
        
        $afps = array();
        foreach($this->db->get('afp_afp')->result() as $row){
            $afps[$row->afp_id] = $row;
        }
        
        $retorno = array();
        foreach($empleados as $empleado){
            
            //ingresos
            $this->db->select('
  tig_tipo_ingreso.tig_id,
  tig_tipo_ingreso.tig_nombre,
  Sum(Distinct ddi_detalle_ingreso.ddi_valor) As ddi_valor,
  ddi_detalle_ingreso.ddi_dias
            ');
            $this->db->join($this->tipo_ingresos, 'ddi_id_tig = tig_id');
            $this->db->where(array(
                'ddi_id_pla' => $planilla->pla_id,
                'ddi_id_emp' => $empleado->emp_id
            ));
            $this->db->group_by('tig_id');
            $ingresos = $this->db->get($this->detalle_ingresos)->result();
            
            //descuentos
            $this->db->select('
  tdc_tipo_descuento.tdc_id,
  tdc_tipo_descuento.tdc_nombre,
  ddd_detalle_descuento.ddd_valor,
  ddd_detalle_descuento.ddd_valor_patronal,
  ddd_detalle_descuento.ddd_ingreso_afecto,
  ddd_detalle_descuento.ddd_dias_descuento
            ');
            $this->db->join($this->tipo_descuentos, 'ddd_id_tdc = tdc_id');
            $this->db->where(array(
                'ddd_id_pla' => $planilla->pla_id,
                'ddd_id_emp' => $empleado->emp_id
            ));
            $this->db->group_by('tdc_id');
            $descuentos = $this->db->get($this->detalle_descuentos)->result();
            
            //calculos
            $ing = 0;
            foreach($ingresos as $row){
                $ing += $row->ddi_valor;
            }
            $des = 0;
            foreach($descuentos as $row){
                $des += $row->ddd_valor;
            }
            
            //formulario
            $formulario = array(
                'empleado' => $empleado->emp_codigo . ': ' . $empleado->emp_nombres . ' ' . $empleado->emp_apellidos,
                'puesto' => $empleado->emp_id_plz . ': ' . $plazas[$empleado->emp_id_plz]->pto_nombre,
                'afp' => $empleado->emp_id_afp . ': ' . $afps[$empleado->emp_id_afp]->afp_nombre,
                'sucursal' => $empleado->emp_id_suc . ': ' . $sucursales[$empleado->emp_id_suc]->suc_nombre,
                'periodo' => $planilla->pla_anyo . '-' . $planilla->pla_mes,
                'planilla' => $planilla->tpl_nombre,
                'ingresos' => $ing,
                'descuentos' => $des
            );
            
            //armamos
            $retorno[$empleado->emp_id] =  array(
                'ing' => $ingresos,
                'des' => $descuentos,
                'frm' => (Object) $formulario
            );
        }
        
        return $retorno;
    }
    
    protected function get_tipos()
    {
        //tipos de descuentos
        foreach($this->db->get($this->tipo_descuentos)->result() as $row){
            $this->tipos_descuento[$row->tdc_id] = $row;
        }
        
        //tipos de ingresos
        foreach($this->db->get($this->tipo_ingresos)->result() as $row){
            $this->tipos_ingreso[$row->tig_id] = $row;
        }
        
        //afps
        foreach($this->db->get($this->afps)->result() as $row){
            $this->afp[$row->afp_id] = $row;
        }
    } 
    
    //--------- Ingesos
    
    function get_ingresos($emp_id)
    {
        $this->db->select('
  inn_ingreso.inn_id,
  tig_tipo_ingreso.tig_nombre,
  inn_ingreso.inn_valor,
  inn_ingreso.inn_fecha_desde,
  inn_ingreso.inn_fecha_hasta,
  inn_ingreso.inn_cuotas,
  inn_ingreso.inn_cuotas_pagadas
        ');
        $this->db->join($this->tipo_ingresos, 'inn_id_tig = tig_id');
        $this->db->where('inn_id_emp', $emp_id);
        $result = $this->db->get($this->ingresos);
        if($result->num_rows() > 0){
            return $result->result();
        }
        return null;
    }
    
    function add_ingreso_empleado($data)
    {
        return $this->db->insert($this->ingresos,$data);
    }
    
    function delete_ingreso_empleado($data)
    {
        return $this->db->delete($this->ingresos, $data, 1);
    }
    
    //--------- Descuentos
    
    function get_descuentos($emp_id)
    {
        $this->db->select('
  dss_descuento.dss_id,
  tdc_tipo_descuento.tdc_nombre,
  dss_descuento.dss_valor,
  dss_descuento.dss_fecha_desde,
  dss_descuento.dss_fecha_hasta,
  dss_descuento.dss_cuotas,
  dss_descuento.dss_cuotas_pagadas
        ');
        $this->db->join($this->tipo_descuentos, 'dss_id_tdc = tdc_id');
        $this->db->where('dss_id_emp', $emp_id);
        $result = $this->db->get($this->descuentos);
        if($result->num_rows() > 0){
            return $result->result();
        }
        return null;
    }
    
    function get_descuentos_planilla($emp_id)
    {
        
    }
    
    function add_descuento_empleado($data)
    {
        return $this->db->insert($this->descuentos,$data);
    }
    
    function delete_descuento_empleado($data)
    {
        return $this->db->delete($this->descuentos, $data, 1);
    }
    
    //---------
    
    function get_empleado($emp_id)
    {
        $this->db->select('
  emp_empleado.emp_codigo,
  emp_empleado.emp_nombres,
  emp_empleado.emp_apellidos,
  tpl_tipo_planilla.tpl_id,
  tpl_tipo_planilla.tpl_nombre
        ');
        $this->db->join('txe_tipo_planillaxempleado','txe_tipo_planillaxempleado.txe_id_emp = emp_empleado.emp_id');
        $this->db->join($this->tipo_planillas,'txe_tipo_planillaxempleado.txe_id_tpl = tpl_tipo_planilla.tpl_id');
        $res = $this->db->get_where($this->empleados,array('emp_id' => $emp_id),1);
        //echo $this->db->last_query(); die();
        if($res->num_rows() == 1){
            return $res->row();
        }
        return null;
    }
    
    //---------
    
    function get_dropdown($tabla, $campo_display, $campo_value, $where = '')
    {
        $this->db->select($campo_value . ', ' . $campo_display);
        if($where) $this->db->where($where);
        $result = $this->db->get($tabla);
        $return = array();
        foreach ($result->result() as $row){
            $return[$row->$campo_value] = $row->$campo_display;
        }
        return $return;
    }
    
    protected function _now()
    {
        return date('Y-m-d H:i:s');
    }
    
    protected function _debug($msg, $type = 'msj', $time = true){
        if($this->debug){
            if($time) echo date('H:i:s') . '> ' ;
            print_r($msg) . "\n";
            switch ($type){ 
            	case 'die':    die();  break;
            	case '':   break;
            
            	case 'msj':
            	default :
            }
        }
    } 
}

?>