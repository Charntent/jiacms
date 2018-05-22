<?php

/**
 * CWCMS  sitemap自动生成的文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:Template.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';


$action = gpc('action');

$func = 'robots';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);
$Robots = new Robots();
switch($action){
	
	case 'save':
	   
	   $robots = gpc('robots');
	   
	   $Robots ->_write_robots($robots);
	   alert('提交成功！',U($m.'/robots'));
	break;
	
 default:

  $Robots_content = $Robots->index();
  if($Robots_content==''){
	 $Robots_content = 'User-Agent: *
Allow: *';  
  }
  
  require tpl('robots/index');
}
