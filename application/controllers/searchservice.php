<?php

class SearchService extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function searchAllNews() {
		
		$this->load->library('allegrowebapisoapclient');	
		$this->load->library('phpmailer/phpmailer');	
		
		$filterQuery = $this->db->query("
			SELECT u.username, u.email, u.phone, s.* FROM search s
			INNER JOIN users u ON u.id = s.user_id
			WHERE s.active = 1
		");

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
										'auctions' => array(),
										'filter' => $row
									);
								}
								
								array_push($searchedData[$userId]['auctions'], $item);	
								
								//$this->db->query("INSERT INTO found_auctions VALUES($userId,$item->sItId)");
															
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

	}

	private function sendSearchedData($searchedData)
	{

		foreach ($searchedData as $user)
		{
			$username = $user['filter']->username;
			$email = $user['filter']->email;
			$phone = $user['filter']->phone;
			
			$keyword = $user['filter']->keywords;
			$catId = $user['filter']->id_cat;
			$buyNow = $user['filter']->buyNow;
			$city = $user['filter']->city;
			$state = $user['filter']->voivodeship;
			$minPrice = $user['filter']->minPrice;
			$maxPrice = $user['filter']->maxPrice;
			
			if ($user['auctions'] > 0) 
			{
				
				$message = '<table><tr>';
				
				$message .= '<td>'.$keyword.'</td>';
				$message .= '<td>'.$catId.'</td>';
				$message .= '<td>'.$buyNow.'</td>';
				$message .= '<td>'.$city.'</td>';
				$message .= '<td>'.$state.'</td>';
				$message .= '<td>'.$minPrice.'</td>';
				$message .= '<td>'.$maxPrice.'</td>';
				
				$message .= '</table></tr>';
				
				$message .= "<table>";
				
				foreach ($user['auctions'] as $auction)
				{					
					
					$message .= "<tr>";
	
					if ($auction->sItThumb == 1) 
					{
						$message .= '<td rowspan="2"><img src="' . $auction->sItThumbUrl . '" alt="' . $auction->sItName . '" style="width: 100px; height: auto;" /></td>';
					} 
					else 
					{
						$message .= '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" style="width: 100px; height: auto;" /></td>';
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
				$title = "Allegro Search Service z dnia " . date("H:i:s d-m-Y"); 
				$this->mailSender($title, $message, $email);
					
			}
		}
		
	}

	private function mailSender($Subject, $BodyHtml, $To)
	{
		$this->phpmailer->IsSMTP();
		$this->phpmailer->SMTPAuth = true;
		$this->phpmailer->SMTPSecure = $this->config->item('MailSecureType');	
		$this->phpmailer->Host = $this->config->item('MailSMTPHost');
		$this->phpmailer->Port = $this->config->item('MailPort');
		$this->phpmailer->Username = $this->config->item('MailSMTPLogin');
		$this->phpmailer->Password = $this->config->item('MailSMTPPassword');
		$this->phpmailer->From = $this->config->item('MailFrom');
		$this->phpmailer->FromName = $this->config->item('MailFromName');
		$this->phpmailer->Subject = $Subject;
		$this->phpmailer->AltBody = $this->config->item('MailNoHTMLSupport');
		$this->phpmailer->MsgHTML($BodyHtml);
		$this->phpmailer->AddAddress($To);
		
		if (!$this->phpmailer->Send())
		{
			echo 'Mailer Error: ' . $this->phpmailer->ErrorInfo;
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