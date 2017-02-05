<html>
<head>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD-SwKdWVlXn-7wYWLf8fy9p0hpHCQuMy4"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src= '<?php echo base_url('/public/google_map.js');?> '></script>
	 <link rel="stylesheet" type="text/css" href= '<?php echo base_url('/public/map.css');?>'>
	<title>資訊網路專題</title>
</head>
<body >
	<div id='member'>
		<?php $this->load->view('member');?>
	</div>
	<div id='main'>
		<div id='select'>
			<select id='county'>
				<option value='changhua'>彰化</option>
				<option value='miaoli'>苗栗</option>
				<option value='taichung'>台中</option>
				<option value='nantou'>南投</option>
			</select>
			<div id='type'>
				<button value='viewpoint'>景點</button>
			</div>
		</div>
		<div class='map_route'>
			<div id='map'></div>
			<div id='route'>
				<!--<button id='clear_route'>清除路徑</button>-->
				<h3>路徑顯示</h3>
				<div id = 'print_route'>
					<ol></ol>
				</div>
			</div>
		</div>
		<div id='location'>
				<div id='information'>
					<h1></h1>
					<img src='' alt='' >
					<div id='basic_information'></div>
				</div>
				<div id='introduction'></div>
				<div id='vote'>
					<form>
					  <input type="radio" name="grade" value="1" checked>一星<br>
					  <input type="radio" name="grade" value="2"> 兩星<br>
					  <input type="radio" name="grade" value="3"> 三星<br>
					  <input type="radio" name="grade" value="4"> 四星<br>
					  <input type="radio" name="grade" value="5"> 五星
					</form>
					<button id='vote_button'>投票</button>
					<div id='bar'><img src='https://chart.googleapis.com/chart?chs=300x300&chd=t:20,30,20,3,5&cht=br&chl=1|2|3|4|5' alt='bar'></id>
				</div>
		</div>
	</div>
</body>
</html>