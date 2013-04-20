<?php

class Allegro_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function createList() 
	{
		
		$query = $this -> db -> query('SELECT id, keywords, c.name AS "categoryName", anyWord, includeDescription, buyNow, city, s.name AS "stateName", minPrice, maxPrice, blocked FROM search se
		INNER JOIN states s ON voivodeship=id_state 
		INNER JOIN categories c ON se.id_cat=c.id_cat WHERE active=1 AND user_id = '.$this->ion_auth->user()->row()->id);
		
		return $query->result();
	}
	
	public function clearAuctions()
	{
		$this -> db -> query("DELETE FROM found_auctions");
	}
	
	public function clearCategories() 
	{		
		$this->db->query("DELETE FROM categories");
	}
	
	public function clearStates() 
	{
		$this->db->query("DELETE FROM states");
	}
	
	public function getStates()
	{
		$query = $this -> db -> query("SELECT * FROM states");
		return $query->result();
	}
	
	public function insertState($data)
	{
		$this->db->insert('states', $data);
	}
	
	public function getCategories()
	{
		$query = $this->db->query("SELECT * FROM categories ORDER BY sort");
		return $query->result();
	}
	
	public function addFilter($data)
	{
		$this->db->insert('search', $data);
	}
	
	public function updateFilter($data, $filterId)
	{
		$this->db->update('search', $data, array('id' => $filterId)); 	
	}
	
	public function getUserIdFromFilterId($filterId)
	{
		$query = $this->db->query("SELECT user_id FROM search WHERE id=$filterId");
		foreach($query->result() as $row)
		{
			$userIdFromDatabase = $row->user_id;
			return $userIdFromDatabase;
		}
		
		return -1;
	}
	
	public function setFilterActive($active, $filterId)
	{
		$this->db->query("UPDATE search SET active=$active WHERE id=$filterId");
	}
	
	public function setFilterBlocked($blocked, $filterId)
	{
		$this->db->query("UPDATE search SET blocked = $blocked WHERE id = $filterId");
	}
	
	public function getFilterById($filterId) 
	{
		$query = $this->db->query("SELECT * FROM search WHERE id=$filterId");
		return $query->result();	
	}
	
	public function getFiltersToSearchService()
	{
		$filterQuery = $this->db->query("
			SELECT u.username, u.email, u.phone, s.*, c.name as c_name, st.name as st_name
			FROM search s
			INNER JOIN users u ON u.id = s.user_id
			LEFT JOIN categories c ON c.id_cat = s.id_cat
			LEFT JOIN states st ON st.id_state = s.voivodeship
			WHERE s.active = 1 AND s.blocked = 0
			ORDER BY s.user_id, id DESC
		");
		
		return $filterQuery->result();
	}
	
	public function getAuctionsForUserQuery($userId)
	{
		return $this->db->query("SELECT * FROM found_auctions WHERE id_user=$userId");
	}

	public function getVersionFromDB()
	{
		$this->db->select('ver_number');
		$this->db->where('name', 'kategorie'); 
		$query = $this->db->get('version');
		return $query->result();
	}
	
	public function updateDBVersion($vers){
		$data = array(
               'ver_number' => $vers
            );

		$this->db->where('name', 'kategorie'); 
		$this->db->update('version', $data); 
	}
	
	public function insertCatVersion($ver){
		$data= array(
			'name'=> 'kategorie',
			'ver_number'=>$var['cat_version']
		);
		
		$this->db->insert('version', $data);
	}
}
?>