<?php

class Allegro extends CI_Controller
{

	public function __construct() 
	{
		parent::__construct();
	}
	
	public function lista()
	{
		if (!$this->ion_auth->logged_in())
		{	
			redirect('main/index');
		}
		
		$this->load->model('allegro_model');
		$data['title'] = "Lista filtrów";
		$data['list'] =  $this->allegro_model->createList();
		
		$this->load->view('templates/header', $data);
		$this->load->view('allegro/lista', $data);
		$this->load->view('templates/footer');	
		
				
	}

}

?>
