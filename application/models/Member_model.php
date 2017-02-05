<?php
Class Member_model extends CI_Model{
	private $table = 'member';
	
	
	public function __construct()
	{
		parent:: __construct();
		$this->load->database('map');
	}
	
	public function login($account)
	{
		$this->db->where('account', $account);
		$this->db->select('password, name, number');
		$sql = $this->db->get($this->table);
		return $sql;
	} 
	 
	public function registered($data)
	{
		$this->db->insert($this->table, $data);
	}
	
	public function check_account($account)
	{
		$this->db->where('account', $account);
		$sql = $this->db->get($this->table);
		return $sql;		
	}
	
}

?>