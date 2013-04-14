<?php

class Allegro_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function createList() {
		//tu funkcje wyciągające z bazy danych
		$list = array( array("nazwaFiltru" => "Pierwszy", "test" => "test"), array("nazwaFiltru" => "Drugi", "test" => "test2"));

		return $list;
	}

}
?>