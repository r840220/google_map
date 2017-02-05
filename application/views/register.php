<html>
<head>
<meta charset="utf-8">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$('#submit').click(function(){
			$.ajax({
				url:'http://127.0.0.1/map2/index.php/member/registered' ,
				data:{
					account: $('#account').val(),
					password: $('#password').val(),
					name: $('#name').val(),
					mail: $('#mail').val(),
					captcha: $('#captcha').val(),
				},
				type:'POST',
				success: function(result){
					alert(result);
				}
			});
		});
	});
	
	$(document).ready(function(){
		$('#account').change(function(){
			$.ajax({
				url:'http://127.0.0.1/map2/index.php/member/check_account',
				data:{account:$(this).val()},
				type:'GET',
				success:function(result){
					$('#check').html(result);
				}
			});
		});
	});
</script>



</head>
<body>
帳號:<input type="text" id="account" ><div id='check' style='display: inline;'></div><br/>
密碼:<input type="password" id="password" ><br/>
姓名:<input type="text" id="name" ><br/>
電子信箱:<input type="text" id="mail" ><br/>
驗證碼:<input type='text' id='captcha'><br />
<img src='<?php echo base_url('/index.php/member/captcha');?>'>
<button id="submit">送出</button><br />
<div id="test"></div>
</body>
</html>