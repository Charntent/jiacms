<?php

/**
 * CWCMS  后台模板文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:Template.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';


$action = gpc('action');

$const = 'wlcms_templates';
$func = 'template';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	case 'save':
	  
	 $data =  gpc('data');
	 $rs =  serialize($data);
	
	 $r = $db->query(" UPDATE `svalue` SET `value`='$rs' WHERE `sname`='$const' ");
	 $Wls_cache->Delete('wlcms_templates');
	 alert('设置成功');
	break;


    default:
	   
	   $r = $db->t('svalue')->where(" sname='$const' ")->get(1);
	  
	   if(!$r){
		   $db->t('svalue')->AddData(array('sname'=>$const,'lang'=>'','value'=>''));
		   $r = $db->t('svalue')->where(" sname='$const' ")->get(1);
	   }
	   $values = '';
	   if($r['value']!=''){
		  $values = unserialize($r['value']);
	   }else{
		  $values  =  array(); 
	   }
	   
	   
	 /* $langs_ar = array();
	  
	  foreach($langs as $l=>$v){
		  $langs_ar[$v['langid']] = $v;
	  }
	  dump($langs_ar);
	  */
	   
	   
	   require tpl('template/template');
	
	break;	
	
}