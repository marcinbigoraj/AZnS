<?php

class A extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function b() {
		$this -> db -> query("DELETE FROM found_auctions");
		redirect('allegro/lista');

	}

}
?>
