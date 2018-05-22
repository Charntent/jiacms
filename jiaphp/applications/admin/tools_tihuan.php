<?php

/**
 * CWCMS  后台整站替换文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:tools_tihuan.php 202 2015-12-10 16:29:08Z Charntent $
 */
require 'admin.inc.php';

$submit = gpc('submit');
$replace = gpc('replace');
$to = gpc('to');
if($submit){
	$db->query("update article set content=REPLACE(content,'$replace','$to')");	
	$db->query("update page set content=REPLACE(content,'$replace','$to')");
	message('操作完成！');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内容批量替换工具</title>
</head>

<body>
<h3>批量替换工具（批量替换article、page表content字段，常用于替换文件路径）</h3>
<form method="post">
<table width="500" border="1" cellspacing="0" cellpadding="0">
	<tr>
		<td>要替换的字符</td>
		<td><input name="replace" type="text" value="<?php echo $sitepath;?>" /></td>
	</tr>
	<tr>
		<td>替换为</td>
		<td><input name="to" type="text" /></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="执行" onclick="return confirm('操作危险，请先备份数据库，确定替换？');" /></td>
	</tr>
</table>
</form>
</body>
</html>