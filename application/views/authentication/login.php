<h1><?php echo $title; ?></h1>

<div id="errorsContainer">
<?php echo $errors; ?>
</div>

<div id="loginForm" class="form">
	
	<?php echo $loginForm; ?>
	
	<div>
		<p><a href='<?php echo site_url('authentication/register'); ?>'>Zarejestruj konto</a></p>
	</div>

</div>