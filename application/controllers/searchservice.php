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
			WHERE s.active = 1 ORDER BY s.user_id, id DESC
		");

		$message = '';
		$savedUserId = -1;
		$savedUsername = '';
		$savedEmail = '';
		$savedPhone = '';
		
		$newAuctionsForUserCount = 0;

		foreach ($filterQuery->result() as $row)
		{
			
			$userId = $row->user_id;
			$username = $row->username;
			$email = $row->email;
			$phone = $row->phone;
			
			$filterId = $row->id;
			
			$keyword = $row->keywords;
			$catId = $row->id_cat;
			$buyNow = $row->buyNow;
			$city = $row->city;
			$state = $row->voivodeship;
			$minPrice = $row->minPrice;
			$maxPrice = $row->maxPrice;

			$offset = 0;
			$limit = 100;		
			
			if ($userId != $savedUserId && $savedUserId != -1) 
			{
				if ($newAuctionsForUserCount > 0)
				{
					echo $savedEmail;		
					//$this->mailSender($message, $savedEmail);	
				}
				echo $message;
				$newAuctionsForUserCount = 0;
				$message = '';
			}
			
			$message .= '<h1 style="font-family:Calibri; font-size:20px; font-weight:bold; color:green; padding:10px 0 0 0; margin:0;">'.$keyword.'</h1>';
			$message .= '<p style="font-family:Calibri; font-size:16px; padding:3px 0 10px 0; margin:0;">';
			
			if ($catId == 0)
			{
				$message .= 'Wszystkie kategorie';
			}
				
			if ($buyNow != 0)
			{
				$message .= ' Kup teraz ';
			}
				
			$message .= '</p>';
			
			$auctionForUserIdQuery = $this->db->query("SELECT * FROM found_auctions WHERE id_user=$userId");
			
			$message .= '<table style="font-family:Calibri; font-size:14px;">';
			
			try 
			{
				
				do 
				{

					$allegroResult = $this->allegrowebapisoapclient->searchAuction($keyword, $buyNow, $catId, $offset, $city, 
						$state, $minPrice, $maxPrice, $limit);
					
					if ($allegroResult->searchCount != 0) 
					{							
						
						foreach ($allegroResult->searchArray->item as $auction) 
						{

							if (!$this->isAuctionIdInArray($auctionForUserIdQuery->result(), $auction->sItId))
							{
								
								$newAuctionsForUserCount++;								
											
								$message .= '<tr>';
	
								if ($auction->sItThumb == 1) 
								{
									$message .= '<td rowspan="2"><img src="' . $auction->sItThumbUrl . '" alt="' . $auction->sItName . '" style="width:100px; height:auto;" /></td>';
								} 
								else 
								{
									$message .= '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" style="width:100px; height:auto;" /></td>';
								}
				
								$message .= '<td style="width:500px;"><a href="http://testwebapi.pl/show_item.php?item='.$auction->sItId.'" style="color:blue; font-weight:bold; text-decoration:underline;">' . $auction->sItId . ' - ' . $auction->sItName . '</a></td>';
													
								$message .= '<td style="text-align:right; width:150px;">Data zakończenia</td>';
								
								$message .= '</tr><tr>';
								
								$message .= '<td style="width:500px;">';
								
								$message .= 'Aktualna cena: ' . $auction->sItPrice;
				
								if ($auction->sItIsBuyNow != 0) 
								{
									$message .= ', Cena kup teraz: ' . $auction->sItIsBuyNow;
								}
				
								$message .= '</td>';
								
								$message .= '<td style="text-align:right; width:150px;">' . date("Y-m-d", $auction->sItEndingTime) .'<br />' . date("H:i:s", $auction->sItEndingTime) . '</td>';
								
								$message .= '</tr>';		
									
								//$this->db->query("INSERT INTO found_auctions VALUES($userId,$auction->sItId)");
															
							}

						}

						$offset += $limit;
						
					}

				} 
				while ($offset < $allegroResult->searchCount);
				
				$message .= "</table>";
					
				$savedUserId = $userId;	
				$savedUsername = $username;	
				$savedEmail = $email;
				$savedPhone = $phone;
				
			} 
			catch(Exception $exception ) 
			{
				log_message('error', $exception->getMessage());
			}
			
		}

		if ($newAuctionsForUserCount > 0)
		{		
			//$this->mailSender($message, $savedEmail);	
			echo $savedEmail;
		}
		echo $message;

	}

	private function mailSender($BodyHtml, $To)
	{
		$Subject = "Allegro Search Service z dnia " . date("H:i:s d-m-Y"); 
		
		$this->phpmailer = new PHPMailer();
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
			log_message('error', "Mail send error: " . $this->phpmailer->ErrorInfo);
			return false;
		}
		
		return true;		
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