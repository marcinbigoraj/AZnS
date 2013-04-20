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
			
			// echo '<p>'.$item->catId.' '.$item->catName.' '.$item->catParent.'</p>';
			
			$data = array(
				'id_cat' => $item->catId,
				'name' => $item->catName,
				'parent_id' => $item->catParent,
				'sort' => 0,
				'depth' => 0
			);
			
			 $this->db->insert('categories', $data);
			
		}
		
		$wholeArray = array();
		$order=0;
		$depth=0;
		$data = array(
			'id_cat' => 0,
			'name' => "Dowolna",
			'parent_id' => 0,
			'sort' => $order,
			'depth' => $depth
		);
		array_push($wholeArray, $data);
		$order++;
		
		$query = $this->db->query("SELECT * FROM categories WHERE parent_id=0 ORDER BY name");
		foreach($query->result() as $row)
		{
			$this->sortCategories($wholeArray, $row, $order, $depth);
		}
		
		$this->db->query("DELETE FROM categories");
		foreach ($wholeArray as $key => $item)
	    {
	    	$this->db->insert('categories', $item);
	    }
		
		$catVersion = $cat->verKey;
	}
	
	private function sortCategories(&$wholeArray, $row, &$order, $depth)
	{
		$data = array(
			'id_cat' => $row->id_cat,
			'name' => $row->name,
			'parent_id' => $row->parent_id,
			'sort' => $order,
			'depth' => $depth
		);
		array_push($wholeArray, $data);
		$order++;
		
		$query = $this->db->query("SELECT * FROM categories WHERE parent_id=$row->id_cat ORDER BY name");
		if($query->num_rows()>0)
		{
			$depth++;
			foreach ($query->result() as $deeperRow)
		    {
		    	$this->sortCategories($wholeArray, $deeperRow, $order, $depth);
		    }
		}
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
			echo '<p>'.$item->stateId.' '.$item->stateName.'</p>';
			
			$data = array(
				'id_state' => $item->stateId,
				'name' => $item->stateName,
			);
			
			$this->allegro_model->insertState($data);
		}
	}
	
}

?>