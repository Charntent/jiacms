<?php

/**
 * CWCMS  SEO优化文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:Seotitle.php 202 2015-12-10 16:29:08Z Charntent $
 */


require 'admin.inc.php';
$dqpage = gpc('page');
$action = gpc('action');

$func = 'attach';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);
switch($action){
	
	case 'del':
	   
	   $delids = gpc('delids');
	   $delids = trim($delids,',');
	   if ($delids) {
		  $res = $db->select("SELECT url FROM   `attachments` WHERE id IN($delids) ");
		  if ($res) {
			  foreach($res as $k=>$v) {
				  @unlink($v['url']);  
			  }
		  }
		  $db->query(" DELETE FROM   `attachments` WHERE id IN($delids) ");	
		  message('删除成功！','',1000,0);   	   
	   } else {
		  message('参数错误！');   
	  }
	
	break;
	
	
	default:
	case 'list':
	     
		 $keyword = gpc('keyword');
		 $where = '';
		 if ($keyword) {
			 $where .= " AND `name` LIKE '%$keyword%'";
	     }
		 
		 $p = new Page("SELECT * FROM `attachments` WHERE 1 $where ORDER BY id desc ",10);
		
	     $list = $p->getlist();
		 $page = $p->getpage();
		 
		 
		 require tpl('attach/index');
		 
	break;
	
}