<?php

class Authentication extends CI_Controller
{

	public function __construct() 
	{
		parent::__construct();
	}
	
	public function login()
	{				
		$data['title'] = "Logowanie";
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
			redirect('allegro/lista');
		}
		
		$this->load->view('templates/header', $data);
		$this->load->view('authentication/login', $data);
		$this->load->view('templates/footer');	
	}
	
	public function edit()
	{
		if (!$this -> ion_auth -> logged_in()) {
			redirect('authentication/login');
		}
		
		$data['title'] = "Edycja danych";
		$data['errors'] = '';
		$user = $this->ion_auth->user()->row();
		$data['form'] = array(
			'id' => $user->id,
			'username' => $user->username,
			'passwd' => $user->password,
			'email' => $user->email,
			'phone' => $user->phone
		); 
		if (isset($_POST['editSend']))
		{

			if ($this->validateEditForm($user->email, $_POST['editEmail']))
			{
				
				$updateData = array(
					'username' => $_POST['editUsername'],
					'password' => $_POST['editPassword'],
					'email' => $_POST['editEmail'],
					'phone' => $_POST['editPhone']
				);

				if ($this->ion_auth->update($user->id, $updateData))
				{
					redirect('allegro/lista');
				}
				else 
				{
					$data['errors'] = $this->ion_auth->errors();
				}
				
			}
			
		} 
		
		$this->load->view('templates/header', $data);
		$this->load->view('authentication/edit', $data);	
		$this->load->view('templates/footer');		
		
	}
	
	public function logout()
	{
		$this->ion_auth->logout();
		redirect('authentication/login');
	}
	
	public function register()
	{		
		$data['title'] = "Rejestracja";
		$data['errors'] = '';
		
		if (isset($_POST['registerSend']))
		{
			
			if ($this->validateRegistrationForm())
			{
				$username = $_POST['registerUsername'];
				$passwd = $_POST['registerPassword'];
				$email = $_POST['registerEmail'];
				$phone = array('phone' => $_POST['registerPhone']);				
				
				if ($this->ion_auth->register($username, $passwd, $email, $phone))
				{
					redirect('allegro/lista');					
				}
				else 
				{
					$data['errors'] = $this->ion_auth->errors();
				}
			}
			
		} 

		$this->load->view('templates/header', $data);
		$this->load->view('authentication/register', $data);	
		$this->load->view('templates/footer');		
	}

	private function validateRegistrationForm()
	{
		
		$this->form_validation->set_rules('registerUsername', 'Login', 'trim|required|min_length[6]|max_length[20]|is_unique[users.username]|xss_clean');
		$this->form_validation->set_rules('registerPassword', 'Hasło', 'required|min_length[8]|max_length[20]');
		$this->form_validation->set_rules('registerPassword2', 'Powtórne hasło', 'required|min_length[8]|max_length[20]|matches[registerPassword]');
		$this->form_validation->set_rules('registerEmail', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('registerPhone', 'Telefon', 'trim|integer|exact_length[9]');
		
		if ($this->form_validation->run())
		{
			return true;
		}
		
		return false;
	}
	
	private function validateEditForm($currentEmail, $newEmail)
	{
			
		$this->form_validation->set_rules('editUsername', 'Login', 'trim|required|min_length[6]|max_length[20]|xss_clean');
		$this->form_validation->set_rules('editPassword', 'Hasło', 'required|min_length[8]|max_length[20]');
		$this->form_validation->set_rules('editPassword2', 'Powtórne hasło', 'required|min_length[8]|max_length[20]|matches[editPassword]');
		if ($currentEmail == $newEmail)
		{
			$this->form_validation->set_rules('editEmail', 'Email', 'trim|required|valid_email');
		}
		else 
		{
			$this->form_validation->set_rules('editEmail', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		}		
		$this->form_validation->set_rules('editPhone', 'Telefon', 'trim|integer|exact_length[9]');

		if ($this->form_validation->run())
		{
			return true;
		}
		
		return false;
	}

}

?>
