<?php

class Allegro extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function lista() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}

		$this -> load -> model('allegro_model');
		$data['title'] = "Lista filtrów";
		$data['list'] = $this -> allegro_model -> createList();

		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/lista', $data);
		$this -> load -> view('templates/footer');

	}

	public function dodajFiltr() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}

		$data['title'] = "Dodaj filtr";
		$query= $this -> db -> query("SELECT * FROM states");
		$data['wojewodztwa'] = array();
		foreach($query->result() as $row)
		{
			$data['wojewodztwa'][$row->id_state]=$row->name;
		}
		
		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/dodajFiltr', $data);
		$this -> load -> view('templates/footer');
	}

	public function zapiszFiltr() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}

		if (isset($_POST['dodajFiltr'])) {

			$user_id = $this->ion_auth->user()->row()->id;
			$keywords = $_POST['keywords'];
			$id_cat = $_POST['id_cat'];
			$buyNow = $_POST['buyNow'];
			$city = $_POST['city'];
			$voivodeship =$_POST['voivodeship'];
			$minPrice = $_POST['minPrice'];
			$maxPrice =$_POST['maxPrice'];
			
			if($buyNow=="true")
			{
				$buyNow=1;
			}
			else {
				$buyNow=0;
			}
			
			$this -> load -> database();
			$data = array(
			'user_id' => $user_id,
			'keywords' => $keywords,
			'id_cat' => $id_cat,
			'buyNow' => $buyNow,
			'city' => $city,
			'voivodeship' => $voivodeship,
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice,
			'active' => 1
			);
			$this -> db -> insert('search', $data);
		}

		redirect('allegro/lista');

	}
	
	public function usun($id) {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
		$this -> load -> database();
		$query = $this -> db -> query("SELECT user_id FROM search WHERE id=$id");
		foreach($query->result() as $row)
		{
			$userIdFromDatabase = $row -> user_id;
		}
		$currentUserId = $this->ion_auth->user()->row()->id;
		if($userIdFromDatabase==$currentUserId)
		{
			$this -> db -> query("UPDATE search SET active=0 WHERE id=$id");
		}
		redirect('allegro/lista');

	}

}
?>
