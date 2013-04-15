<?php

class SearchService extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function search() {
		$this -> load -> library('allegrowebapisoapclient');
		$this -> load -> helper('email');
		
		// $sendData = array(
// 			
			// 'telefon' => '',
			// 'email' => '',
// 			
			// aukcja => array(
				// //dane aukcji
			// )
// 		
		// );

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
						foreach ($allegroResult -> searchArray -> item as $item) {

							$query = $this -> db -> query("SELECT * FROM found_auctions WHERE id_user=$currentUserId AND id_auc=$item->sItId");
							if ($query -> num_rows() == 0) {
								array_push($data['searchArray'], $item);

							}

						}

						$offset += $limit;
					}

				} while ($offset < $allegroResult->searchCount);
			} catch(Exception $exception ) {

			}
		}

		if (!empty($data['searchArray'])) {
			//przydałoby się tutaj ignorowanie błędów bazy
			foreach ($data['searchArray'] as $item) {

				$this -> db -> query("INSERT INTO found_auctions VALUES($currentUserId,$item->sItId)");

			}

			$message = "<table>";
			foreach ($data['searchArray'] as $item) {
				$message .= "<tr>";

				if ($item -> sItThumb == 1) {
					$message .= '<td rowspan="2"><img src="' . $item -> sItThumbUrl . '" alt="' . $item -> sItName . '" /></td>';
				} else {
					$message .= '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" /></td>';
				}

				$message .= '<td>' . $item -> sItId . ' - ' . $item -> sItName . '</td>';
				$message .= '<td>';
				$message .= 'Aktualna cena: ' . $item -> sItPrice . '<br />';

				if ($item -> sItIsBuyNow != 0) {
					$message .= 'Cena kup teraz: ' . $item -> sItIsBuyNow;
				}

				$message .= '</td></tr><tr>';
				$message .= '<td>Pozostało: ' . $item -> sItTimeLeft . ' | Kategoria: ' . $item -> sItCategoryId . '</td>';
				$message .= '<td>Locaklizacja: ' . $item -> sItCity . ' | ' . $item -> sItCountry . '</td></tr>';
			}

			$message .= "</table>";

			$query = $this -> db -> query("SELECT email FROM users WHERE id=$currentUserId");

			if ($query -> num_rows() > 0) {
				$row = $query -> row();

				$mail = $row -> email;
				$subject = "Powiadomienie z dnia " . date("Y-m-d H:i:s");
				mail($mail, $subject, $message);
			}
		}

		$this -> load -> view('templates/header', $data);
		$this -> load -> view('search_items_view', $data);
		$this -> load -> view('templates/footer');

	}


	public function searchAllNews() {
		
		$this -> load -> library('allegrowebapisoapclient');		

		$filterQuery = $this->db->query("
			SELECT u.username, u.email, u.phone, s.* FROM search s
			INNER JOIN users u ON u.id = s.user_id
			WHERE s.active = 1
		");

		$data['title'] = "Wyszukane aukcje";
		$data['searchArray'] = array();
		$searchedData = array();

		foreach ($filterQuery->result() as $row) 
		{

			$userId = $row->user_id;
			$username = $row->username;
			$email = $row->email;
			$phone = $row->phone;
			
			$keyword = $row->keywords;
			$catId = $row->id_cat;
			$buyNow = $row->buyNow;
			$city = $row->city;
			$state = $row->voivodeship;
			$minPrice = $row->minPrice;
			$maxPrice = $row->maxPrice;

			$offset = 0;
			$limit = 100;
			
			$auctionForUserIdQuery = $this->db->query("SELECT * FROM found_auctions WHERE id_user=$userId");
				
			try 
			{
				
				do 
				{

					$allegroResult = $this->allegrowebapisoapclient->searchAuction($keyword, $buyNow, $catId, $offset, $city, 
						$state, $minPrice, $maxPrice, $limit);
					
					if ($allegroResult->searchCount != 0) 
					{
						
						foreach ($allegroResult->searchArray->item as $item) 
						{

							if (!$this->isAuctionIdInArray($auctionForUserIdQuery->result(), $item->sItId))
							{
								
								if (!isset($searchedData[$userId]))
								{
									$searchedData[$userId] = array(
										'username' => $username,
										'email' => $email,
										'phone' => $phone,
										'auctions' => array(),
										'filter' => $row
									);
								}
								
								array_push($searchedData[$userId]['auctions'], $item);	
								
							//	$this->db->query("INSERT INTO found_auctions VALUES($userId,$item->sItId)");
															
							}

						}

						$offset += $limit;
						
					}

				} 
				while ($offset < $allegroResult->searchCount);
				
			} 
			catch(Exception $exception ) 
			{

			}
			
		}

		$this->sendSearchedData($searchedData);	
	
		$this -> load -> view('templates/header', $data);
		$this -> load -> view('search_items_view', $data);
		$this -> load -> view('templates/footer');

	}

	private function sendSearchedData($searchedData)
	{
		$this -> load -> helper('email');
		
		foreach ($searchedData as $user)
		{
			$username = $user['username'];
			$email = $user['email'];
			$phone = $user['phone'];
			
			if ($user['auctions'] > 0) 
			{
				
				$message = "<table>";
				
				foreach ($user['auctions'] as $auction)
				{					
					
					$message .= "<tr>";
	
					if ($auction->sItThumb == 1) 
					{
						$message .= '<td rowspan="2"><img src="' . $auction->sItThumbUrl . '" alt="' . $auction->sItName . '" /></td>';
					} 
					else 
					{
						$message .= '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" /></td>';
					}
	
					$message .= '<td><a href="http://testwebapi.pl/show_item.php?item='.$auction->sItId.'">' . $auction->sItId . ' - ' . $auction->sItName . '</a></td>';
					$message .= '<td>';
					$message .= 'Aktualna cena: ' . $auction->sItPrice . '<br />';
	
					if ($auction->sItIsBuyNow != 0) 
					{
						$message .= 'Cena kup teraz: ' . $auction->sItIsBuyNow;
					}
	
					$message .= '</td></tr><tr>';
					$message .= '<td>Pozostało: ' . $auction->sItTimeLeft . ' | Kategoria: ' . $auction->sItCategoryId . '</td>';
					$message .= '<td>Locaklizacja: ' . $auction->sItCity . ' | ' . $auction->sItCountry . '</td></tr>';
					
				}
	
				$message .= "</table>";
				
				echo $message;
					
			}
		}
		
	}

	private function isAuctionIdInArray($result, $id) 
	{
		
		foreach ($result as $item) 
		{
			if ($item->id_auc == $id) 
			{
				return true;
			}
		}
		return false;
		
	}

}
?>