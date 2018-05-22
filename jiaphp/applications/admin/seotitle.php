<?php

/**
 * CWCMS  SEO优化文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
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

$func = 'seotitle';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);
switch($action){
	
	case 'edit':
		$submit = gpc('submit');
		$id = gpc('id');
		if($submit){
			
			$ar = array('cattitle'=>gpc('cattitle'),'keywords1'=>gpc('keywords1'),'description1'=>gpc('description1'),'pic'=>gpc('pic'),'mbpic'=>gpc('mbpic'),'catname'=>gpc('catname'),'ename'=>gpc('ename'),'mbtitle'=>gpc('mbtitle'),'mbkeywords'=>gpc('mbkeywords'),'mbdescription'=>gpc('mbdescription'));
			
			$db->t('category')->UpdateTable($ar,array('id='.gpc('id')));
			$Wls_cache->Delete('_categorys_'.LANG);
			alert('修改成功！',U($m."/seotitle")."?action=list&page=".$dqpage);
		}else{
			$r = $db->find("select id,catname,cattitle,keywords1,description1,pic,mbpic,ename,mbtitle,mbkeywords,mbdescription from category where id=$id");
			include tpl('seotitle/seotitle');
		}
	break;
	case 'list':
	default:
	     
		$keyword = gpc('keyword');
	    $addon = '';
		if($keyword){
			$addon = " and catname like'%$keyword%'";
		}
	    $p = new Page("select id,catname,cattitle,keywords1,description1,pic,mbpic,ename from `category`  where   lang='".LANG."' $addon order by id desc ",15);
		$list = $p->getlist();
		$page = $p->getpage();
		
		require tpl('seotitle/seotitle');
	break;
}