<?php

/**
 * CWCMS  后台切换语言文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: lang.php 001 2015-12-10 16:29:08Z Charntent $
 */


require 'admin.inc.php';


$action = gpc('action');

$func = 'lang';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){


    default:
	   
	  $where = array();
			
				$keyword = gpc("keyword");
				if($keyword){
					$where[] = " and langname like '%$keyword%' ";
				}
			
				$where = empty($where)?"":join(" AND ",$where);
				$p = new Page("select * from 	`langs`  WHERE 1=1 $where ",8);
				$list = $p->getlist();
				$page = $p->getpage();
				require tpl('lang/lang');
	
	break;	
	
	case 'del':
				$ids = gpc('id');
				if(!$ids){
				    $ids = trim(gpc('delids'),',');
				}
				if (!$ids) {
					message("参数错误");
				}
				$idsAr = explode(',',$ids);
				foreach($idsAr as $v=>$id){
					$db->query("delete from `langs` where langid='$id'");
					$db->query("delete from `category` where lang='$id'");
					$db->query("delete from `article` where lang='$id'");
					$db->query("delete from `svalue` where lang='$id'");
					$db->query("delete from `flink` where lang='$id'");
					$db->query("delete from `ads` where lang='$id'");
				}
				message("删除成功",-1,1000,0);
			break;
	case "add":
			
			   $submit = gpc('submit');
			   if(empty($submit)){
			        require tpl('lang/lang');
			   }else{
				    $data = gpc('data');
					$db->settable('langs');
					if($db->AddData($data)){
						alert("添加成功！",U($m."/lang"),1000,1,0);
					}else{
						alert("添加失败！",U($m."/lang"));
					}
			   }
	break;
	
	case "edit":
			    
			   $id = gpc('id');
				
			   $submit = gpc('submit');
			   $r = $db->find(" select * from langs where langid='$id'");
			   if(empty($submit)){
				   require tpl('lang/lang');
			   }else{
				    $data = gpc('data');
					$db->settable('langs');
					$langid =  gpc('langid');
					if($db->UpdateTable($data,array(" langid='{$langid}'"))){
						alert("修改成功！",U($m."/lang"));
					}else{
						alert("修改失败！",U($m."/lang"));
					}  
			   }
			
	break;
	
}