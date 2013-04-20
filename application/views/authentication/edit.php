<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo validation_errors(); ?>
<?php echo $errors; ?>
</div>

<div id="editForm" class="form">
<?php

	$formString = form_open('authentication/edit');		
	
	$username = $form['username'];
	if (isset($_POST['editSend']))
	{
		$username = $_POST['editUsername'];
	}
	$formString .= '<div id="editUsername">';
	$formString .= "<span>Login</span>";
	$formString .= form_input('editUsername', $username);
	$formString .= '</div>';	
	
	$formString .= '<div id="editPassword">';
	$formString .= "<span>Hasło</span>";
	$formString .= form_password('editPassword', '');
	$formString .= '</div>';
	
	$formString .= '<div id="editPassword2">';
	$formString .= "<span>Hasło powtórnie</span>";
	$formString .= form_password('editPassword2', '');
	$formString .= '</div>';
	
	$email = $form['email'];
	if (isset($_POST['editSend']))
	{
		$email = $_POST['editEmail'];
	}
	$formString .= '<div id="editEmail">';
	$formString .= "<span>E-mail</span>";
	$formString .= form_input('editEmail', $email);
	$formString .= '</div>';
	
	$phone = $form['phone'];
	if (isset($_POST['editPhone']))
	{
		$phone = $_POST['editSend'];
	}
	$formString .= '<div id="editPhone">';
	$formString .= "<span>Telefon</span>";
	$formString .= form_input('editPhone', $phone);
	$formString .= '</div>';
	
	$formString .= '<div id="editSend">';
	$formString .= form_submit('editSend', 'Zmień dane');
	$formString .= '</div>';
	
	$formString .= form_close();
	
	echo $formString;

?>

<a href='<?php echo site_url('allegro/lista'); ?>'>Wróć</a>
</div>