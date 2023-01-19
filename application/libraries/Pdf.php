<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
 
class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
         $this->CI = get_instance ( );
    }
	
    public function Header() {

    }
    
    public function Footer() {
		// Posisionamieto a -15 mm del eje y
		$this->SetY(-15);
		// Fondo
		$this->SetFont('helvetica', 'I', 8);
		// Numero de pagina
		$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

    }
    
    public function makePDF($output, $orientacion='', $reporte='')
    {
        // set document information$titulo,$header,$body,$generado
        $this->setPrintHeader(false);
        $this->SetSubject('Reporte');
        $this->SetKeywords('Reporte, '.$reporte);
        
        // set font
        $this->SetFont('dejavusans', '', 8);
        
        // add a page
        $this->AddPage($orientacion,'Letter');
        $tbl = '';		
		
		$this->writeHTML($tbl.$output, true, false, false, false, '');
        //$pdf->writeHTML($tbl, true, false, false, false, '');
        
        // print a line using Cell()
        //$this->Cell(0, 12, 'Example 001 - Sapos Ã¨Ã©Ã¬Ã²Ã¹', 1, 1, 'C');
        
        //Close and output PDF document
        if (ob_get_contents()) ob_end_clean();
        $this->Output('Reporte_'.$reporte.'_'.date('Y-m-d_H-m-s').'.pdf', 'D');
	}
    
    /**
     * Pdf::boletaPDF()
     * 
     * @param string $destino D, I
     * @return void
     */
    public function boletaPDF($destino = 'D')
    {
        
        // set document information$titulo,$header,$body,$generado
        $this->setPrintHeader(false);
        $this->SetSubject('Boletas de Pago');
        $this->SetKeywords('Reporte, sistema');
        
        // set font
        //$this->SetFont('dejavusans', '', 2);
        
        if (ob_get_contents()) ob_end_clean();
        $this->Output('Boletas_'.date('Y-m-d-H-m-s').'.pdf', $destino);
    }
    
    public function addBoleta($html)
    {
        $this->SetMargins(3,3,3,3);
        $this->addPagina($html, 'L', 
            array(107, 140),
            array(
                'family' => 'courier',
                'style'  => '',
                'size'   => 6
            ));
    }

     public function addBoleta_f($html)
    {
       
        $this->addPagina($html, 'P', 
            
            array(
                'family' => 'courier',
                'style'  => '',
                'size'   => 5
            ));
    }
    
    public function addPagina($html, $display= '', $size = 'Letter', $font = null, $header = false, $footer = false)
    {
        if($font){
            $this->SetFont($font['family'], $font['style'], $font['size']);
        }else{
            $this->SetFont('dejavusans', '', 8);
        }
        $this->AddPage($display, $size);
        $this->writeHTML($html);
        
        $this->setPrintFooter($header);
        $this->setPrintFooter($footer);
    }

    public function reportePDF($vista, $data, $reporte, $orientacion='')
    {
        $CI =& get_instance();
        $header['titulo']=$reporte;
        $reporte=$this->CI->load->view('reporte/header',$header,true);
        $reporte.=$this->CI->load->view($vista,$data,true);
        //print_r($reporte);
        //exit();
        $this->makePDF($reporte, $orientacion, $header['titulo']);
       
    }
  
   public function Generar_constancia_pdf($view,$data,$reporte,$orientacion='')
   {
        $CI =& get_instance();
        $header['titulo'] = $reporte;
        $reporte=$this->CI->load->view('reporte/header_constancia',$header,true);
        $reporte.= $this->CI->load->view($view,$data,true);
        $this->makePDF($reporte,$orientacion,$header['titulo']);    
   }


    public function partidaPDF($output,$tabla,$marginaciones='')
    {
        // set document information$titulo,$header,$body,$generado
        $this->setPrintHeader(false);
         $this->setPrintFooter(false);
        $this->SetSubject('Reporte');
        $this->SetKeywords('Reporte, sistema');
        $this->SetMargins (0, 0, -20,false);
        $this->setCellPaddings (-20, -20, -20, -20);
        $this->setFooterMargin (-1000);
        // set font
        $this->SetFont('dejavusans', '', 8);
        $this->SetAutoPageBreak(false);
        // add a page
        $this->AddPage('','Letter');
        $tbl = '';      
        
        $this->writeHTML($tbl.$output);

        if($marginaciones!='')
        {
            $this->SetMargins (20, 20, 20,true);
            $this->AddPage('','Letter');
            $tbl = '';
            $this->writeHTML($tbl.$marginaciones);
        }


        //$pdf->writeHTML($tbl, true, false, false, false, '');
        
        // print a line using Cell()
        //$this->Cell(0, 12, 'Example 001 - Sapos Ã¨Ã©Ã¬Ã²Ã¹', 1, 1, 'C');
        
        //Close and output PDF document
        if (ob_get_contents()) ob_end_clean();
        $this->Output($tabla.'_'.date('Y-m-d-H-m-s').'.pdf', 'D');
    }

}