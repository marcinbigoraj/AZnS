<?php

class Allegro_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function createList() {
		
		$query = $this -> db -> query('SELECT id, keywords, id_cat, buyNow, city, s.name, minPrice, maxPrice FROM search INNER JOIN states s ON voivodeship=id_state WHERE active=1');
		
		return $query->result();
	}

}
?>