<?php

/**
 * CWCMS  后台单页文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:page.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$id = gpc('id');

$submit = gpc('submit');
$action = 'list';
if($submit){
  $action = 'edit';	
}
$func = 'page';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);


if(S('rewrite_type') =='静态'){
   $is_html = 1;	
}
if($submit){
	$fields = gpc('data');
	$data['fields'] = addslashes( serialize( gpc_stripslashes( $fields ) ) );
	
	$db->data($data)->save("page");
	if(!empty($is_html)){
		make_list_html($id);
		make_home_page();
	}
	alert("保存成功！");
}else{
	$arr = array('title'=>'','keywords'=>'','description'=>'','thumb'=>'','content'=>'','alt'=>'');
	$art = $db->find("select * from page where id='$id'");
	if(empty($art)) $art = $arr;
	if(!empty($art['fields'])){
		$fields = @unserialize($art['fields']);
		foreach((array)$fields as $_key => $_val){
			if(!isset($art[$_key])) $art[$_key] = $_val;
		}
		unset($art['fields']);
	}
	$category = $db->find("select * from category where id='$id' ");

	require tpl('page/page');
}


?>