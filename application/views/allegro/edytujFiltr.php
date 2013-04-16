<h1><?php echo $title; ?></h1>

<?php echo form_open('allegro/zapiszWyedytowanyFiltr'); ?>
	<table>
		<tbody>
			<tr>
				<td>
					Słowa kluczowe
				</td>
				<td>
					<?php echo form_input('keywords', $zapisaneDane['keywords']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Kategoria
				</td>
				<td>
					<select name="id_cat">
						<?php 
							foreach($kategorie as $kategoria )
							{
								$nazwa = "";
								for($i=0; $i<$kategoria->depth; $i++)
								{
									$nazwa.="---";
								}
								$nazwa.=$kategoria->name;
								$kategoria->id_cat == $zapisaneDane['id_cat'] ? $t=" selected='selected' ":$t='';
								echo "<option value='".$kategoria->id_cat."'".$t.">$nazwa</option>";
							}
							?>
					</select>
					
				</td>
			</tr>
			<tr>
				<td>
					Którekolwiek z szukanych słów
				</td>
				<td>
					<?php echo form_checkbox('anyWord', ($zapisaneDane['anyWord']==1 ? true: false), ($zapisaneDane['anyWord']==1 ? true: false)); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj również w opisach
				</td>
				<td>
					<?php echo form_checkbox('includeDescription', $zapisaneDane['includeDescription']==1 ? true:false, $zapisaneDane['includeDescription']==1 ? true:false); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj tylko 'Kup teraz'
				</td>
				<td>
					<?php echo form_checkbox('buyNow', $zapisaneDane['buyNow']==1 ? true:false, $zapisaneDane['buyNow']==1 ? true:false); ?>
				</td>
			</tr>
			<tr>
				<td>
					Miasto
				</td>
				<td>
					<?php echo form_input('city', $zapisaneDane['city']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Województwo
				</td>
				<td>
					<?php echo form_dropdown('voivodeship', $wojewodztwa, $zapisaneDane['voivodeship']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Minimalna cena
				</td>
				<td>
					<?php echo form_input('minPrice', $zapisaneDane['minPrice']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Maksymalna cena
				</td>
				<td>
					<?php echo form_input('maxPrice', $zapisaneDane['maxPrice']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo form_submit('zapiszZmiany', 'Zapisz zmiany'); ?>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close(); ?>
<a href='<?php echo site_url('allegro/lista'); ?>'>Wróć</a>
