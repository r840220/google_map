<?php
Class Map_model extends CI_Model{
	
	public function __construct()
	{
		parent:: __construct();
		$this->load->database('map');
	}
	
	public function insert($data){
		$this->db->insert('location', $data);
	}
	
	public function get_location($data){
		$this->db->select('number, name, lat, lng');
		$this->db->where($data);
		$this->db->where('lat !=', '');
		$result = $this->db->get('location')->result_array();
		return $result;
	}
	
	public function get_location_information($number)
	{
		$this->db->where('number', $number);
		$result = $this->db->get('location')->result_array();
		return $result;
	}
	
	public function check_vote($data)
	{
		$this->db->select('grade');
		$this->db->where('member_number', $data['member_number']);
		$this->db->where('location', $data['location']);
		$result = $this->db->get('vote')->result_array();
		if(empty($result)){
			return 0;
		}else{
			return $result[0]['grade'];
		}
	}
	
	public function insert_vote($data)
	{
		$this->db->insert('vote', $data);
	}
	
	public function get_grade($number)
	{
		$grade = array('1'=>'', '2'=>'', '3'=>'', '4'=>'', '5'=>'');
		
		foreach($grade as $key => $value){
			$this->db->where('location', $number);
			$this->db->where('grade', $key);
			$result = $this->db->get('vote')->result_array();
			$grade[$key] = count($result);
		}
		return $grade;
	}
}
?>