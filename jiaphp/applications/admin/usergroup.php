<?php

/**
 * CWCMS  后台模板文件
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

$func = 'usergroup';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	
	case  'grant':
	      
		 $do = gpc('do');
		 if($do){
		       $grantall =gpc('grantall');
			   $user_id = gpc('user_id');
			   $ar = explode("|",$grantall);
			   $grant =array();
			   if(!empty($ar)){
				   foreach($ar as $as){
					   $gr = explode(";",$as);
					   $grant[$gr[0]][$gr[1]] = 1;
				   } 
			   }
			   
			   $grant = serialize($grant);
			   $data = array('purview'=>$grant);
			   $m = $db->t("usergroup");
			   if($m->UpdateTable($data,array("id='".$user_id."'"))){
				  
				    message("授权成功！",'',1000,0);
			   }else{
				    message("授权失败！");
			   }	 
		 }else{
			 
			 $user_id = gpc('id');
			 $r = $db->t("usergroup")->where(" id='$user_id' ")->FindData('purview'); 
			
			 $purview = unserialize($r['purview']);
			
			 $list = $db->t('purview')->where(' `parent`=0 ')->SelectData("*");
			 
			 require tpl('usergroup/grant');
		 }
	break;
	case 'del':
		$id = gpc('delids');
		
		$id = trim($id,',');
		
		if(!$id){
			message("参数不对",-1);
		}
		
		$db->query("DELETE FROM  `usergroup` WHERE `id` IN($id) ");
			
		message("删除成功",-1,1000,0);
		break;
	case "add":
			
	   $submit = gpc('submit');
	   if(empty($submit)){
			  require tpl('usergroup/index');
	   }else{
			$data = gpc('data');
			$db->t('usergroup');
			if($db->AddData($data)){
				message("添加成功！",U($m."/usergroup"),1000,0);
			}else{
				message("添加失败！",U($m."/usergroup"));
			}
	   }
	break;
	
	case "edit": 
	   $id = gpc('id');
	   $submit = gpc('submit');
	   $r = $db->find(" select * from usergroup where id='$id'");
	   if(empty($submit)){
		   require tpl('usergroup/index');
	   }else{
			$data = gpc('data');
			$db->settable('usergroup');
			$id =  gpc('id');
			if($db->UpdateTable($data,array(" id='{$id}'"))){
				message("修改成功！",U($m."/usergroup"),1000,0);
			}else{
				message("修改失败！",U($m."/usergroup"));
			}  
	   }
			
	break;


    
	//浏览
	default:

       $where = array();
	   $keyword = gpc("keyword");
	   if($keyword){
		  $where[] = " and varname like '%$keyword%' ";
	   }
	   
	   $where = empty($where)?"":join(" AND ",$where);
	   $p = new Page(" SELECT * from 	`usergroup`  WHERE 1=1 $where ",8);
	   $list = $p->getlist();
	   $page = $p->getpage();
	   
	   require tpl('usergroup/index');
	break;	
	
}
