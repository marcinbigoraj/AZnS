<h1><?php echo $title; ?></h1>

<table>
	<thead>
		<tr>
			<th>Słowa kluczowe</th><th>Kategoria</th><th>Którekolwiek słowo</th><th>Szukaj w opisach</th><th>Kup teraz</th><th>Miasto</th><th>Województwo</th><th>Minimalna cena</th><th>Maksymalna cena</th><th></th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($list as $row) {
			echo "<tr>";
				$id = $row->id;
				foreach($row as $key => $value)
				{
					if($key!='id')
					{
						echo "<td>".$value."</td>";
					}
				}
				echo "<td><a href='".site_url("allegro/edytuj/$id")."'>Edytuj</a></td>";
				echo "<td><a href='".site_url("allegro/usun/$id")."'>Usuń</a></td>";
			
			echo "</tr>";
		}
		?>
	</tbody>
</table>
<a href='<?php echo site_url('allegro/dodajFiltr'); ?>'>Dodaj filtr</a>
