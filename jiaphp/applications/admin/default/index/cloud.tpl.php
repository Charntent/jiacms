<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>登录系统_美藤科技内容管理系统</title>
	<link rel="stylesheet" href="{$skins_admin}/login/cloud/font-awesome.css">
	<!--[if IE 7]>
	<link rel="stylesheet" href="{$skins_admin}/login/cloud/font-awesome-ie7.css">
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="{$skins_admin}/login/cloud/login.css">
		<link rel="stylesheet" type="text/css" href="{$skins_admin}/login/cloud/style.css"/>

</head>
<body>
<div class="body">   
    <div class="main">         
        <div class="slide-content" id="slide_content">     
            <!-- 动画部分 -->   
        </div>
    </div>   
</div>
   <div class="loginbox pop_fadein">
		<div class="title">
			<div class="logo"></div>
			<div class='info'>美藤科技内容管理系统</div>
		</div>
		<form method="post" action="{U($m.'/index')}" name="form1">
        <input name="action" type="hidden" value="login">
			<div class="inputs">
				<div><span>用户名：</span><input id="username" name='username' type="text" placeholder="用户名" required/> </div> 
				<div><span>密码：</span><input id="password" name='password' type="password" placeholder="密码" required /></div>
            				
                 <div><span>验证码：</span><input name="checkcode" type="text" class="form-control" style="width:170px; float:left" placeholder="验证码" required > <img src="{U($m.'/checkcode')}" onClick="this.src='{U($m.'/checkcode')}?'+Math.random()" style="cursor:pointer; height:34px; width:70px" /> </div>
            	</div>	
			<div class="actions">
				<input type="submit" id="submit" value="登陆" />
				<input type="checkbox" class="checkbox" name="rember_password" id='rm' checked='checked' />
				<label for='rm'>记住密码</label>				
			</div>
			<div class="msg"></div>
			<div style="clear:both;"></div>
			<div class='guest'>
		</form>
	</div><a href=""></a>
	<div class="footer">
	Powered by <a href="http://www.mitent.com" target="_blank">美藤科技内容管理系统</a> | © 2014-{date("Y")} All Rights Reserved.
	</div>
	<script src="{$skins_admin}/login/cloud/jquery-1.8.2.min.js"></script>
<script src="{$skins_admin}/login/cloud/jQuery.easing.js"></script>
<script src="{$skins_admin}/login/cloud/script.js"></script>  
</body>
</html>