<html><head>
<meta name="Generator" content="ECSHOP v2.7.3">
<meta charset="utf-8">
<title>温馨提示</title>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style>
body{ font-family:'Microsoft Yahei'}
a{ text-decoration:none}
</style>
</head>



<div class="layui-main" style="max-width:100%;">
       <div class="ub-address-info" id="esm" style="text-align:center">
       <img src="{BASEURL}/resources/images/error.png">
       <p style="line-height:40px; padding-top:20px">{$msg}</p>
       <p style="line-height:40px;"><a href="{$redirect}" class="ub-color">点击此处立即跳转</a></p>
       </div>
</div>

<script>
var h = window.screen.availHeight;

var ma = (h-200)/2-60;

document.getElementById('esm').style.margin = ma+"px auto";

var msg = '{$msg}';
var i =  parseInt('{$time}');
var fn = function () {
	i = i - 1000;
	var df = i/1000<0?0:i/1000
	//that.content(msg+'('+df + '秒后跳转)');
	if(i<0){ 
	  window.location="{$redirect}";clearInterval(timer);
	
	}
};
timer = setInterval(fn, 1000);
</script>
