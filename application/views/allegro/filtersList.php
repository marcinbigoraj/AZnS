<h1><?php echo $title; ?></h1>

<div id="main_table">
	
<table>
	<thead>
		<tr>
			<th>Słowa kluczowe</th><th>Kategoria</th><th>Którekolwiek słowo</th><th>Szukaj w opisach</th><th>Kup teraz</th><th>Miasto</th><th>Województwo</th><th>Minimalna cena</th><th>Maksymalna cena</th><th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($list as $row) {
			
			if ($row -> blocked == 1) 
			{
				echo '<tr style="color:red;">';
			}
			else 
			{
				echo "<tr>";
			}
				$id = $row -> id;
				foreach($row as $key => $value)
				{
					if($key!='id' && $key != 'blocked')
					{
						if ($key == 'anyWord' || $key == 'includeDescription' || $key == 'buyNow') 
						{
							$tempValue = $value == 1 ? 'tak' : 'nie';
							echo "<td>".$tempValue."</td>";
						}
						else if ($key == 'city' && $value == '')
						{
							echo "<td>Dowolne</td>";
						}
						else if ($key == 'minPrice' && $value == 0)
						{
							echo "<td>Brak ceny minimalnej</td>";
						}
						else if ($key == 'maxPrice' && $value == 0)
						{
							echo "<td>Brak ceny maksymalnej</td>";
						}
						else
						{
							echo "<td>".$value."</td>";
						}
						
					}
				}
				echo "<td><a href='".site_url("allegro/editFilter/$id")."'>Edytuj</a></td>";
				echo "<td><a href='".site_url("allegro/deleteFilter/$id")."'>Usuń</a></td>";
			
			echo "</tr>";
		}
		?>
	</tbody>
</table>

<p><a href='<?php echo site_url('allegro/addFilter'); ?>'>Dodaj filtr</a></p>
</div>