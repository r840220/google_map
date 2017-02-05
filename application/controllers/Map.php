<?php
class Map extends  CI_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('Map_model');
		$this->load->helper('url');
		$this->load->library('session');
	}
	
	public function index(){
		$this->load->view('main_map');
	}
	
	public function get_location()
	{
		$data['county'] = $this->input->get('county');
		$data['type'] = $this->input->get('type');
		$result = $this->Map_model->get_location($data);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
	}
	
	public function get_location_information()
	{
		$number = $this->input->get('number');
		$result = $this->Map_model->get_location_information($number);
		$result[0]['grade'] = $this->Map_model->get_grade($number);
		echo json_encode($result,JSON_UNESCAPED_UNICODE);
		
	}
	
	public function vote()
	{
		$data = array();
		$data['grade'] = $this->input->get('grade');
		$data['location'] = $this->input->get('location');
		if($data['member_number'] = $this->session->userdata('number')){
			if(!$this->Map_model->check_vote($data)){
				$this->Map_model->insert_vote($data);
				echo '成功';
			}else{
				echo '您已經投過票了';
			}
		}else{
			echo '請登入帳號';
		}
		
	}
}
?>