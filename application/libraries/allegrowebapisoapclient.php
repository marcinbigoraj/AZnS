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
	
	public function searchAuction($text, $buyNow, $category, $offset, $city, $state, $priceFrom, $priceTo, $limit)
	{
		$searchOptions = 1; // którekolwiek z wpisanych słów
		$searchOptions += 2; // szukanie w opisach
		
		if ($buyNow == 1) 
		{
			$searchOptions += 8; // tylko kup teraz
		}
		
		$cityOptions = '';
		if ($city != NULL)
		{	
			$cityOptions = $city;
		}
		
		$stateOptions = 0;
		if ($state != NULL) 
		{
			$stateOptions = $state;
		}
		
		$searchOptions = array(
			'search-string' => $text,
			'search-options' => $searchOptions,
			'search-order' => 1,
			'search-order-type' => 0,
			'search-country' => 0,			
			'search-category' => $category,
			'search-offset' => $offset,
			'search-city' => $cityOptions,
			'search-state' => $stateOptions,
			'search-price-from' => $priceFrom,
			'search-price-to' => $priceTo,
			'search-limit' => $limit,
			'search-order-fulfillment-time' => 999,
			'search-user' => 0
		);
		
		$searchOptions = array(
			'search-string' => 'test',
			'search-options' => 1,
			'search-order' => 1,
			'search-order-type' => 0,
			'search-country' => 0,			
			'search-category' => 0,
			'search-offset' => 0,
			'search-city' => '',
			'search-state' => '',
			'search-price-from' => 0,
			'search-price-to' => 0,
			'search-limit' => 100,
			'search-order-fulfillment-time' => 999,
			'search-user' => 0
		);
		
		$sessionHandle = $this->session['session-handle-part'];
		
		return $this->doSearch($sessionHandle, $searchOptions);		
	}

}

?>