<?php

/**
 * CWCMS  生成静态文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: makehtml.php 202 2015-12-10 16:29:08Z Charntent $
 */

ini_set('memory_limit','128M');
set_time_limit(180);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once 'admin.inc.php';
$action = gpc('action');

$func = 'makehtml';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);


switch($action){
	case "makehome":
		$r = make_home_page();
		alert("首页生成完成！");
	break;
	case "makelist":
		$id = intval( gpc("id") );
		$r = $db->find("select id,catname,template from category where cattype in ('article','page') AND id>'$id' order by id asc");
		if(empty($r)) alert("生成完成",U($m."/makehtml"));
		if(empty($r['template'])){
			alert($r['catname']." 不需要生成","?action=makelist&id=".$r['id']);
		}else{
			make_list_html($r['id']);
			alert($r['catname']." 生成完成，正在生成下一个栏目","?action=makelist&id=".$r['id']);
		}
	break;
	case "makearticle":
		$id = intval( gpc("id") );
		$lsid = make_article_html($id,1);
		if(empty($lsid)){
			alert("生成完成",U($m."/makehtml"));
		}
		alert("当前已生成到".$lsid."，请稍后...","?action=makearticle&id=".$lsid);
	break;
	default:
	
	     
	

		if(S('rewrite_type')!='静态'){
				  //	alert("请在系统设置中伪静态设置启用静态！");
					exit("请在系统设置中伪静态设置启用静态！");
		}
		if(count($langs)>1){
			//alert('多语言不支持生成静态，请将多语言分开！');
			exit('多语言不支持生成静态，请将多语言分开！');
		}
		$action = gpc('action');
		
		if(S('rewrite_type') =='静态'){
		   $is_html = 1;	
		}

		require tpl("makehtml/makehtml");
	
}




