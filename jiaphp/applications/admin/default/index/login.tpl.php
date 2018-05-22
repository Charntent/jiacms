<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>Jiacms内容管理系统</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">

<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<style>
body{ background:#267cb7}
.frame .top{ background:#267cb7;#222222;#323841; height:154px;
     background-image: -moz-linear-gradient(top, #267cb7, #267cb7);
	 background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #267cb7), color-stop(1, #267cb7));
	 filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#267cb7', endColorstr='#267cb7', GradientType='0');
	 border-bottom:0}
.login{ width:396px; height:303px; padding-top:56px; *padding-top:50px;/* background:url({$skins_admin}/login.png) no-repeat;*/ border:none; box-shadow:none}
.form-control{ background:#fff; border:1px solid #fff; border-radius:3px;    padding: 5px 10px 5px 34px; width:280px; height:42px; line-height:42px; font-family:'MicrosoftYahei'}
.login input.iptext{ }
.login input.iphover{}
.h40{ height:50px; *height:40px;}
.form input.iptext, .form textarea.iptext{}
.login{ background:#267cb7}
.form td, .form th{ color:#FFF}
.navbar-btn{display: block;
    padding: 5px 0;
    font-size: 14px;
    color: #fff;
    width: 100%;
    line-height: 28px;
    height: 42px;
    background: #0C4B77;
    border: 0;
    border-radius: 3px;
    letter-spacing: .5em;
    cursor: pointer;
}
.form input.iptext, .form textarea.iptext{ border:0}
.form tr{ border-top:0}
</style>
</head>

<body>
<div class="frame login_wrap">
	<div class="top" style="text-align:center; font-size:40px; color:#FFF; font-family:'微软雅黑'; line-height:80px;">
      
	</div>
	<div class="login">
		<form name="form1" method="post" action="{U($m.'/index')}" class="form">
		<input name="action" type="hidden" value="login">
		<table width="280" border="0" cellspacing="0" cellpadding="0" align="center" style="">
        <tr height="46">
				<td align="center"><img src="{$skins_admin}/logo_ui5_white.png"></td>
			
			</tr>
			<tr height="46">
				
				<td><input name="username" type="text" id="username" class="form-control" placeholder="管理员账号"  required></td>
			</tr>
			<tr class="h40">
				
				<td><input name="password" type="password" id="password" class="form-control" placeholder="管理员密码" required></td>
			</tr>
			<tr height="52">
				
				<td><input name="checkcode" type="text" class="form-control" style="width:170px; float:left" placeholder="验证码" required > <img src="{U($m.'/checkcode')}" onClick="this.src='{U($m.'/checkcode')}?'+Math.random()" style="cursor:pointer; height:42px; width:110px" /> </td>
			</tr>
			
			<tr height="90">
				
				<td >
                <div style="position:relative; ">
                <input name="submit" type="submit" value="登录" class="btn btn-danger navbar-btn" style="width:280px; background:#0C4B77">
                </div></td>
			</tr>
            
			<tr height="90">
				
				<td >
               <p style="font-size: 12px;
    color: #83b7ce;
    line-height: 1.8em;">版权所有 深圳市慧名科技有限公司 Copyright © 2014 - {date('Y')} huimingai.com Inc. All Rights Reserved.

               </p>
               </td>
			</tr>
		</table>
		</form>
	</div>
</div>

</body>
</html>