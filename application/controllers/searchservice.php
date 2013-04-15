<?php

class SearchService extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function search() {
		$this -> load -> library('allegrowebapisoapclient');
		$this -> load -> helper('email');

		$currentUserId = $this -> ion_auth -> user() -> row() -> id;
		$query = $this -> db -> query("SELECT * FROM search WHERE active = 1 AND user_id=$currentUserId");

		$data['title'] = "Wyszukane aukcje";
		$data['searchArray'] = array();

		foreach ($query->result() as $row) {

			$keyword = $row -> keywords;
			$catId = $row -> id_cat;
			$buyNow = $row -> buyNow;
			$city = $row -> city;
			$state = $row -> voivodeship;
			$minPrice = $row -> minPrice;
			$maxPrice = $row -> maxPrice;

			$offset = 0;
			$limit = 100;

			try {
				do {

					$allegroResult = $this -> allegrowebapisoapclient -> searchAuction($keyword, $buyNow, $catId, $offset, $city, $state, $minPrice, $maxPrice, $limit);
					if ($allegroResult -> searchCount != 0) {
						foreach($allegroResult -> searchArray -> item as $item)
						{
							
							$query = $this->db->query("SELECT * FROM found_auctions WHERE id_user=$currentUserId AND id_auc=$item->sItId");
							if($query->num_rows() == 0)
							{
								array_push($data['searchArray'], $item);
								
							}
							
						}

						$offset += $limit;
					}

				} while ($offset < $allegroResult->searchCount);
			} catch(Exception $exception ) {

			}
		}

		//przydałoby się tutaj ignorowanie błędów bazy
		foreach($data['searchArray'] as $item)
		{
			
			$this->db->query("INSERT INTO found_auctions VALUES($currentUserId,$item->sItId)");
			
		}
		
		
		

		$this -> load -> view('templates/header', $data);
		$this -> load -> view('search_items_view', $data);
		$this -> load -> view('templates/footer');

	}

}
?>