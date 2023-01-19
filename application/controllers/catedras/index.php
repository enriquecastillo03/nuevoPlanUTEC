<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Index extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		if (!$this->tank_auth->is_logged_in()) {
			redirect('/auth/login/');
		} else {
			echo $this->uri->segment(3);
			$data=$this->masterpage->getUsuario();
			$this->masterpage->setMasterPage('masterpage_default');
			$this->masterpage->addContentPage('sistema/index', 'content',$data);
			$this->masterpage->show();
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */