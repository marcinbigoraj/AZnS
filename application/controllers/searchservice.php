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
			
			$allegroResult = $this->allegrowebapisoapclient->searchAuction($keyword, $buyNow, $catId, $offset, $city, 
				$state, $minPrice, $maxPrice, $limit);
				
			print_r($allegroResult);			
			
		}		
	}
	
}

?>