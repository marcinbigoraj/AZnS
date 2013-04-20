<?php

class A extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function clearAuctions() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
		
		$this->allegro_model->clearAuctions();
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
		
		redirect('allegro/lista');
	}

	public function clearCategories() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
				
		$this->allegro_model->clearCategories();
		redirect('allegro/lista');		
	}
	
	public function clearStates() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
		
		$this->allegro_model->clearStates();
		redirect('allegro/lista');
	}
}
?>
