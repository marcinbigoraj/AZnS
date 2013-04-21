<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AllegroWebAPISoapClient extends SoapClient
{

	private $APIVersion;
	private $session;
	public $config;

	public function __construct()
	{
		$ci = get_instance(); 
		$this -> config = $ci -> config -> item('AllegroAPI');
				
		parent::__construct($this -> config['url']);
		
		try
		{
			$doQuerySysStatus_request = array(
			   'sysvar' => $this -> config['component'],
			   'countryId' => $this -> config['country'],
			   'webapiKey' => $this -> config['webapikey']
			);
			$this -> APIVersion = $this -> doQuerySysStatus($doQuerySysStatus_request);
			$this -> login();			
		}
		catch(SoapFault $error)
		{
			log_message('error', "SoapClient initialize error: " . $error -> faultcode . ', ' . $error -> faultstring);
		}
	}
	
	public function login() 
	{		
		// w przypadku aktywnej sesjji nie jest tworzona nowa
		if ($this -> session != null) 
		{
			$this -> session -> getAllegroFormatSessionInfo();
		} 
		else 
		{
			$doLoginEnc_request = array(
				'userLogin' => $this -> config['login'],
			   	'userHashPassword' => base64_encode(hash('sha256', $this -> config['passwd'], true)),
			   	'countryCode' => $this -> config['country'],
			   	'webapiKey' => $this -> config['webapikey'],
			   	'localVersion' => $this -> APIVersion -> verKey
			);	
			$this -> session = $this -> doLoginEnc($doLoginEnc_request);
		}
		
		return $this -> session;
	}
	
	public function getMainCategories() 
	{
		$doGetCatsData_request = array(
		   'countryId' => $this -> config['country'],
		   'localVersion' => 0,
		   'webapiKey' => $this -> config['webapikey']
		);
		return $this -> doGetCatsData($doGetCatsData_request);
	}
	
	public function getStates() 
	{
		$doGetStatesInfo_request = array(
			'countryCode' => $this -> config['country'],
   			'webapiKey' => $this -> config['webapikey']
		);
		return $this -> doGetStatesInfo($doGetStatesInfo_request);
	}
	
	public function searchAuction($keyword, $catId, $anyWord, $includeDesc, $buyNow, $city, $state, $minPrice, $maxPrice, $offset, $limit)
	{
		$searchOptions = 0; 
		
		if ($anyWord == 1)
		{
			$searchOptions += 1; // którekolwiek z wpisanych słów
		}
		
		if ($includeDesc == 1)
		{
			$searchOptions += 2; // szukaj również w opisach
		}
		
		if ($buyNow == 1) 
		{
			$searchOptions += 8; // tylko kup teraz
		}
		
		$sessionHandle = $this -> session -> sessionHandlePart;	
				
		$doSearch_request = array(
			'sessionHandle' => $sessionHandle,
			'searchQuery' => array(
				'searchString' => $keyword,
				'searchOptions' => $searchOptions,
				'searchCategory' => $catId,
				'searchOffset' => $offset,
				'searchCity' => $city,
				'searchState' => $state,
				'searchPriceFrom' => $minPrice,
				'searchPriceTo' => $maxPrice,
				'searchLimit' => $limit				
			)
		);	
		
		return $this -> doSearch($doSearch_request);		
	}

	public function getCategoriesVersion()
	{
		$doGetStatesInfo_request = array(
			'countryId' => $this -> config['country'],
   			'webapiKey' => $this -> config['webapikey']
		);
		return $this -> doQueryAllSysStatus($doGetStatesInfo_request);
	}
	
	public function getCountryId()
	{
		return $this -> config['country'];
	}
	
	public function getNotExistsAuctions($auctionsIds)
	{
		$sessionHandle = $this -> session -> sessionHandlePart;	
		
		$limit = 25;
		$offset = 0;
		
		$notExistsIdsArray = array();
		
		do
		{

			$slice = array_slice($auctionsIds, $offset, $limit);
			
			$doGetItemsInfo_request = array(
				'sessionHandle' => $sessionHandle,
	   			'itemsIdArray' => $slice,
	   			'getDesc' => 0,
	   			'getImageUrl' => 0,
	   			'getAttribs' => 0,
	   			'getPostageOptions' => 0,
	   			'getCompanyInfo' => 0,
	   			'getProductInfo' => 0
			);
			
			$result = $this -> doGetItemsInfo($doGetItemsInfo_request);	
			
			if (isset($result -> arrayItemsNotFound -> item))
			{
				
				if (!is_array($result -> arrayItemsNotFound -> item))
				{
					$result -> arrayItemsNotFound -> item = array($result -> arrayItemsNotFound -> item);
				}
				
				foreach ($result -> arrayItemsNotFound -> item as $item)
				{
					$notExistsIdsArray[] = $item;
				}
				
			}
			
			$offset += $limit;
		
		}
		while ($offset < count($auctionsIds));
		
		return $notExistsIdsArray;		
	}
	
}

?>