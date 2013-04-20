<?php

class Test extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
	}

	public function clearAuctions() 
	{		
		if (!$this -> ion_auth -> logged_in()) 
		{
			redirect('authentication/login');
		}
		
		$this -> allegro_model -> clearAuctions();
		redirect('allegro/filtersList');
	}

	public function clearCategories() 
	{
		if (!$this -> ion_auth -> logged_in()) 
		{
			redirect('authentication/login');
		}
				
		$this->allegro_model->clearCategories();
		redirect('allegro/filtersList');		
	}
	
	public function clearStates() 
	{
		if (!$this -> ion_auth -> logged_in()) 
		{
			redirect('authentication/login');
		}
		
		$this->allegro_model->clearStates();
		redirect('allegro/filtersList');
	}
}
?>
