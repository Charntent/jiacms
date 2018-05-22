<?php

/**
 * CWCMS  首页控制器文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: module/index.php 252 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

$catid = 0;
$parentid = 0;
$position = get_position($catid);
$stype = 'PC';
if(is_mobile()  || $fw_type =='mb'){
	defined('title') || define("title", ($setting['mindextitle']?$setting['mindextitle']:$setting['msitename']));
	defined('keywords') || define("keywords", $setting['mkeywords'] );
	defined('description') || define("description", $setting['mdescription'] );
	$stype = 'mobile';
}else{
	defined('title') || define("title", ($setting['indextitle']?$setting['indextitle']:$setting['sitename']));
	defined('keywords') || define("keywords", $setting['keywords'] );
	defined('description') || define("description", $setting['description'] );
}

$template = get_tpl_name($catid);

$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);
$sonidarr = get_sonids($catid);
$sonids = join(',',$sonidarr);

//幻灯片
$sliders = getSlider($stype,'index');
if(!empty($template)){
	require tpl('content/'.$template);
}else{
	exit("模板未设置");
}
