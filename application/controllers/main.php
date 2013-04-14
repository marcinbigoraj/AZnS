<?php

class Main extends CI_Controller
{

	public function __construct() 
	{
		parent::__construct();
	}
	
	public function index() 
	{
		
		if (!$this->ion_auth->logged_in())
		{
			redirect('authentication/login');
		}
		
		$data['title'] = "Strona główna";
		
		$this->load->view('templates/header', $data);
		$this->load->view('main/index', $data);
		$this->load->view('templates/footer');	
		
	}

}

?>