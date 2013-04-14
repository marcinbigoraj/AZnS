<h1><?php echo $title; ?></h1>

<form>
	<table>
		<tbody>
			<tr>
				<td>
					Nazwa filtru
				</td>
				<td>
					<input type="text" name="nazwaFiltru" />
				</td>
			</tr>
			<tr>
				<td>
					Inne rzeczy
				</td>
				<td>
					<input type="text" name="a" />
				</td>
			</tr>
		</tbody>
	</table>
</form>
<a href='<?php echo site_url('allegro/lista'); ?>'>Wróć</a>
