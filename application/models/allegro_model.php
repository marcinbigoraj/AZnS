<?php

class Allegro_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function createList() {
		
		$query = $this -> db -> query('SELECT id, keywords, c.name AS "categoryName", buyNow, city, s.name AS "stateName", minPrice, maxPrice FROM search se
		INNER JOIN states s ON voivodeship=id_state 
		INNER JOIN categories c ON se.id_cat=c.id_cat WHERE active=1');
		
		return $query->result();
	}

}
?>