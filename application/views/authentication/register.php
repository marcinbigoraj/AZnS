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
		$formString .= form_input('registerUsername', isset($_POST['registerUsername']) ? $_POST['registerUsername'] : '');
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
		$formString .= form_input('registerEmail', isset($_POST['registerEmail']) ? $_POST['registerEmail'] : '');
		$formString .= '</div>';
		
		$formString .= '<div id="registerPhone">';
		$formString .= "<span>Telefon</span>";
		$formString .= form_input('registerPhone', isset($_POST['registerPhone']) ? $_POST['registerPhone'] : '');
		$formString .= '</div>';
		
		$formString .= '<div id="registerSend">';
		$formString .= form_submit('registerSend', 'Zarejestruj się');
		$formString .= '</div>';
		
		$formString .= form_close();
		
		echo $formString;
	
	?>

<a href='<?php echo site_url('authentication/login'); ?>'>Wróć</a>
</div>