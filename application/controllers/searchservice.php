<?php

class SearchService extends CI_Controller
{
	
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function search()
	{
		$this->load->library('allegrowebapisoapclient');
		$this->load->helper('email');
		$query = $this->db->query("SELECT * FROM search WHERE active = 1 ORDER BY user_id");
		
		$data['title'] = "Wyszukane aukcje";
		$data['searchArray'] = array();
		
		foreach ($query->result() as $row) 
		{

			$keyword = $row->keywords;				
			$catId = $row->id_cat;		
			$buyNow = $row->buyNow;
			$city = $row->city;
			$state = $row->voivodeship;
			$minPrice = $row->minPrice;
			$maxPrice = $row->maxPrice;
			
			$offset = 0;
			$limit = 100;
			
			do
			{				
				$allegroResult = $this->allegrowebapisoapclient->searchAuction($keyword, $buyNow, $catId, $offset, $city, 
					$state, $minPrice, $maxPrice, $limit);

				$data['searchArray'] = $allegroResult->searchArray->item;
				
				$offset += $limit;
			} 
			while ($offset < $allegroResult->searchCount);
						
		}

		$this->load ->view('templates/header', $data);		
		$this->load->view('search_items_view', $data);		
		$this->load->view('templates/footer');
		
	}
	
	
}

?>