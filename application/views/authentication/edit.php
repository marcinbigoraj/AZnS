<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo validation_errors(); ?>
<?php echo $errors; ?>
</div>

<div id="editForm" class="form">
<?php

	$formString = form_open('authentication/edit');		
	
	$formString .= '<div id="editUsername">';
	$formString .= "<span>Login</span>";
	$formString .= form_input('editUsername', isset($_POST['editUsername']) ? $_POST['editUsername'] : $form['username']);
	$formString .= '</div>';	
	
	$formString .= '<div id="editPassword">';
	$formString .= "<span>Hasło</span>";
	$formString .= form_password('editPassword', '');
	$formString .= '</div>';
	
	$formString .= '<div id="editPassword2">';
	$formString .= "<span>Hasło powtórnie</span>";
	$formString .= form_password('editPassword2', '');
	$formString .= '</div>';
	
	$formString .= '<div id="editEmail">';
	$formString .= "<span>E-mail</span>";
	$formString .= form_input('editEmail', isset($_POST['editEmail']) ? $_POST['editEmail'] : $form['email']);
	$formString .= '</div>';
	
	$formString .= '<div id="editPhone">';
	$formString .= "<span>Telefon</span>";
	$formString .= form_input('editPhone', isset($_POST['editPhone']) ? $_POST['editPhone'] : $form['phone']);
	$formString .= '</div>';
	
	$formString .= '<div id="editSend">';
	$formString .= form_submit('editSend', 'Zmień dane');
	$formString .= '</div>';
	
	$formString .= form_close();
	
	echo $formString;

?>

<p></p><a href='<?php echo site_url('allegro/filtersList'); ?>'>Wróć</a></p>
</div>