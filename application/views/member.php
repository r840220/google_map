<?php if ($this->session->userdata('name')): ?>
	歡迎:<?php echo $this->session->userdata('name');?>
	<button id='logout'>登出</button>
<?php else: ?>
	帳號:<input type='text' id='account'>
	密碼:<input type='password' id='password'>
	<button id='login'>登入</button>
	<button id='registered'><a href='<?php echo base_url('/index.php/member/register');?>' target="_blank" >註冊</a></button>
	驗證碼:<input type='text' id='captcha'>
	<img src='<?php echo base_url('/index.php/member/captcha');?>'>
<?php endif; ?>