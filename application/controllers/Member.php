<?php
Class Member extends CI_Controller{
	
	private $person = array(
				'number' => "",
				'name' => "",
				'mail' => "",
				'account' => "",
				'password' => "",
				
			);
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Member_model');
		$this->load->library('session');
		$this->load->helper('url');
		
	}
	
	public function login()
	{
		if($this->session->userdata('captcha') == $this->input->post('captcha')){
			$this->person['account'] = $this->input->post('account');
			$this->person['password'] = $this->input->post('password');
			$request = $this->Member_model->login($this->person['account'])->result_array();
			if(empty($request)){
				$response['error'] = "查無帳號";
				$response['success'] = false;
				echo json_encode($response, JSON_UNESCAPED_UNICODE);
			}else{ 
				$data = $request[0];
				if($data['password'] != $this->person['password']){
					$response['error'] = "密碼錯誤";
					$response['success'] = false;
					echo json_encode($response, JSON_UNESCAPED_UNICODE);
				}else{
					$this->person['number'] = $data['number'];
					$this->person['name'] = $data['name'];
					$response['name'] = $this->person['name'];
					$response['success'] = true;
					//echo json_encode($response, JSON_UNESCAPED_UNICODE);
					$this->session->set_userdata($this->person);
					$this->load->view('member');
				}
			}
		}else{
			$response['error'] = "驗證碼錯誤";
			$response['success'] = false;
			echo json_encode($response, JSON_UNESCAPED_UNICODE);
		}
		
	}
	
	public function register()
	{
		$this->load->view('register');
	}
	
	public function registered()
	{
		if($this->session->userdata('captcha') == $this->input->post('captcha')){
			$data =  array(
					'name' => "",
					'mail' => "",
					'account' => "",
					'password' => "",
				);
			foreach($data as $key => $value){
				if($this->input->post($key) == null){
					echo "請輸入".$key;			
				}else{
					$data[$key] = $this->input->post($key);
				}
			}
			$request = $this->Member_model->check_account($data['account'])->result_array();
			if(empty($request)){
				$randnumber = rand(1000,9999);
				$this->send_mail($data['mail'], $data['account'], $randnumber);
				$this->Member_model->registered($data);
			}else{
				echo "帳號已有人用";
			}
			
		}else{
			echo '驗證碼錯誤';
		}
		
	}
	
	public function check_account()
	{
		$check = $this->input->get('account');
		$request = $this->Member_model->check_account($check)->result_array();
		if(empty($request)){
			echo "帳號無人使用";
		}else{
			echo "已有人用";
		}
	}
	
	public function logout()
	{
		$this->session->unset_userdata('name');
		$this->session->unset_userdata('number');
		$this->load->view('member');
	}
	
	public function captcha()
	{
		$text = rand(1000,9999);
		$this->session->captcha = $text;
		$im = imagecreatetruecolor(60, 30);
		$white = imagecolorallocate($im, 255, 255, 255);
		$black = imagecolorallocate($im, 0, 0, 0);
		imagefilledrectangle($im, 0, 0, 59, 29, $black);
		$font = 'C:\\Windows\\Fonts\\Arial.ttf';
		imagettftext($im, 12, 0, 10, 20, $white, $font, $text);
		imagepng($im);
		imagedestroy($im);

	}
	
	public function send_mail($mail='r840220@gmail.com', $name='r840220', $number='98745')
	{
		require_once(APPPATH.'libraries/PHPMailer-master/PHPMailerAutoload.php');
		$data = $mail;
		$account = $name;
		date_default_timezone_set('Asia/Taipei');
		$mail = new PHPMailer;
		$mail->isSMTP();
		$mail->SMTPDebug = 0;
		$mail->Debugoutput = 'html';
		$mail->Host = 'smtp.gmail.com';
		$mail->Port = 587;
		$mail->CharSet = "UTF-8";
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Username = "ncues0222008@gmail.com";
		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);
		$mail->Password = "e601210E3211";
		$mail->setFrom('ncues0222008@gmail.com', '台灣宅景點');
		$mail->addAddress($data, '會員');
		$mail->Subject = '信箱驗證';
		$mail->msgHTML("
			<form action='' method='post'>
				<input type='hidden' name='name' value='$account,$number'>
				<input type='submit' value='信箱驗證'>
			</form>
		");
		//send the message, check for errors
		if (!$mail->send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
		//echo $mail.$name.$number;
	}
	
	
}




?>