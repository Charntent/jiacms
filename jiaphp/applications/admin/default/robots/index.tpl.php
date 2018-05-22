<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>慧名科技</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>


</head>
<body>
<div style="margin-left:50px; margin-top:30px">

<form name="form1" method="post" action="{U($m.'/robots')}" class="system">
<table width="90%" border="0" cellspacing="0" cellpadding="0" class="form">
	<tr>
		<td width="15%">Robots</td>
		<td width="85%"><textarea name="robots"class="form-control" id="robots" style="width:100%;  height:280px;">{$Robots_content}</textarea>
        </td>
	</tr>
	
	<tr>
		<td>&nbsp;</td>
		<td>
        <input type="hidden" name="action" value="save">
        <input name="submit" type="submit" value="提交" class="btn btn-success">
        <a href="{BASEURL}/robots.txt" target="_blank" class="btn btn-danger"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>查看Robots.txt</a>
        <a href="{BASEURL}/robots.txt" target="_blank" class="btn btn-info"><span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>下载示例</a>
        </td>
	</tr>
</table>
</form>
<div style="text-align:center">
<p style="color:#f30; font-size:20px">Robots的示例写法介绍</p>
<a href="" style="text-align:center"><img src="{$skins_admin}/robot.png"></a>
</div>
</div>
</body>
</html>