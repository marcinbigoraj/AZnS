<?php

class A extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function clearAuctions() {
		$this -> db -> query("DELETE FROM found_auctions");
		redirect('allegro/lista');
	}

	public function clearCategories() {		
		$this->db->query("DELETE FROM categories");
		redirect('allegro/lista');		
	}
	
	public function clearStates() {
		$this->db->query("DELETE FROM states");
		redirect('allegro/lista');
	}
}
?>
