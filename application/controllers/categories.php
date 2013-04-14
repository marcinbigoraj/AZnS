<?php

class Categories extends CI_Controller
{
		
		public function __construct() 
	{
		parent::__construct();
	}
	
	
	public function getListOfCategories(){
		$this->load->library('allegrowebapisoapclient');
		$cat = $this->allegrowebapisoapclient->getMainCategories();
		$this -> load -> database();
			
		foreach($cat['cats-list'] as &$item){
			echo '<p>'.$item->{'cat-id'}.'  '.$item->{'cat-name'}.'  '.$item->{'cat-parent'}.'</p>';
			$data = array(
			'id_cat' => $item->{'cat-id'},
			'name' => $item->{'cat-name'},
			'parent_id' => $item->{'cat-parent'},
			);
			$this -> db -> insert('categories', $data);
		}
	}
}
?>