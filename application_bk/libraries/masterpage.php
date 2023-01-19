<?php if ( ! defined ( 'BASEPATH' ) ) exit ( 'No direct script access allowed.' );
/**
 * @author Kim Johansson <hagbarddenstore@gmail.com>
 * @copyright Copyright (c) 2008, Kim Johansson
 *
 * @version 0.0.1
 */
class MasterPage {
    private $masterPage = '';
    private $contentPages = array ( );
    private $ci = null;

    /**
     * @access public
     * @param string $masterPage Optional file to use as MasterPage.
     */
    public function __construct ( $masterPage = '' ) {       
        $this->CI = get_instance ( );
        if ( ! empty ( $masterPage ) )
            $this->setMasterPage ( $masterPage );       
        $this->CI->load->model('sistema');
    }

    /**
     * @access public
     * @param string $masterPage File to use as MasterPage.
     */
    public function setMasterPage ( $masterPage ) {
        // Check if the supplied masterpage exists.
        if ( ! file_exists ( APPPATH . 'views/' . $masterPage . EXT ) )
            throw new Exception ( APPPATH . 'views/' . $masterPage . EXT );
        $this->masterPage = $masterPage;
    }

    /**
     * @access public
     * @return string The current MasterPage.
     */
    public function getMasterPage ( ) {
        return $this->masterPage;
    }

    /**
     * @access public
     * @param string $file The view file to add.
     * @param string $tag The tag in the MasterPage it should match.
     * @param mixed $content The content to be used in the view file.
     */
    public function addContentPage ( $file, $tag, $content = array ( ) ) {
        $this->contentPages[$tag] = $this->CI->load->view ( $file, $content, true );
    }

    /**
     * @access public
     * @param array $content Optional content to be added to the MasterPage.
     */
    public function show ( $content = array ( ) ) {
        // Get the content of the MasterPage and replace all matching tags with their
        // respective view file content.
        $masterPage = $this->CI->load->view ( $this->masterPage, $content, true );
        foreach ( $this->contentPages as $tag => $content ) {
            $masterPage = str_replace ( '<mp:' . ucfirst ( strtolower ( $tag ) ) . ' />',
            $content, $masterPage );
        }

        // Finally, print the data.
        echo $masterPage;
    }

    public function getUsuario()
    {
        $CI =& get_instance();
        $this->CI->load->model('sistema');
        $id = $this->CI->tank_auth->get_user_id();
        $modulo=$this->CI->sistema->modulo_actual(strtolower($CI->uri->segment(1)));
        $data['menu0']=$this->CI->sistema->cargar_menus($id,0);
        $data['menu1']=$this->CI->sistema->cargar_menus($id,1,$modulo['opc_id']);
        $data['menu2']=$this->CI->sistema->cargar_menus($id,2);
        $data['user_id']    = $id;
        $data['username']   = $this->CI->tank_auth->get_username();
        $data['titulo'] =  strtolower($CI->uri->segment(1));
        $data['modulo'] =  strtolower($CI->uri->segment(1));
        $data['control'] =$this->CI->router->class;
        $data['funcion'] = strtolower($this->CI->router->fetch_method());
        $funcion=$data['funcion'];
        $descripcion=strtolower($CI->uri->segment(1));
        if($funcion=="index")
        {
            $funcion=strtolower($CI->uri->segment(2));           
        }
        if($funcion=="index")
            $descripcion=strtoupper($data['modulo']);

        if($funcion!="")
        {
            foreach ($data['menu0'] as $key) {            
                if(in_array($funcion, $key))
                    $descripcion=$key['opc_nombre'];                
            }
            foreach ($data['menu1'] as $key) {            
                if(in_array($funcion, $key))
                    $descripcion=$key['opc_nombre'];                
            }
            foreach ($data['menu2'] as $key) {            
                if(in_array($funcion, $key))
                    $descripcion=$key['opc_nombre'];                
            }        
            $data['descripcion'] = $descripcion;
        }        
        else
            $data['descripcion']="";
        $data['cabecera']= (isset($modulo['opc_nombre'])?$modulo['opc_nombre']:'');      
        return $data;
    }
}
?>