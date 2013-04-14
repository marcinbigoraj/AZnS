<?php

class Forms_model extends CI_Model	 
{

	public function __construct() 
	{
		parent::__construct();
	}

	public function createRegisterForm() 
	{		
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
		
		return $formString;
	}
	
	public function createLoginForm() 
	{
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

		return $formString;
	}
	
}

?>