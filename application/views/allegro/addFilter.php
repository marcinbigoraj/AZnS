<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo validation_errors(); ?>
</div>

<div id="addFilterForm" class="form">
	
<?php echo form_open('allegro/addFilter'); ?>
	<table>
		<tbody>
			<tr>
				<td>
					Słowa kluczowe
				</td>
				<td>
					<?php echo form_input('keywords', isset($_POST['keywords']) ? $_POST['keywords'] : '' ); ?>
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
								
								if (isset($_POST['id_cat']) && ($_POST['id_cat'] == $category -> id_cat))
								{									
									echo '<option value="'.$category -> id_cat.'" selected="selected">'.$nazwa.'</option>';
								}
								else 
								{
									echo '<option value="'.$category -> id_cat.'">'.$nazwa.'</option>';
								}
								
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
					<?php echo form_checkbox('anyWord', 'true', isset($_POST['anyWord']) ? $_POST['anyWord'] : FALSE); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj również w opisach
				</td>
				<td>
					<?php echo form_checkbox('includeDescription', 'true', isset($_POST['includeDescription']) ? $_POST['includeDescription'] : FALSE); ?>
				</td>
			</tr>
			<tr>
				<td>
					Szukaj tylko 'Kup teraz'
				</td>
				<td>
					<?php echo form_checkbox('buyNow', 'true', isset($_POST['buyNow']) ? $_POST['buyNow'] : FALSE); ?>
				</td>
			</tr>
			<tr>
				<td>
					Miasto
				</td>
				<td>
					<?php echo form_input('city', isset($_POST['city']) ? $_POST['city'] : ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Województwo
				</td>
				<td>
					<?php echo form_dropdown('voivodeship', $states, isset($_POST['voivodeship']) ? $_POST['voivodeship'] : ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Minimalna cena
				</td>
				<td>
					<?php echo form_input('minPrice', isset($_POST['minPrice']) ? $_POST['minPrice'] : ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					Maksymalna cena
				</td>
				<td>
					<?php echo form_input('maxPrice', isset($_POST['maxPrice']) ? $_POST['maxPrice'] : ''); ?>
				</td>
			</tr>
			<tr>
				<td>
					<?php echo form_submit('addFilter', 'Dodaj Filtr'); ?>
				</td>
				<td></td>
			</tr>
		</tbody>
	</table>
<?php echo form_close(); ?>

<p><a href='<?php echo site_url('allegro/filtersList'); ?>'>Wróć</a></p>
</div>
