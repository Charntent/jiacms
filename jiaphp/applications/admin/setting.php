<?php

/**
 * CWCMS  后台网站设置文件
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


$se = gpc('se');
$set_ars = array('网站SEO设置','系统设置','伪静态静态设置','misc设置','联系设置');
if(!$se){
   $se = 0;	
}

$action = gpc('action');

$func = 'setting';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);



$cache = new Cache();
$idf = $cache->Delete('config_'.LANG);

switch($action){
	case 'save':
		$data = gpc('data');
		$ajax = gpc('ajax');
		
		$rewrite_type = isset($data['rewrite_type'])?$data['rewrite_type']:'';
		if($rewrite_type=='程序伪静态'){
			if(file_exists(WL_ROOT.DS.'index.html'))
				@unlink(WL_ROOT.DS.'index.html');
		}
		
		foreach($data as $k => $r){
			if(is_array($r))  $r = addslashes( serialize( gpc_stripslashes($r) ) );
			
			$db->query("update svalue set `value`='$r' where `sname`='$k' and lang='".LANG."' ");
		}
		if($ajax) {
		    message("修改成功",'-1',1000,0);
		}else{
			alert('修改成功');
		}
	break;
	case 'setstatus':
		$status = gpc('status');
		$name = gpc('name');
		$db->query("update setting set status='$status' where `name`='$name'");
		alert("ok");
	break;
	case 'add':
		$submit = gpc('submit');
		if(empty($submit)){
			$r['title'] = $r['name'] = $r['type'] = $r['default'] = $r['desc'] = '';
			$r['weight'] = 0;
			require tpl('setting/setting');
		}else{
			$name = gpc('name');
			$title = gpc('title');
			if(!$name || !$title){
			    alert('请输入字段中文标题或者英文名称',-1);	
			}
			$db->save("setting");
			alert("添加成功",U($m."/setting"));
		}
	break;
	case "del":
		$name = gpc('name');
		$db->query("delete from setting where `name`='$name'");
		$db->query("delete from svalue where `sname`='$name' and lang='".LANG."'");
		alert("删除成功",U($m."/setting"));
	break;
	case "edit":
		$submit = gpc('submit');
		if(empty($submit)){
			$id = gpc('id');
			$r = $db->find("select * from setting where `id`='$id'");
			include tpl('setting/setting');
		}else{
			$db->save("setting");
			alert("修改成功",U($m."/setting")."?se=".gpc('issystem'));
		}
	break;
	default:
		if($debug){
			$r=$db->select("select * from setting where issystem='$se' order by weight asc,id asc");
		}else{
			$r=$db->select("select * from setting where status=1 and issystem='$se' order by weight asc, id asc");
		}
		
		
		//找所有
		$svalues = $db->select(" select * from svalue where lang='".LANG."' ");
		
		$svalues_ar = array();
		
		foreach($svalues as $k=>$v)		{
			$svalues_ar[$v['sname']] = $v['value'];
		}
	
		
		foreach($r as $k=>$vv)	{
			$r[$k]['value'] = isset($svalues_ar[$vv['name']])?$svalues_ar[$vv['name']]:'';
			
			if(!isset($svalues_ar[$vv['name']])){
			   $db->t('svalue')->AddData(array('sname'=>$vv['name'],'value'=>'','lang'=>LANG));	
			}
		}	
		
		
		include tpl('setting/setting');
}

?>