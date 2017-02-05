$(document).ready(function(){
	main();
});


var data_route =[];
var map;
var all_marker = [];


//新增路徑資料
$(document).ready(function(){
	$('#map').on('click', 'button', function(e) {
		var location = $(this).val().split(',');
		var insert = {number:location[0], lat:location[1], lng:location[2], name:location[3]};
		data_route = data_route.concat(insert);
		Print_route(location[3]);		
	});
});


//根據地區+類型,顯示在地圖上
$(document).ready(function(){
	$('#type').on('click', 'button', function(){
		$.ajax({
			method:'GET',
			dataType: "json",
			url:'http://127.0.0.1/map2/index.php/map/get_location',
			data:{
				county:$('#county').val(),
				type:$(this).val()
			},
			success: function(result){
				location(result);
			}
		});
	});
});

//取得詳細景點資料
$(document).ready(function(){
	$('#map').on('click', 'span', function(){
		$.ajax({
			method:'GET',
			url:'http://127.0.0.1/map2/index.php/map/get_location_information',
			data:{
				number:$(this).attr('value')
			},
			dataType:'json',
			success: function(result){
				location_detail(result[0]);
				console.log(result);
				
			},
			 error: function(){
                alert('錯誤');
				return false;
			}
		});
	});
});

//登入
$(document).ready(function(){
	$('#member').on('click', '#login', function(){
		$.ajax({
			method:'POST',
			url:'http://127.0.0.1/map2/index.php/member/login',
			data:{
				account:$('#account').val(),
				password:$('#password').val(),
				captcha:$('#captcha').val()
			},
			success:function(e){
				if(isJson(e)){
					var a = jQuery.parseJSON(e);
					alert(a.error);
					return false;
				}else{
					$('#member').html(e);
				}
			}
		});
	});
});

//登出
$(document).ready(function(){
	$('#member').on('click', '#logout', function(){
		$.ajax({
			url:'http://127.0.0.1/map2/index.php/member/logout',
			success:function(e){
				$('#member').html(e);
			}
		});
	});
});

//送出投票
$(document).ready(function(){
	$('#vote').on('click', 'button',function(){
		$.ajax({
			url:'http://127.0.0.1/map2/index.php/map/vote',
			method:'GET',
			data:{
				grade:$('input[name=grade]:checked').val(),
				location:$('#vote form').attr('value')
			},
			success:function(e){
				alert(e);
			}
		});
	});
});


//主程式
function main(){
	var map = initMap();
}

//初始化GOOGLE地圖
function initMap(center = {lat: 24.083246, lng: 120.5364792}, zoom = 8){
	//map 全域變數
	map = new google.maps.Map(
		document.getElementById('map'),
		{center:center, zoom:zoom},
		google.maps.MapTypeId.ROADMAP
	);
	
	return map;
}

//將每個景點標註在地圖上
function location(result){
	var marker;
	for(var i=0; i < result.length; i++){
		marker = new google.maps.Marker({
				position: {lat: Number(result[i].lat), lng: Number(result[i].lng)},
				map:map
		});	
		all_marker[result[i].number] = marker;
		var infowindow = new google.maps.InfoWindow();
		google.maps.event.addListener(marker, 'click', (function(marker,i, result) {
			return function() {
				infowindow.setContent(
					'<div class="scrollFix"><h2>'+result[i].name+'</h2><button value='+result[i].number +','+ result[i].lat+','+result[i].lng+','+result[i].name+'>加入路徑</button><span value='+result[i].number+'>查看資訊<span></div>'
				);
				infowindow.open(map, marker);
			}
		})(marker,i, result));
	 }
}

var display = function(directionsDisplay){
	this.duration = 0;
	
	this.test = (response, status) => {
		if (status == 'OK') {
            // Display the route on the map.
            directionsDisplay.setDirections(response);
			var amount = response.routes[0].legs.length;
			this.duration = response.routes[0].legs[amount-1].duration.text;
			console.log(this.duration);
          }
	}
}

var promise = function(request){
	var promise = new Promise((resolve,reject) => {
		var directionsService = new google.maps.DirectionsService();
		directionsService.route(request, (response, status) => {
			if (status == 'OK') {
				resolve(response); 
			  }
		});
	});
	
	return promise;
}

//GOOGLE路徑規劃

function Route_Planning(){
	var length = data_route.length;
	var destination = {lat:Number(data_route[length-1].lat),lng:Number(data_route[length-1].lng)};
	var	origin = {lat:Number(data_route[0].lat),lng:Number(data_route[0].lng)};
	var waypoints = [];
	
	for(var i=1; i < length-1; i++){
		waypoints.push({
			location:{lat:Number(data_route[i].lat),lng:Number(data_route[i].lng)},
			stopover:true
		});
	}	
	
	for(var i=0; i<length; i++){
		all_marker[data_route[i].number].setMap(null);
	}
	
	
	var directionsDisplay = new google.maps.DirectionsRenderer({
          map: map
    });
	
	var request = {
          destination: destination, //目的位置
          origin: origin, //起始位置
		  waypoints: waypoints,
          travelMode: 'DRIVING'
    };
	
	var route_display = new display(directionsDisplay);
	var ajax = new promise(request);
	ajax.then(function(response){
		directionsDisplay.setDirections(response);
		var amount = response.routes[0].legs.length;
		var duration = response.routes[0].legs[amount-1].duration.text;
		$('#print_route ol').append(duration+"<li>"+data_route[data_route.length-1].name+"</li>");
		console.log(response);
	});
}



//新增路徑景點
function Print_route(name){
	if(data_route.length > 1){
			Route_Planning();
	}else{
		$('#print_route ol').append("<li>"+name+"</li>");
	}
	
}

//判斷是否為JSON檔案
function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function location_detail(data){
	//console.log(data);
	$('#information h1').text(data.name);
	$('#information img').attr('src', 'http://127.0.0.1/map2/public/map_picture/'+data.number+'.jpg');
	$('#information img').attr('alt', data.name);
	$('#basic_information').html('電話:'+data.phone+'<br>'+'住址:'+data.address);
	$('#introduction').html('<h4>簡介</h4>'+data.introduction);
	$('#vote form').attr('value', data.number);
	$('#bar img').attr('src', 'https://chart.googleapis.com/chart?chs=300x300&chd=t:'+data.grade[1]+','+data.grade[2]+','+data.grade[3]+','+data.grade[4]+','+data.grade[5]+'&cht=br&chl=1|2|3|4|5');
}

function get_duration(e){
	var amount = e.routes[0].legs.length;
	console.log(e.routes[0].legs[amount-1].duration.text);
}
