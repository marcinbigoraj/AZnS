<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo validation_errors(); ?>
<?php echo $errors; ?>
</div>

<div id="registerForm" class="form">
<?php

	$formString = form_open('authentication/register');		
	
	$formString .= '<div id="registerUsername">';
	$formString .= "<span>Login</span>";
	$formString .= form_input('registerUsername', $form['username']);
	$formString .= '</div>';	
	
	$formString .= '<div id="registerPassword">';
	$formString .= "<span>Hasło</span>";
	$formString .= form_password('registerPassword', '');
	$formString .= '</div>';
	
	$formString .= '<div id="registerPassword2">';
	$formString .= "<span>Hasło powtórnie</span>";
	$formString .= form_password('registerPassword2', '');
	$formString .= '</div>';
	
	$formString .= '<div id="registerEmail">';
	$formString .= "<span>E-mail</span>";
	$formString .= form_input('registerEmail', $form['email']);
	$formString .= '</div>';
	
	$formString .= '<div id="registerPhone">';
	$formString .= "<span>Telefon</span>";
	$formString .= form_input('registerPhone', $form['phone']);
	$formString .= '</div>';
	
	$formString .= '<div id="registerSend">';
	$formString .= form_submit('registerSend', 'Zmień dane');
	$formString .= '</div>';
	
	$formString .= form_close();
	
	echo $formString;

?>
</div>