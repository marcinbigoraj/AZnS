<?php

class BasicDataService extends CI_Controller
{
	
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function getListOfCategories()
	{
		$elements = $this -> getCategoriesArray();
		$this -> insertCategoriesToDB($elements);
	}
	
	private function getCategoriesArray()
	{
		$this -> load -> library('allegrowebapisoapclient');
		$cat = $this -> allegrowebapisoapclient -> getMainCategories();
		
		$elements = array();
		
		$order = 0;
		$depth = 0;
		$data = array(
			'id_cat' => 0,
			'name' => "Dowolna",
			'parent_id' => 0,
			'sort' => $order,
			'depth' => $depth
		);
		$elements[0] = $data;
		$order++;
		
		foreach($cat -> catsList -> item as $item)
		{			
			$data = array(
				'id_cat' => $item -> catId,
				'name' => $item -> catName,
				'parent_id' => $item -> catParent,
				'sort' => 0,
				'depth' => 0
			);
			
			$elements[$item -> catId] = $data;
			$elementsSort[$item->catId] = $data;
		}
		
		foreach($elementsSort as $key => $row)
		{
			$name[$key] = $row['name'];
		}
		
		array_multisort($name, SORT_LOCALE_STRING, $elementsSort);
		
		$parents = array();
		foreach($elementsSort as $key => $row)
		{
			if(!isset($parents[$row['parent_id']]))
			{
				$parents[$row['parent_id']] = array();
			}
			array_push($parents[$row['parent_id']], $row['id_cat']);
		}
		
		foreach($parents[0] as $index => $childrenID)
		{
			$this -> sortCategories($childrenID, $order, $depth, $elements, $parents);
		}
		
		return $elements;
	}
	
	private function sortCategories($childrenID, &$order, $depth, &$elements, $parents)
	{
		$elements[$childrenID]['sort'] = $order;
		$elements[$childrenID]['depth'] = $depth;
		$order++;
		
		if(isset($parents[$childrenID]))
		{
			$depth++;
			foreach ($parents[$childrenID] as $index => $nextChildrenID)
		    {
		    	$this -> sortCategories($nextChildrenID, $order, $depth, $elements, $parents);
		    }
		}
		
	}
	
	private function insertCategoriesToDB($elements)
	{
		$this -> db -> trans_start();
		foreach($elements as $key => $element)
		{
			$this -> db -> insert('categories', $element);
		}
		$this -> db -> trans_complete();
		log_message('info', 'Dodawanie kategorii do bazy zakończone');
	}	
	
	public function getListOfStates()
	{
		$this -> load -> library('allegrowebapisoapclient');
		$state = $this -> allegrowebapisoapclient -> getStates();
		
		$data = array(
			'id_state' => 0,
			'name' => "Dowolne",
		);	
		$this -> allegro_model -> insertState($data);
		
		foreach($state -> statesInfoArray -> item as $item)
		{	
			$data = array(
				'id_state' => $item -> stateId,
				'name' => $item -> stateName,
			);
			
			$this -> allegro_model -> insertState($data);
		}
		
	}	
	
	public function config()
	{
		
		if($this -> wasConf())
		{
			echo "<p>Dokonywałeś już początkowej konfiguracji serwera. Przejdź do strony logowania.</p>";
		}
		else
		{
			$komunikat = "Gratuluje! Konfiguracja zakończona powodzeniem!";
			
			try{
				$this -> getListOfStates();
				$this -> getListOfCategories();
				$this -> allegro_model -> insertCatVersion($this -> allegro_model -> getCurrentVersion());
			}
			catch(exception $e)
			{
				log_message('error', 'Konfiguracja zakończona niepowodzeniem');
				$komunikat = "Konfiguracja zakończona niepowodzeniem";
			}
			
			echo $komunikat;						
		}
		
	}

	public function wasConf()
	{
		return $this -> db -> count_all('version') > 0 ? true : false;
	}
	
	public function getNewVersion()
	{
		
		$isNewVersion = false;
		
		$currentVersion = $this -> allegro_model -> getCurrentVersion();
		$versionFromDB = $this -> allegro_model -> getVersionFromDB();
		
		if($currentVersion > $versionFromDB)
		{
			
			$isNewVersion = true;
			
			$elements = $this -> getCategoriesArray();
			$query = $this -> db -> query("SELECT id, c.id_cat AS idcat, c.name AS name FROM search s LEFT JOIN categories c ON s.id_cat=c.id_cat");
			
			foreach ($query->result() as $row)
			{
			   if($row -> idcat == null || $elements[$row -> idcat]['name'] != $row -> name)
			   {
			   		$this -> allegro_model -> setFilterBlocked(1, $row -> id);
			   }
			}
			
			$this -> allegro_model -> updateDBVersion($currentVersion);
			$this -> allegro_model -> clearCategories();
			$this -> insertCategoriesToDB($elements);	
			
		}
		
		return $isNewVersion;
	}
}

?>