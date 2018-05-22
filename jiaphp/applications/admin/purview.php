<?php

/**
 * CWCMS  后台权限菜单文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:Purview.php 001 2016-05-28 11:29:08Z Charntent $
 */

require 'admin.inc.php';



$viewar = array("edit",'del');

$lanmuurl = 'purview.php';

$action = gpc('action');



$func = 'purview';
$Purview = new Purview();
if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);




$list_top = $db->select(" select * from purview where parent='0'  order by id asc ");
switch($action){

	case  'grant':


		 $list = $db->t('purview')->where(' `parent`=0 ')->SelectData("*");

	     require tpl('purview/grant');
	break;
	case 'del':
		$id = gpc('delids');

		$id = trim($id,',');

		if(!$id){
			message("参数不对",-1);
		}

		$db->query("DELETE FROM  `purview` WHERE `id` IN($id) ");

		message("删除成功",'',100,1);
		break;
	case "add":

	   $submit = gpc('submit');
	   if(empty($submit)){
			  require tpl('purview/index');
	   }else{
			$data = gpc('data');
			$db->tb('purview');
			if($db->AddData($data)){
				message("添加成功！","purview",1000,0,'success',1);
			}else{
				message("添加失败！","purview");
			}
	   }
	break;

	case "edit":
	   $id = gpc('id');
	   $submit = gpc('submit');
	   $r = $db->find(" select * from purview where id='$id'");

	   if(empty($submit)){

		   require tpl('purview/index');
	   }else{
			$data = gpc('data');
			$db->tb('purview');
			$langid =  gpc('langid');
			if($db->UpdateTable($data,array(" id='{$id}'"))){
				message("修改成功！","purview",1000,0,'success',1);
			}else{
				message("修改失败！","purview");
			}
	   }

	break;


    default:
	//浏览
	case 'list':
       $where = array();
	   $keyword = gpc("keyword");
	   if($keyword){
		  $where[] = " and `class` like '%$keyword%' ";
	   }
	   $where = empty($where)?"":join(" AND ",$where);
	   $p = new Page(" SELECT * from 	`purview`  WHERE `parent`=0 $where ORDER BY listorder asc ",10);
	   $list = $p->getlist();
	   $page = $p->getpage();

	   require tpl('purview/index');
	break;

	case 'order':
	   //即将进入排序
	   $data = gpc('para');
	   $dr = explode("|",$data);

	   $e = 0;
	   foreach($dr as $r){
		   $ar = explode("_",$r);
		   //0表示id，1表示排序号！
		   if(!$db->tb('purview')->UpdateTable(array("listorder"=>$ar[1]),array("id='".$ar[0]."'"))){
			   $e++;
		   }
	   }
	   if($e){
		   message(lang("errordata"));
	   }else{
		   message(lang("success"),'',1000,0,'success',1);
	   }

	break;

}
