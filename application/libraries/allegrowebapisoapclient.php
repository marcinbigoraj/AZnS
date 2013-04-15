<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AllegroWebAPISoapClient extends SoapClient
{

	private $APIVersion;
	private $session;

	public $config = array(
		'url' => 'https://webapi.allegro.pl/service.php?wsdl',
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
			$doQuerySysStatus_request = array(
			   'sysvar' => $this->config['component'],
			   'countryId' => $this->config['country'],
			   'webapiKey' => $this->config['webapikey']
			);
			$this->APIVersion = $this->doQuerySysStatus($doQuerySysStatus_request);
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
			$doLoginEnc_request = array(
				'userLogin' => $this->config['login'],
			   	'userHashPassword' => base64_encode(hash('sha256', $this->config['passwd'], true)),
			   	'countryCode' => $this->config['country'],
			   	'webapiKey' => $this->config['webapikey'],
			   	'localVersion' => $this->APIVersion->verKey
			);	
			$this->session = $this->doLoginEnc($doLoginEnc_request);
		}
		
		return $this->session;
	}
	
	public function getMainCategories() 
	{
		$doGetCatsData_request = array(
		   'countryId' => $this->config['country'],
		   'localVersion' => 0,
		   'webapiKey' => $this->config['webapikey']
		);
		return $this->doGetCatsData($doGetCatsData_request);
	}
	
	public function getStates() 
	{
		$doGetStatesInfo_request = array(
			'countryCode' => $this->config['country'],
   			'webapiKey' => $this->config['webapikey']
		);
		return $this->doGetStatesInfo($doGetStatesInfo_request);
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
		
		$sessionHandle = $this->session->sessionHandlePart;	
			
		
			
		$doSearch_request = array(
			'sessionHandle' => $sessionHandle,
			'searchQuery' => array(
				'searchString' => $text,
				'searchOptions' => $searchOptions,
				'searchCategory' => $category,
				'searchOffset' => $offset,
				'searchCity' => $cityOptions,
				'searchState' => $stateOptions,
				'searchPriceFrom' => $priceFrom,
				'searchPriceTo' => $priceTo,
				'searchLimit' => $limit				
			)
		);	
		
		return $this->doSearch($doSearch_request);		
	}

}

?>