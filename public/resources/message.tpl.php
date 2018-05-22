<!DOCTYPE html>
<html>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示页面</title>
<meta name="keywords" content="提示页面" />
<meta name="description" content="提示页面" />
<script type="text/javascript" src="{_STATIC}/js/jquery.js?4.17"></script>
<style>
body{ font-family:'Microsoft Yahei'}
</style>
</head>
<body>
<div class="layui-main" style="max-width:100%;">
       <div class="ub-address-info" id="esm" style="text-align:center">
       <img src="{BASEURL}/resources/images/error.png">
       <p style="line-height:40px; padding-top:20px">{$msg}</p>
       <p style="line-height:40px;"><a href="{$redirect}" class="ub-color">点击此处立即跳转</a></p>
       </div>
</div>

<script>
$(function(){
	
	
	var pageHeight = $(window).height(); 
	var ma = (pageHeight-200)/2-60;

	$('#esm').css('margin',ma+"px auto");
	
	var msg = '{$msg}';
	var i =  parseInt('{$time}');
	var fn = function () {
		i = i - 1000;
		var df = i/1000<0?0:i/1000
	
		if(i<0){ 
			window.location="{$redirect}";
			clearInterval(timer);
		}
	};
	timer = setInterval(fn, 1000);
	
})
</script>

</body>

</html>