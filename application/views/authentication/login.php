<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo $errors; ?>
</div>

<div id="loginForm" class="form">
	
	<?php
	
		$formString = form_open('authentication/login');	
		
		$formString .= '<div id="loginUsername">';
		$formString .= "<span>Login</span>";
		$formString .= form_input('loginUsername', '');
		$formString .= '</div>';	
		
		$formString .= '<div id="loginPassword">';
		$formString .= "<span>Hasło</span>";
		$formString .= form_password('loginPassword', '');
		$formString .= '</div>';
		
		$formString .= '<div id="loginSend">';
		$formString .= form_submit('loginSend', 'Zaloguj');
		$formString .= '</div>';

		$formString .= form_close();	

		echo $formString;
	
	?>
	
	<div>
		<p><a href='<?php echo site_url('authentication/register'); ?>'>Zarejestruj konto</a></p>
	</div>

</div>