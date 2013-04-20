<?php

class BasicDataService extends CI_Controller
{
	
	
	public function __construct() 
	{
		
		parent::__construct();
	}
	
	
	public function getListOfCategories()
	{
		$elementy=$this->getCategoriesArray();
		
		$this->insertCategoriesToDB($elementy);
		
		$currentVersion = $this->allegro_model->getCurrentVersion();
		$this->allegro_model->insertCatVersion($currentVersion);
	}
	
	private function getCategoriesArray()
	{
		$this->load->library('allegrowebapisoapclient');
		$cat = $this->allegrowebapisoapclient->getMainCategories();
		
		$elementy = array();
		
		$order=0;
		$depth=0;
		$data = array(
			'id_cat' => 0,
			'name' => "Dowolna",
			'parent_id' => 0,
			'sort' => $order,
			'depth' => $depth
		);
		$elementy[0]=$data;
		$order++;
		
		foreach($cat->catsList->item as $item)
		{
			
			// echo '<p>'.$item->catId.' '.$item->catName.' '.$item->catParent.'</p>';
			
			$data = array(
				'id_cat' => $item->catId,
				'name' => $item->catName,
				'parent_id' => $item->catParent,
				'sort' => 0,
				'depth' => 0
			);
			
			$elementy[$item->catId]=$data;
			$elementySort[$item->catId]=$data;
		}
		
		foreach($elementySort as $key => $row)
		{
			$name[$key]= $row['name'];
		}
		
		array_multisort($name, SORT_LOCALE_STRING, $elementySort);
		
		$rodzice = array();
		foreach($elementySort as $key => $row)
		{
			if(!isset($rodzice[$row['parent_id']]))
			{
				$rodzice[$row['parent_id']] = array();
			}
			array_push($rodzice[$row['parent_id']], $row['id_cat']);
		}
		
		foreach($rodzice[0] as $index => $dzieckoID)
		{
			$this->sortCategories($dzieckoID, $order, $depth, $elementy, $rodzice);
		}
		
		return $elementy;
	}
	
	private function sortCategories($dzieckoID, &$order, $depth, &$elementy, $rodzice)
	{
		$elementy[$dzieckoID]['sort']=$order;
		$elementy[$dzieckoID]['depth']=$depth;
		$order++;
		
		if(isset($rodzice[$dzieckoID]))
		{
			$depth++;
			foreach ($rodzice[$dzieckoID] as $index => $dalszeDzieckoID)
		    {
		    	$this->sortCategories($dalszeDzieckoID, $order, $depth, $elementy, $rodzice);
		    }
		}
	}
	
	private function insertCategoriesToDB($elementy)
	{
		$this->db->trans_start();
		foreach($elementy as $key => $element)
		{
			$this->db->insert('categories', $element);
		}
		$this->db->trans_complete();
	}	
	
	public function getListOfStates()
	{
		$this->load->library('allegrowebapisoapclient');
		$state = $this->allegrowebapisoapclient->getStates();
		
		$data = array(
				'id_state' => 0,
				'name' => "Dowolne",
			);	
		$this->allegro_model->insertState($data);
		
		foreach($state->statesInfoArray->item as $item)
		{
 			
			$data = array(
				'id_state' => $item->stateId,
				'name' => $item->stateName,
			);
			
			$this->allegro_model->insertState($data);
		}
	}	
	
	public function config(){
		if($this->wasConf()){
			echo "<p>Dokonywałeś już początkowej konfiguracji serwera. Przejdź do strony logowania</p>";
		}
		else{
			$komunikat="Gratuluje! Konfiguracja zakończona powodzeniem!";
			try{
				$this->getListOfStates();
				$this->getListOfCategories();
				$this->allegro_model->insertCatVersion($this->allegro_model->getCurrentVersion());
			}
			catch(exception $e){
				$komunikat="Konfiguracja zakończona niepowodzeniem";
			}
			echo $komunikat;						
		}
	}

	public function wasConf(){
		return $this->db->count_all('version')>0 ? true:false;
	}
	
	public function getNewVersion()
	{
		$isNewVersion=false;
		
		$currentVersion = $this->allegro_model->getCurrentVersion();
		
		$versionFromDB = $this->allegro_model->getVersionFromDB();
		if($currentVersion > $versionFromDB)
		{
			$isNewVersion=true;
			$elementy= $this->getCategoriesArray();
			$query = $this->db->query("SELECT id, c.id_cat AS idcat, c.name AS name FROM search s LEFT JOIN categories c ON s.id_cat=c.id_cat");
			foreach ($query->result() as $row)
			{
			   if($row->idcat==null || $elementy[$row->idcat]['name'] != $row->name)
			   {
			   		$this->allegro_model->setFilterBlocked(1, $row->id);
			   }
			}
			
			$this->allegro_model->updateDBVersion($currentVersion);
			$this->allegro_model->clearCategories();
			$this->insertCategoriesToDB($elementy);	
		}
		
		return $isNewVersion;
	}
}

?>