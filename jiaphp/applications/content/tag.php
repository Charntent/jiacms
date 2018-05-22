<?php

/**
 * CWCMS  单页控制器文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: module/page.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');
defined('title') || define("title", ($setting['indextitle']?$setting['indextitle']:$setting['sitename']).'_'.$setting['sitename'] );

defined('keywords') || define("keywords", $setting['keywords'] );
defined('description') || define("description", $setting['description'] );
$catid  = 0;
$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);
$position = get_position($catid);
$tagid = gpc('tagid');
if(!$tagid) message('您访问页面不存在！');
switch($a){
	
	
	default:
	    
		$_page = new Page(" SELECT * FROM `article` WHERE  `tags` LIKE '%$tagid%'  order by id desc ",20);
		
		// 可用变量
		$list = $_page->getlist();
		$list = Tag::sql_select($list);
		$page =  $_page->getpage();
		$template = 'list';
		if(!empty($template)){
			require tpl('tag/'.$template);
		}else{
			exit("模板未设置");
		}
		
		
	break;
}