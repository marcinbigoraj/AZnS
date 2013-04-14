<?php

class BasicDataService extends CI_Controller
{
		
	public function __construct() 
	{
		parent::__construct();
	}
	
	
	public function getListOfCategories()
	{
		$this->load->library('allegrowebapisoapclient');
		$cat = $this->allegrowebapisoapclient->getMainCategories();		
		
		foreach($cat->catsList->item as $item)
		{
			
			echo '<p>'.$item->catId.' '.$item->catName.' '.$item->catParent.'</p>';
			
			$data = array(
				'id_cat' => $item->catId,
				'name' => $item->catName,
				'parent_id' => $item->catParent,
			);
			
			$this->db->insert('categories', $data);
		}
		
		$catVersion = $cat->verKey;
	}
	
	public function getListOfStates()
	{
		$this->load->library('allegrowebapisoapclient');
		$state = $this->allegrowebapisoapclient->getStates();
			
		foreach($state->statesInfoArray->item as $item)
		{
			echo '<p>'.$item->stateId.' '.$item->stateName.'</p>';
			
			$data = array(
				'id_state' => $item->stateId,
				'name' => $item->stateName,
			);
			
			$this->db->insert('states', $data);
		}
	}
	
}

?>