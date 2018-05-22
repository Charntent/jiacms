<?php

/**
 * CWCMS  数据配置文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Datalist.php 202 2015-12-10 16:29:08Z Charntent $
 */
require 'admin.inc.php';

$action = gpc('action');

$func = 'datalist';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);



$table = gpc('table');
$cfgfile =  WL_STATIC.DS.'config'.DS.'table_'.$table.'.php';

if(empty($table)) die("未知数据表");
if(!$db->find("SHOW TABLES like '$table'",false)){
	die("数据表不存在");
}

if(is_file($cfgfile)){
	$cfg = require $cfgfile;
}

switch($action){
	case "setting":
		$submit = gpc("submit");
		if(empty($submit)){
			$fields = $db->select("SHOW COLUMNS FROM `$table`","Field");
			
			$fields = array_keys($fields);
			
			if(!in_array('id',$fields)){
				die('字段名ID必须存在');
			}
		}else{
			$data['listfield'] = (array)gpc("listfield");
			$data['editfield'] = (array)gpc("editfield");
			$data['searchfield'] = (array)gpc("searchfield");
			$data['func'] = (array)gpc("func");
			$data['field'] = (array)gpc('field');
			$data = gpc_stripslashes($data);
			$data = var_export($data,true);
			file_put_contents($cfgfile,'<?php return '.$data.'; ?>');
			alert("保存成功！");
		}
	break;
	case "add":
		$submit = gpc("submit");
		if($submit){
			$fields = $db->select("SHOW COLUMNS FROM `$table`","Field");
			// 字段名带 time 的字段 默认保存当前时间
			foreach($fields as $f => $v){
				if(stripos($f,'time')!==false && !gpc($f)) set_gpc($f,$timestamp);
			}
			//自定义处理
			inputfilter();
			
			$db->save($table);
			alert("保存成功",U($m."/datalist")."?table=".$table);
		}
	break;
	case "view":
		$id =gpc("id");
		$r = $db->find("select * from `{$table}` where id='$id'");
		$r = outputfilter($r);
	break;
	case "edit":
		$submit = gpc("submit");
		if(!$submit){
			$id =gpc("id");
			$r = $db->find("select * from `{$table}` where id='$id'");
			$r = outputfilter($r);
		}else{
			//自定义处理
			inputfilter();
			$db->save($table);
			alert("保存成功",U($m."/datalist")."?table=".$table);
		}
	break;
	case "del":
		$id =gpc("id");
		$db->query("delete from `{$table}` where id='$id'");
		message("删除成功！",'',1000,0);
	break;
	case 'tg':
		$id =gpc("id");
		$r = $db->find('select status from guestbook  where id='.$id);
		if($r['status'])
		$db->query("update `{$table}` set status = 0 where id='$id' ");
		else 
		$db->query("update `{$table}` set status = 1 where id='$id' ");
		alert("审核成功！");
	break;
	case "huifu":
		$id =gpc("id");
		$r = $db->find('select * from guestbook  where id='.$id);
		$do = gpc('do');
		if(empty($do)){
			require tpl("datalist/huifu");
			exit();
		}else{
			$data =gpc('data');
			$data['addtime'] =time();
		
			
			$data['username'] = $_SESSION['admin_username'];
			
			$sql = "INSERT INTO guestbook(`pid`,`content`,`username`,`addtime`) VALUES('".$data["pid"]."','".$data['content'] ."','".$data['username']."','".$data['addtime']."')";
		
			if($db->query($sql)){	
			    unset($data);
				alert("回复成功！");
				exit();
			}else{
				alert("回复不成功！");
				exit();
			}
		}
		
	break;
	default:
		if(!is_file($cfgfile)){
			alert("数据表尚未配置","?action=setting&table=".$table);
		}
		$fields = $db->select("SHOW COLUMNS FROM `$table`","Field");
		$fields = array_keys($fields);
		if(!in_array('id',$fields)){
			die('字段名ID必须存在');
		}
		$orderby = 'id';
		if(in_array('weight',$fields)){
			$orderby = 'weight ASC,id';
		}
		foreach($fields as $key => $field){
			if(!in_array($field,(array)@$cfg['listfield'])) unset($fields[$key]);
		}
		
		$wheres = array();
		foreach($cfg['searchfield'] as $f){
			${'where_'.$f} = gpc('where_'.$f);
			if(!empty(${'where_'.$f})){
				if(is_numeric(${'where_'.$f})){
					$wheres[] = "`$f` = '".${'where_'.$f}."'";
				}else{
					$wheres[] = "`$f` like '%".${'where_'.$f}."%'";
				}
			}
		}
		$where = !empty($wheres)?" WHERE ".join(' AND ',$wheres):" WHERE 1 ";
		
		$p = new Page("select * from `$table` $where and  lang='".LANG."' order by $orderby desc",20);
		$list = $p->getlist();
		
		foreach($list as $k => $r){
			$list[$k] = outputfilter($r);
		}
		
		$page = $p->getpage();
}

require tpl("datalist/datalist");

function outputfilter($arr){
	global $cfg,$action,$table,$id;
	foreach($cfg['field'] as $k => $r){
		if(!empty($r['outputexec']) && isset($arr[$k]) ){
			$$k = $arr[$k];
			eval($r['outputexec']);
			$arr[$k] = $$k;
		}
	}
	return $arr;
}

function inputfilter(){
	global $cfg,$action,$table,$id;
	// 先给字段赋值，便于当前字段调用其它字段
	foreach($cfg['field'] as $k => $r){
		$gpc = gpc($k);
		if(is_array($gpc)){
			$gpc = join(',',$gpc);
			set_gpc($k,$gpc);
		}
		if($gpc!==null){
			$$k = $gpc;
		}
	}
	foreach($cfg['field'] as $k => $r){
		if(!empty($r['inputexec'])){
			eval($r['inputexec']);
			set_gpc($k,$$k);
		}
	}
}

?>