<h1><?php echo $title; ?></h1>

<?php echo form_open('allegro/zapiszFiltr'); ?>
	<table>
		<tbody>
			<tr>
				<td>
					Słowa kluczowe
				</td>
				<td>
					<?php echo form_input('keywords', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Kategoria
				</td>
				<td>
					<?php echo form_input('id_cat', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Kup teraz
				</td>
				<td>
					<?php echo form_checkbox('buyNow', 'true'); ?>
				</td>
			</tr>
			<tr>
				<td>
					Miasto
				</td>
				<td>
					<?php echo form_input('city', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Województwo
				</td>
				<td>
					<?php echo form_input('voivodeship', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Minimalna cena
				</td>
				<td>
					<?php echo form_input('minPrice', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Maksymalna cena
				</td>
				<td>
					<?php echo form_input('maxPrice', ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo form_submit('dodajFiltr', 'Dodaj Filtr'); ?>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close(); ?>
<a href='<?php echo site_url('allegro/lista'); ?>'>Wróć</a>
