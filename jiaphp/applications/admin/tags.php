<?php

/**
 * CWCMS  后台切换语言文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: lang.php 001 2015-12-10 16:29:08Z Charntent $
 */


require 'admin.inc.php';


$action = gpc('action');

$func = 'tags';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	
    default:
	   
	   $where = array();
	   $order = gpc('order');
	   $keyword = gpc("keyword");
	   if($order==''){
		  $where[] = "  ORDER BY listorder asc,id desc ";   
	   }
	   if($order==1){
		  $where[] = "  ORDER BY listorder desc,id desc ";   
	   }
	   
	   if($order==2){
		  $where[] = "  ORDER BY listorder asc,id desc ";   
	   }
	   if($order==3){
		  $where[] = "  ORDER BY nums desc,id desc ";   
	   }
	    if($order==4){
		  $where[] = "  ORDER BY nums asc,id desc ";   
	   }
	   
	   

	   $where = empty($where)?"":join(" AND ",$where);
	   $p = new Page("select * from 	`tags`  WHERE 1=1 $where ",15);
	   $list = $p->getlist();
	   $page = $p->getpage();
	   
	   //更新一下统计的次数
	  /* foreach($list as $k=>$v){
		  $trs = $db->select("select id from article where tags like ',%".$v['id']."%,' "); 
		  $l = count($trs);
		  $db->query(" update tags set nums='".$l."' where id='".$v['id']."'");  
	   }*/
	   
	   require tpl('tags/index');
	
	break;	
	
	 case 'del':
		$id = gpc('id');
	    $db->query("delete from tags where id IN($id)");
	    message("删除成功",'',1000,0);
        break;
	case "add":
			
			 
	break;
	
	case "edit":
			    
	     //修改TAGS
		 $target = gpc('target');
		 $id = gpc('id');
		 if($target=='order'){
			 $neworder  = gpc('neworder');
			 $db->query("update tags set listorder ='$neworder' where id='$id'");
			 message("操作成功",'',1000,0);
		 }
		 
			
	break;
	
}