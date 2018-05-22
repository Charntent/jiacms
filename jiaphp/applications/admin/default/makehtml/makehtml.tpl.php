<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>美藤科技内容管理系统</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
</head>

<body>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr>
        	<th align="left">生成网站首页</th>
		</tr>
		<tr>
			<td><div style="padding:20px;"><input name="submit" type="submit" class="btn btn-default" value="开始生成" onClick="window.location='?action=makehome'"></div></td>
		</tr>
	</table>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line" style="margin-top:18px;">
		<tr>
        	<th align="left">生成全部栏目</th>
		</tr>
		<tr>
			<td><div style="padding:20px;"><input name="submit" type="submit" class="btn btn-default" value="开始生成" onClick="window.location='?action=makelist'"></div></td>
		</tr>
	</table>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line" style="margin-top:18px;">
		<tr>
        	<th align="left">生成所有文章</th>
		</tr>
		<tr>
			<td><div style="padding:20px;"><input name="submit" type="submit" class="btn btn-default" value="开始生成" onClick="window.location='?action=makearticle'"></div></td>
		</tr>
	</table>
</body>
</html>