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

$keyword = gpc('keyword');
defined('title') || define("title", $keyword.'_查询结果_'.$setting['sitename'] );

defined('keywords') || define("keywords",  $keyword.','.$setting['keywords'] );
defined('description') || define("description", $setting['description'] );
$catid  = 0;
$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);
$position = get_position($catid);

if(!$keyword) message('您访问页面不存在！');
switch($a){
	
	
	default:
	    
		$_page = new Page(" SELECT * FROM `article` WHERE  `title` LIKE '%$keyword%' OR  `description` LIKE '%$keyword%' OR `content` LIKE '%$keyword%'  order by id desc ",20);
		
		// 可用变量
		$list = $_page->getlist();
		$list = Tag::sql_select($list);
		$template = 'search';
		$page = $_page->getpage();
		if(!empty($template)){
			require tpl('content/'.$template);
		}else{
			exit("模板未设置");
		}
		
		
	break;
}