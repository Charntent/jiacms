<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>美藤科技</title>
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css" />
</head>

<body>
	<div class="searchbar clearfix">
     <ul class="nav nav-tabs" role="tablist" style="border:none">
         <li role="presentation" style="border:none">
            <form id="search" action="{U($m.'/member')}?m=search" method="post" class="navbar-form navbar-left">
             <div class="form-group" style="float:left; border:none">
             <span class="fl">用户名：</span>
                 <input type="text" class="form-control" name="keyword" style="width:150px; float:left;"  placeholder="Search">
                 </div>
                <input type="submit" class="btn btn-default" value="搜索" style=" float:left; height:34px;*margin-top:-8px; *margin-left:10px;" />
            </form>
            </li>
        </ul>
	</div>
<div class="form1">
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tr_line">
		<tr class="tr_line">
			<th>用户名</th>
			<th>邮箱</th>
			<th>上次登录时间</th>
			<th>操作</th>
		</tr>
		<!--foreach $list -->
		<tr>
			<td align="center"><a href="{U($m.'/member')}?m=info&id={$uid}">{$username}</a></td>
			<td align="center">{$email}</td>
			<td align="center">{date("Y-m-d H:i",$lastdate)}</td>
			<td align="center"><a href="{U($m.'/member')}?m=edit&id={$uid}">修改</a>|<a href="{U($m.'/member')}?m=info&id={$uid}">查看</a>|
            <a href="{U($m.'/member')}?m=delu&uid={$uid}" class="hp-del">删除</a>
            </td>
		</tr>
		<!--/foreach -->
	</table>
	<div class="page_box">{$page}</div>
</div>
</body>
</html>
