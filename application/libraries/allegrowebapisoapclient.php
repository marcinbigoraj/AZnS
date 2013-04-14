<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AllegroWebAPISoapClient extends SoapClient
{

	private $APIVersion;
	private $session;

	public $config = array(
		'url' => 'https://webapi.allegro.pl/uploader.php?wsdl',
		'component' => 1, // component AllegroWebAPI
		'country' => 228, // kod kraju, 1 - Polska, 228 - serwis testowy
		'webapikey' => '652d6c74', // klucz API
		'login' => 'testapipl',
		'passwd' => 'azns2013pl'
	);

	public function __construct()
	{		
		parent::__construct($this->config['url']);
		
		try
		{		
			$this->APIVersion = $this->doQuerySysStatus($this->config['component'], $this->config['country'], 
					$this->config['webapikey']);
			$this->login();			
		}
		catch(SoapFault $error)
		{
			echo "Error: " . $error->faultcode . ', ' . $error->faultstring;
		}
	}
	
	public function login() 
	{		
		// w przypadku aktywnej sesjji nie jest tworzona nowa
		if ($this->session != null) 
		{
			$this->session->getAllegroFormatSessionInfo();
		} 
		else 
		{
			$this->session = $this->doLoginEnc($this->config['login'], 
					base64_encode(hash('sha256', $this->config['passwd'], true)), 
					$this->config['country'], $this->config['webapikey'], $this->APIVersion['ver-key']);
		}
		
		return $this->session;
	}
	
	public function getMainCategories() 
	{
		return $this->doGetCatsData($this->config['country'], 0, $this->config['webapikey']);
	}

}

?>