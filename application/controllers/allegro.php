<?php

class Allegro extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function lista() {
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}

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
		
		if (isset($_POST['dodajFiltr'])) 
		{
			$this->zapiszFiltr();
		}

		$data['title'] = "Dodaj filtr";
		$data['wojewodztwa'] = array();
		foreach($this->allegro_model->getStates() as $row)
		{
			$data['wojewodztwa'][$row->id_state]=$row->name;
		}
		
		$data['kategorie'] = $this->allegro_model->getCategories();
		
		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/dodajFiltr', $data);
		$this -> load -> view('templates/footer');
	}

	private function zapiszFiltr() {

		if ($this->validateFilterForm()) 
		{
	
			$user_id = $this->ion_auth->user()->row()->id;
			$keywords = $_POST['keywords'];
			$id_cat = $_POST['id_cat'];
			$anyWord = $_POST['anyWord'];
			$includeDescription = $_POST['includeDescription'];
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
			
			if($anyWord=="true")
			{
				$anyWord=1;
			}
			else {
				$anyWord=0;
			}
			
			if($includeDescription=="true")
			{
				$includeDescription=1;
			}
			else {
				$includeDescription=0;
			}

			$data = array(
			'user_id' => $user_id,
			'keywords' => $keywords,
			'id_cat' => $id_cat,
			'anyWord' => $anyWord, 
			'includeDescription' => $includeDescription,
			'buyNow' => $buyNow,
			'city' => $city,
			'voivodeship' => $voivodeship,
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice,
			'active' => 1
			);
			$this->allegro_model->addFilter($data);
	
			redirect('allegro/lista');
		
		}

	}
	
	public function usun($id) {
		
		$currentUserId = $this->ion_auth->user()->row()->id;
		if (!$this -> ion_auth -> logged_in() || $this->allegro_model->getUserIdFromFilterId($id) != $currentUserId) {
			redirect('authentication/login');
		}
	
		$this->allegro_model->setFilterActive(0, $id);
		redirect('allegro/lista');

	}

	public function edytuj($id){
		
		$currentUserId = $this->ion_auth->user()->row()->id;
		if(!$this -> ion_auth -> logged_in() || $this->allegro_model->getUserIdFromFilterId($id) != $currentUserId)
		{
			redirect('authentication/login');
		}
		
		if (isset($_POST['zapiszZmiany']))
		{
			$this->zapiszWyedytowanyFiltr($id);
		}
		
		$data['title'] = "Edytuj filtr";
		$data['wojewodztwa'] = array();
		foreach($this->allegro_model->getStates() as $row)
		{
			$data['wojewodztwa'][$row->id_state]=$row->name;
		}
		
		$data['kategorie'] = $this->allegro_model->getCategories();
		
		if (!isset($_POST['zapiszZmiany']))
		{
		
			foreach($this->allegro_model->getFilterById($id) as $row)
			{
				$minPrice = $row->minPrice;
				if ($minPrice == 0)
				{
					$minPrice = '';
				} 
				
				$maxPrice = $row->maxPrice;
				if ($maxPrice == 0)
				{
					$maxPrice = '';
				}
				
				$zapisaneDane = array(
				'id'=>$id,
				'keywords'=> $row->keywords,
				'id_cat'=> $row->id_cat,
				'anyWord'=> $row->anyWord,
				'includeDescription'=> $row->includeDescription,
				'buyNow'=> $row->buyNow,
				'city'=> $row->city,
				'voivodeship'=> $row->voivodeship,
				'minPrice'=> $minPrice,
				'maxPrice'=> $maxPrice
				);
			}

		}
		else 
		{
			
			$keywords = $_POST['keywords'];
			$id_cat = $_POST['id_cat'];
			$anyWord = isset($_POST['anyWord']) ? $_POST['anyWord'] : "false";
			$includeDescription = isset($_POST['includeDescription']) ? $_POST['includeDescription'] : "false";
			$buyNow = isset($_POST['buyNow']) ? $_POST['buyNow'] : "false";
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
			
			if($anyWord=="true")
			{
				$anyWord=1;
			}
			else {
				$anyWord=0;
			}
			
			if($includeDescription=="true")
			{
				$includeDescription=1;
			}
			else {
				$includeDescription=0;
			}
			
			$zapisaneDane = array(
				'id'=>$id,
				'keywords'=> $keywords,
				'id_cat'=> $id_cat,
				'anyWord'=> $anyWord,
				'includeDescription'=> $includeDescription,
				'buyNow'=> $buyNow,
				'city'=> $city,
				'voivodeship'=> $voivodeship,
				'minPrice'=> $minPrice,
				'maxPrice'=> $maxPrice
				);
		}
		
		$data['zapisaneDane']=$zapisaneDane;
		
		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/edytujFiltr', $data);
		$this -> load -> view('templates/footer');
	}

	private function zapiszWyedytowanyFiltr($id) {
		
		if ($this->validateFilterForm())  
		{

			$keywords = $_POST['keywords'];
			$id_cat = $_POST['id_cat'];
			$anyWord = $_POST['anyWord'];
			$includeDescription = $_POST['includeDescription'];
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
			
			if($anyWord=="true")
			{
				$anyWord=1;
			}
			else {
				$anyWord=0;
			}
			
			if($includeDescription=="true")
			{
				$includeDescription=1;
			}
			else {
				$includeDescription=0;
			}

			$data = array(
			'keywords' => $keywords,
			'id_cat' => $id_cat,
			'anyWord' => $anyWord, 
			'includeDescription' => $includeDescription,
			'buyNow' => $buyNow,
			'city' => $city,
			'voivodeship' => $voivodeship,
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice
			);
			
			$this->allegro_model->updateFilter($data, $id);
			$this->allegro_model->setFilterBlocked(0, $id);
			
			redirect('allegro/lista');
		
		}

	}

	private function validateFilterForm() 
	{
		
		$this->form_validation->set_rules('keywords', 'Słowa kluczowe', 'trim|required|min_length[3]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('city', 'Miasto', 'trim|max_length[50]|xss_clean');
		$this->form_validation->set_rules('minPrice', 'Cena minimalna', 'is_natural|less_than[1000000]');
		$minPrice = ($_POST['minPrice'] == '') ? 0 : $_POST['minPrice'];
		$this->form_validation->set_rules('maxPrice', 'Cena maksymalna', 'is_natural|less_than[100000]|greater_than['.$minPrice.']');
		
		if ($this->form_validation->run())
		{
			return true;
		}
		
		return false;
		
	}

}
?>
