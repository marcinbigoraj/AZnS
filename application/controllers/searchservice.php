<?php

class SearchService extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
	
	public function searchAllNews() {
		
		$this -> load -> library('allegrowebapisoapclient');	
		$this -> load -> library('phpmailer/phpmailer');	

		$message = '';
		$savedUserId = -1;
		$savedUsername = '';
		$savedEmail = '';
		$savedPhone = '';
		
		$newAuctionsForUserCount = 0;
		$newAuctionsForFilterCount = 0;

		foreach ($this -> allegro_model -> getFiltersToSearchService() as $row)
		{
			
			$userId = $row -> user_id;
			$username = $row -> username;
			$email = $row -> email;
			$phone = $row -> phone;
			
			$filterId = $row -> id;
			
			$keyword = $row -> keywords;
			$catId = $row -> id_cat;
			$anyWord = $row -> anyWord;
			$includeDesc = $row -> includeDescription;
			$buyNow = $row -> buyNow;
			$city = $row -> city;
			$state = $row -> voivodeship;
			$minPrice = $row -> minPrice;
			$maxPrice = $row -> maxPrice;
			
			$stateName = $row -> st_name;
			$catName = $row -> c_name;

			$offset = 0;
			$limit = 100;		
			
			$newAuctionsForFilterCount = 0;
			
			if ($userId != $savedUserId && $savedUserId != -1) 
			{
				if ($newAuctionsForUserCount > 0)
				{		
					$this -> mailSender($message, $savedEmail);	
				}
				$newAuctionsForUserCount = 0;
				$message = '';
			}
			
			$auctionForUserIdQuery = $this -> allegro_model -> getAuctionsForUserQuery($userId);
			
			try 
			{
				
				$allegroResult = $this -> allegrowebapisoapclient -> searchAuction($keyword, $catId, $anyWord, $includeDesc, 
					$buyNow, $city, $state, $minPrice, $maxPrice, $offset, $limit);
				
				if ($allegroResult -> searchCount != 0) 
				{							
					
					foreach ($allegroResult -> searchArray -> item as $auction) 
					{

						if (!$this -> isAuctionIdInArray($auctionForUserIdQuery -> result(), $auction -> sItId))
						{
							
							$newAuctionsForUserCount++;		
							$newAuctionsForFilterCount++;	
							
							if ($newAuctionsForFilterCount == 1)
							{
								$message .= '<h1 style="font-family:Calibri; font-size:20px; font-weight:bold; color:green; padding:10px 0 0 0; margin:0;">'.$keyword.'</h1>';
								$message .= '<p style="font-family:Calibri; font-size:16px; padding:3px 0 10px 0; margin:0;">';
								
								$message .= 'Kategoria: ' . $catName;
								
								if($anyWord == 1) 
								{
									$message .= " | Którekolwiek słowo";
								}
								
								if($includeDesc == 1)
								{
									$message .= " | Szukaj w opisach";
								}

								if ($buyNow == 1)
								{
									$message .= ' | Kup teraz';
								}
								
								if ($city != '')
								{
									$message .= ' | Miasto: ' . $city;
								}
								
								$message .= " | Region: " . $stateName;
								
								if ($minPrice == 0)
								{
									$message .= " | Brak ceny minimalnej";
								}
								else 
								{
									$message .= " | Cena minimalna: " . $minPrice;
								}
								
								if ($maxPrice == 0)
								{
									$message .= " | Brak ceny maksymalnej";
								}
								else 
								{
									$message .= " | Cena maksymalna: " . $maxPrice;
								}								
									
								$message .= '</p>';		
								
								$message .= '<table style="font-family:Calibri; font-size:14px;">';
							}					
										
							$message .= '<tr>';

							if ($auction -> sItThumb == 1) 
							{
								$message .= '<td rowspan="2"><img src="' . $auction -> sItThumbUrl . '" alt="' . $auction -> sItName . '" style="width:100px; height:auto;" /></td>';
							} 
							else 
							{
								$message .= '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" style="width:100px; height:auto;" /></td>';
							}
			
							$message .= '<td style="width:500px;"><a href="http://testwebapi.pl/show_item.php?item='.$auction -> sItId.'" style="color:blue; font-weight:bold; text-decoration:underline;">' . $auction -> sItId . ' - ' . $auction -> sItName . '</a></td>';
												
							$message .= '<td style="text-align:right; width:150px;">Data zakończenia</td>';
							
							$message .= '</tr><tr>';
							
							$message .= '<td style="width:500px;">';
							
							$message .= 'Aktualna cena: ' . $auction -> sItPrice;
			
							if ($auction -> sItIsBuyNow != 0) 
							{
								$message .= ', Cena kup teraz: ' . $auction -> sItIsBuyNow;
							}
			
							$message .= '</td>';
							
							$message .= '<td style="text-align:right; width:150px;">' . date("Y-m-d", $auction -> sItEndingTime) .'<br />' . date("H:i:s", $auction -> sItEndingTime) . '</td>';
							
							$message .= '</tr>';		
								
							$this->allegro_model->addSendedAuction($userId, $auction -> sItId);
														
						}

					}
					
				}
				
				if ($newAuctionsForFilterCount != 0)
				{
					$message .= "</table>";	
				}	
					
				$savedUserId = $userId;	
				$savedUsername = $username;	
				$savedEmail = $email;
				$savedPhone = $phone;
				
			} 
			catch(Exception $exception ) 
			{
				log_message('error', $exception -> getMessage());
			}
			
		}

		if ($newAuctionsForUserCount > 0)
		{		
			$this -> mailSender($message, $savedEmail);	
		}

	}

	private function mailSender($BodyHtml, $To)
	{
		$Subject = "Allegro Search Service z dnia " . date("H:i:s d-m-Y"); 
		
		$this -> phpmailer = new PHPMailer();
		$this -> phpmailer -> IsSMTP();
		$this -> phpmailer -> SMTPAuth = true;
		$this -> phpmailer -> SMTPSecure = $this -> config -> item('MailSecureType');	
		$this -> phpmailer -> Host = $this -> config -> item('MailSMTPHost');
		$this -> phpmailer -> Port = $this -> config -> item('MailPort');
		$this -> phpmailer -> Username = $this -> config -> item('MailSMTPLogin');
		$this -> phpmailer -> Password = $this -> config -> item('MailSMTPPassword');
		$this -> phpmailer -> From = $this -> config -> item('MailFrom');
		$this -> phpmailer -> FromName = $this -> config -> item('MailFromName');
		$this -> phpmailer -> Subject = $Subject;
		$this -> phpmailer -> AltBody = $this -> config -> item('MailNoHTMLSupport');
		$this -> phpmailer -> MsgHTML($BodyHtml);
		$this -> phpmailer -> AddAddress($To);
		
		if (!$this -> phpmailer -> Send())
		{			    
			log_message('error', "Mail send error: " . $this -> phpmailer -> ErrorInfo);
			return false;
		}
		
		return true;		
	}

	private function isAuctionIdInArray($result, $id) 
	{
		
		foreach ($result as $item) 
		{
			if ($item -> id_auc == $id) 
			{
				return true;
			}
		}
		return false;
		
	}

}

?>