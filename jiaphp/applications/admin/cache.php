<?php

/**
 * CWCMS  清除缓存文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Cache.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$action = gpc('action');

$cache = new Cache();
$func = 'cache';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);
switch($action){
	case 'clear':
		$cache ->Clean(WL_DATA.DS."Cache".DS);
		$cache ->Clean(WL_DATA.DS."Tplcache".DS);
		@$cache ->Clean(WL_DATA.DS."Session".DS);
		alert("更新成功");
	break;
	
	default :
	
	require tpl('cache/cache');
	
	break;
	
}