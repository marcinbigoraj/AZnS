<?php

class Allegro extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function filtersList() 
	{
		if (!$this -> ion_auth -> logged_in()) 
		{
			redirect('authentication/login');
		}

		$data['title'] = "Lista filtrów";
		$data['list'] = $this -> allegro_model -> createList();

		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/filtersList', $data);
		$this -> load -> view('templates/footer');

	}

	public function addFilter() 
	{
		if (!$this -> ion_auth -> logged_in()) 
		{
			redirect('authentication/login');
		}
		
		if (isset($_POST['addFilter'])) 
		{
			$this -> saveFilter();
		}

		$data['title'] = "Dodaj filtr";
		$data['states'] = array();
		foreach($this->allegro_model->getStates() as $row)
		{
			$data['states'][$row->id_state]=$row->name;
		}
		
		$data['categories'] = $this->allegro_model->getCategories();
		
		$this -> load -> view('templates/header', $data);
		$this -> load -> view('allegro/addFilter', $data);
		$this -> load -> view('templates/footer');
	}

	private function saveFilter() {

		if ($this->validateFilterForm()) 
		{
	
			$user_id = $this -> ion_auth -> user() -> row() -> id;
			$keywords = $_POST['keywords'];
			$id_cat = $_POST['id_cat'];
			$anyWord = isset($_POST['anyWord']) ? $_POST['anyWord'] : "false";
			$includeDescription = isset($_POST['includeDescription']) ? $_POST['includeDescription'] : "false";
			$buyNow = isset($_POST['buyNow']) ? $_POST['buyNow'] : "false";
			$city = $_POST['city'];
			$voivodeship = $_POST['voivodeship'];
			$minPrice = $_POST['minPrice'];
			$maxPrice =$_POST['maxPrice'];
			
			if($buyNow == "true")
			{
				$buyNow = 1;
			}
			else {
				$buyNow = 0;
			}
			
			if($anyWord == "true")
			{
				$anyWord = 1;
			}
			else {
				$anyWord = 0;
			}
			
			if($includeDescription == "true")
			{
				$includeDescription = 1;
			}
			else {
				$includeDescription = 0;
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
			
			$this -> allegro_model -> addFilter($data);
	
			redirect('allegro/filtersList');
		
		}

	}
	
	public function deleteFilter($id) 
	{
		
		$currentUserId = $this->ion_auth->user()->row()->id;
		if (!$this -> ion_auth -> logged_in() || $this->allegro_model->getUserIdFromFilterId($id) != $currentUserId) {
			redirect('authentication/login');
		}
	
		$this->allegro_model->setFilterActive(0, $id);
		redirect('allegro/filtersList');

	}

	public function editFilter($id){
		
		$currentUserId = $this -> ion_auth -> user() -> row() -> id;
		if(!$this -> ion_auth -> logged_in() || $this -> allegro_model -> getUserIdFromFilterId($id) != $currentUserId)
		{
			redirect('authentication/login');
		}
		
		if (isset($_POST['saveChanges']))
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
			
			if($buyNow == "true")
			{
				$buyNow = 1;
			}
			else 
			{
				$buyNow = 0;
			}
			
			if($anyWord == "true")
			{
				$anyWord = 1;
			}
			else 
			{
				$anyWord = 0;
			}
			
			if($includeDescription == "true")
			{
				$includeDescription = 1;
			}
			else 
			{
				$includeDescription = 0;
			}
			
			$savedData = array(
				'id' => $id,
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
			
			$this -> saveEditedFilter($id, $savedData);
		}
		
		$data['title'] = "Edytuj filtr";
		$data['states'] = array();
		foreach($this -> allegro_model -> getStates() as $row)
		{
			$data['states'][$row -> id_state]=$row -> name;
		}
		
		$data['categories'] = $this -> allegro_model -> getCategories();
		
		if (!isset($_POST['saveChanges']))
		{
		
			foreach($this -> allegro_model -> getFilterById($id) as $row)
			{
				$minPrice = $row -> minPrice;
				if ($minPrice == 0)
				{
					$minPrice = '';
				} 
				
				$maxPrice = $row -> maxPrice;
				if ($maxPrice == 0)
				{
					$maxPrice = '';
				}
				
				$savedData = array(
					'id' => $id,
					'keywords' => $row -> keywords,
					'id_cat' => $row -> id_cat,
					'anyWord' => $row -> anyWord,
					'includeDescription' => $row -> includeDescription,
					'buyNow' => $row -> buyNow,
					'city' => $row -> city,
					'voivodeship' => $row -> voivodeship,
					'minPrice' => $minPrice,
					'maxPrice' => $maxPrice
				);
			}

		}
		
		$data['savedData'] = $savedData;
		
		$this  ->  load -> view('templates/header', $data);
		$this -> load -> view('allegro/editFilter', $data);
		$this -> load -> view('templates/footer');
	}

	private function saveEditedFilter($id, $data) {
		
		if ($this -> validateFilterForm())  
		{

			$this -> allegro_model -> updateFilter($data, $id);
			$this -> allegro_model -> setFilterBlocked(0, $id);
			
			redirect('allegro/filtersList');
		
		}

	}

	private function validateFilterForm() 
	{
		
		$this -> form_validation -> set_rules('keywords', 'Słowa kluczowe', 'trim|required|min_length[3]|max_length[20]|xss_clean');
		$this -> form_validation -> set_rules('city', 'Miasto', 'trim|max_length[50]|xss_clean');
		$this -> form_validation -> set_rules('minPrice', 'Cena minimalna', 'is_natural|less_than[1000000]');
		
		$minPrice = ($_POST['minPrice'] == '') ? 0 : $_POST['minPrice'];
		$maxPrice = ($_POST['maxPrice'] == '') ? 0 : $_POST['maxPrice'];
		
		if ($maxPrice != 0)
		{
			$this -> form_validation -> set_rules('maxPrice', 'Cena maksymalna', 'is_natural|less_than[1000000]|greater_than['.$minPrice.']');
		}
		
		if ($this -> form_validation -> run())
		{
			return true;
		}
		
		return false;
		
	}

}
?>
