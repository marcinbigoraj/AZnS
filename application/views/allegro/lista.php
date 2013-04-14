<h1><?php echo $title; ?></h1>

<table>
	<thead>
		<tr>
			<th>Nazwa własna filtru</th><th>Jakieś drugie pole</th>
		</tr>
	</thead>
	<tbody>
		<?php
		foreach ($list as $value) {
			echo "<tr>";
			
				echo "<td>".$value['nazwaFiltru']."</td><td>".$value['test']."</td>";
			
			echo "</tr>";
		}
		?>
	</tbody>
</table>