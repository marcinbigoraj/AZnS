<?php

class Allegro_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function createList() {
		
		$query = $this -> db -> query('SELECT id, keywords, id_cat, buyNow, city, voivodeship, minPrice, maxPrice FROM search WHERE active=1');

		return $query->result();
	}

}
?>