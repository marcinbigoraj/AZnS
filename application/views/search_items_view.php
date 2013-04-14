<h1><?php echo $title; ?></h1>

<table>
	
	<?php foreach ($searchArray as $item) { ?>
	
		<tr>
			
			<?php				
				if ($item->sItThumb == 1) 
				{
					echo '<td rowspan="2"><img src="'.$item->sItThumbUrl.'" alt="'.$item->sItName.'" /></td>';
				}
				else 
				{
					echo '<td rowspan="2"><img src="http://static.allegrostatic.pl/site_images/1/0/layout/showItemNoPhoto.png" alt="Brak zdjęcia" /></td>';
				}
				
				echo '<td>'.$item->sItId.' - '.$item->sItName.'</td>';			
				echo '<td>';
					echo 'Aktualna cena: '.$item->sItPrice.'<br />';
					
					if ($item->sItIsBuyNow != 0)
					{
						echo 'Cena kup teraz: '.$item->sItIsBuyNow;
					}
					
				echo '</td>';
			?>
			
		</tr>
		
		<tr>
			
			<?php				
				echo '<td>Pozostało: '.$item->sItTimeLeft.' | Kategoria: '.$item->sItCategoryId.'</td>';
				echo '<td>Locaklizacja: '.$item->sItCity.' | '.$item->sItCountry.'</td>';
			?>
			
		</tr>
	
	<?php } ?>
	
</table>