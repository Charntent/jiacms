<?php

/**
 * CWCMS  语言切换控制器文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: module/changelang.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

$action = gpc('action');

switch($action){
	
  default:
  
   $langid = gpc('lang');
	
	if(isset($langs[$langid]) && session('clang')!=$langid){
		 session('clang',$langid);
		 message('切换成功！','',1000,0);
	}else{
	    message('切换错误！','',1000,0);	
	}
  break;
  
  case 'getnext':
     $catid = gpc('catid');
     $subnav = get_nav($catid,$catid,1);
	 $catid = '0';
	 foreach($subnav as $k=>$v){
		  $catid .= ($catid==''?$v['id']:','.$v['id']);
	 }
	 
	 
	 
	 $pagesn=empty($categorys[$catid]['pagesize'])?16:$categorys[$catid]['pagesize'];
     $_page = new Page("select * from article where catid IN($catid) order by id desc",$pagesn);
	 
	 // 可用变量
	 $list = $_page->getlist();
	 $page = $_page->getpage('lxkt');
	 $list = Tag::sql_select($list);
	 
	 $action = 'get_products';
	 require tpl('content/cats');
	 
  break;
  
   case 'get_products':
     $catid = gpc('catid')+0;
	 $pagesn= empty($categorys[$catid]['pagesize'])?16:$categorys[$catid]['pagesize'];
     $_page = new Page("select * from article where catid IN($catid) order by id desc",$pagesn);
	 
	 // 可用变量
	 $list = $_page->getlist();
	 $page = $_page->getpage('lxkt');
	 $list = Tag::sql_select($list);
	 
	 require tpl('content/cats');
	 
  break;
  	
}
