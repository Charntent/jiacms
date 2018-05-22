<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{title}</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
</head>

<body>
<!--if empty($action) -->
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr>
        	<th><b>选择</b></th>
			<th><b>表名</b></th>
			<th><b>类型</b></th>
            <th><b>编码</b></th>
            <th><b>记录数</b></th>
            <th><b>大小</b></th>
            <th><b>碎片</b></th>
			<th><b>操作</b></th>
		</tr>
		<!--foreach $tables -->
		<tr>
			<td align="center"><input name="ids[]" type="checkbox" disabled value="{$Name}" checked="checked" ></td>
			<td align="center">{$Name}</td>
			<td align="center">{$Engine}</td>
			<td align="center">{$Collation}</td>
			<td align="center">{$Rows}</td>
			<td align="center">{formatsize($Data_length+$Index_length+$Data_free)}</td>
			<td align="center">{formatsize($Data_free)}</td>
			<td align="center"><a href="?action=optimize&table={$Name}">优化</a> | <a href="?action=repair&table={$Name}">修复</a></td>
		</tr>
		<!--/foreach -->
		<tr>
			<td align="center">总计</td>
			<td align="center">共 {count($tables)} 个表</td>
			<td align="center"></td>
			<td align="center"></td>
			<td align="center">{$allrow}</td>
			<td align="center">{formatsize($totalsize)}</td>
			<td align="center">{formatsize($allfreesize)}</td>
			<td align="center"></td>
		</tr>
	</table>
	<div class="form" style="padding:10px; text-align:center">
		<form action="?action=export" method="post">
			<input name="submit" type="submit" class="btn btn-default" value="立即备份">
			<label>&nbsp;&nbsp;<input name="fast" type="checkbox" value="1"> <span style="color:#666">快速备份，支持超大数据库，需服务器支持</span></label>
		</form>
	</div>
<!--else -->
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr>
			<th width="20%">已备份文件</th>
			<th width="20%">备份时间</th>
			<th width="47%" align="left">数据大小</th>
			<th width="13%">操作</th>
		</tr>
		<!--foreach $bklist -->
		<tr>
			<td align="center"><a href="?action=down&dumpfile={$name}">{$name}</a></td>
			<td align="center">{$time}</td>
			<td align="left">{$size}</td>
			<td align="center">
			<a href="?action=zip&dumpfile={$name}" target="_blank">压缩下载</a> | 
			<a href="?action=import&dumpfile={$name}" onClick="return confirm('当前数据将被还原到这个备份时的数据，你确定要恢复吗？')">恢复</a> | 
			<a href="?action=del&dumpfile={$name}" class="hp-del">删除</a></td>
		</tr>
		<!--/foreach -->
		
	</table>
	<!--if !$bklist -->当前数据还没有备份过，为了安全请您立即备份<!--/if -->
<!--/if -->
</body>
</html>