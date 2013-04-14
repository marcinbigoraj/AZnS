<?php

class Authentication extends CI_Controller
{

	public function __construct() 
	{
		parent::__construct();
	}
	
	public function login()
	{		
		$this->load->model('forms_model');
		$this->load->library('form_validation');
		
		$data['title'] = "Logowanie";
		$data['loginForm'] =  $this->forms_model->createLoginForm();
		$data['errors'] = '';
		
		if (isset($_POST['loginSend']))
		{
			$username = $_POST['loginUsername'];
			$password = $_POST['loginPassword'];
			if (!$this->ion_auth->login($username, $password, FALSE))
			{
				$data['errors'] = $this->ion_auth->errors();
			}
		}
		
		if ($this->ion_auth->logged_in())
		{	
			redirect('main/index');
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('authentication/login', $data);
		$this->load->view('templates/footer');	
	}
	
	public function logout()
	{
		$this->ion_auth->logout();
		redirect('authentication/login');
	}
	
	public function register()
	{
		$this->load->model('forms_model');
		$this->load->library('form_validation');
		
		$data['title'] = "Rejestracja";
		$data['registerForm'] = $this->forms_model->createRegisterForm();
		
		if (isset($_POST['registerSend']))
		{
			
			$this->form_validation->set_rules('registerUsername', 'Login', 'trim|required|min_length[6]|max_length[20]|is_unique[users.username]|xss_clean');
			$this->form_validation->set_rules('registerPassword', 'Hasło', 'required|min_length[8]|max_length[20]');
			$this->form_validation->set_rules('registerPassword2', 'Powtórne hasło', 'required|min_length[8]|max_length[20]|matches[registerPassword]');
			$this->form_validation->set_rules('registerEmail', 'Email', 'trim|required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('registerPhone', 'Telefon', 'trim|required|integer|exact_length[9]');
			
			if ($this->form_validation->run() != FALSE)
			{
				$username = $_POST['registerUsername'];
				$passwd = $_POST['registerPassword'];
				$email = $_POST['registerEmail'];
				$phone = array('phone' => $_POST['registerPhone']);
				
				$this->ion_auth->register($username, $passwd, $email, $phone);
				
				redirect('authentication/login');
			}
			
		} 

		$this->load->view('templates/header', $data);
		$this->load->view('authentication/register', $data);	
		$this->load->view('templates/footer');		
	}

}

?>
