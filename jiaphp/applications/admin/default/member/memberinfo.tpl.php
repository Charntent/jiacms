<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{title}</title>
<meta name="keywords" content="{keywords}" />
<meta name="description" content="{description}" />
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css" />
<style>
.right { text-align:right; color:#BBB}
.center { text-align:center;}
td { padding:5px; border-bottom:1px dotted #c6c6c6;}
</style>
</head>

<body>
	<div class="form" style="width:500px; margin:0px auto">
        <table width="500px">
        	<tr><td colspan="2" style="height:30px; color:#fff; background-color:#c6c6c6; font-size:16px; font-weight:bold">会员档案</td></tr>
        	<tr><td colspan="2" class="center"><img src="{BASEURL}/<?php  if(file_exists($user['face'])) echo $user['face']; ?>" alt="用户头像" /></td></tr>
            <tr><td width="20%" class="right">用户编号：</td><td><?php echo $user['uid'];?></td></tr>
            <tr><td class="right">用户名：</td><td><?php echo $user['username'];?></td></tr>
            <tr><td class="right">用户昵称：</td><td><?php echo $user['nickname'];?></td></tr>
            <tr><td class="right">注册日期：</td><td><?php echo date("Y-m-d",$user['regdate']);?></td></tr>
            <tr><td class="right">QQ号码：</td><td><?php echo $user['QQ'];?></td></tr>
            <tr><td class="right">邮箱：</td><td><?php echo $user['email'];?></td></tr>
            <tr><td class="right">联系电话：</td><td><?php echo $user['mobile'];?></td></tr>
            <tr><td class="right">联系地址：</td><td><?php echo $user['addr'];?></td></tr>
            <tr><td class="right">上次登录日期：</td><td><?php echo date("Y-m-d",$user['lastdate']);?></td></tr>
            <tr><td class="right">注册IP：</td><td><?php echo $user['regip'];?></td></tr>
            <tr><td class="right">上次登录IP：</td><td><?php echo $user['lastip'];?></td></tr>
            <tr><td class="right">登录次数：</td><td><?php echo $user['loginnum'];?></td></tr>
            <tr><td class="right">积分：</td><td><?php echo $user['score'];?></td></tr>
            <tr><td class="right">用户介绍人：</td><td><?php echo $user['introducer'];?></td></tr>
            <tr><td class="right">用户状态：</td><td><?php echo $user['status'];?></td></tr>
            <tr><td colspan="2" style="border-bottom:1px solid #c6c6c6; text-align:center"><a href="{U($m.'/member')}?m=edit&id=<?php echo $user['uid'];?>">修改会员资料</a></td></tr>
        </table>
        <br />
	</div>
</body>
</html>

