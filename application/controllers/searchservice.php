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

}
?>