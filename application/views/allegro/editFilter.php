<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo validation_errors(); ?>
</div>

<div id="editFilterForm" class="form">

<?php echo form_open("allegro/editFilter/".$savedData['id']); ?>
	
	<table>
		<tbody>
			<tr>
				<td>
					Słowa kluczowe
				</td>
				<td>
					<?php echo form_input('keywords', $savedData['keywords']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Kategoria
				</td>
				<td>
					<select name="id_cat">
						<?php 
							foreach($categories as $category)
							{
								$nazwa = "";
								for($i = 0; $i < $category -> depth; $i++)
								{
									$nazwa .= "---";
								}
								$nazwa .= $category -> name;
								$category -> id_cat == $savedData['id_cat'] ? $t = " selected='selected' ": $t ='';
								echo "<option value='".$category -> id_cat."'".$t.">$nazwa</option>";
							}
						?>
					</select>
					
				</td>
			</tr>
			<tr>
				<td>
					Którekolwiek z wpisanych słów
				</td>
				<td>
					<?php echo form_checkbox('anyWord', 'true', $savedData['anyWord'] == 1 ? TRUE : FALSE ); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj również w opisach
				</td>
				<td>
					<?php echo form_checkbox('includeDescription', 'true', $savedData['includeDescription'] == 1 ? TRUE : FALSE); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj tylko 'Kup teraz'
				</td>
				<td>
					<?php echo form_checkbox('buyNow', 'true', $savedData['buyNow'] == 1 ? TRUE : FALSE); ?>
				</td>
			</tr>
			<tr>
				<td>
					Miasto
				</td>
				<td>
					<?php echo form_input('city', $savedData['city']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Województwo
				</td>
				<td>
					<?php echo form_dropdown('voivodeship', $states, $savedData['voivodeship']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Minimalna cena
				</td>
				<td>
					<?php echo form_input('minPrice', $savedData['minPrice']); ?>
				</td>
			</tr>
			<tr>
				<td>
					Maksymalna cena
				</td>
				<td>
					<?php echo form_input('maxPrice', $savedData['maxPrice']); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo form_submit('saveChanges', 'Zapisz zmiany'); ?>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close(); ?>

<p><a href='<?php echo site_url('allegro/filtersList'); ?>'>Wróć</a></p>
</div>