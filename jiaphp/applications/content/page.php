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

if(!isset($categorys[$pageid])) message_404("栏目不存在！");


if($categorys[$pageid]['cattype']=='article') header("Location: $defaultCtl?catid=".$pageid);

$page = $pageid;
$catid = $pageid;

$r = $db->find("select * from page where id='$page' ");

if(empty($r)) message_404("页面不存在！",'',1520000);

extract($r,EXTR_SKIP);
if(!empty($fields)){
	extract( (array) unserialize($fields),EXTR_SKIP);
}
// 可用变量 以及 page 表所有字段	

$parentid = $categorys[$catid]['parentid'];
$catname = $categorys[$catid]['catname'];
$ename = $categorys[$catid]['ename'];
$position = get_position($catid);

$catnames = get_catname($catid,$catname);
/*if(is_mobile()  || $fw_type =='mb'){
	defined('title') || define("title",(empty($title)?$catname:$title).'_'.$setting['msitename'] );
	defined('keywords') || define("keywords", empty($keywords)?$setting['mkeywords']:$keywords );
	defined('description') || define("description", empty($description)?$setting['mdescription']:$description );
}else{
	defined('title') || define("title",(empty($title)?$catname:$title).'_'.$setting['sitename'] );
	defined('keywords') || define("keywords", empty($keywords)?$setting['keywords']:$keywords );
	defined('description') || define("description", empty($description)?$setting['description']:$description );
}*/
if(is_mobile() || $fw_type =='mb'){
	
	defined('title') || define("title", ($categorys[$catid]['mbtitle']?($categorys[$catid]['mbtitle'].'_'.$catnames):$categorys[$catid]['cattitle'].$catnames).'_'.$setting['msitename'] );
	defined('keywords') || define("keywords",$categorys[$catid]['mbkeywords']?$categorys[$catid]['mbkeywords']:$setting['mkeywords'] );
	defined('description') || define("description", $categorys[$catid]['mbdescription']?$categorys[$catid]['mbdescription']:$setting['mdescription'] );
	
}else{
	
    defined('title') || define("title", ($categorys[$catid]['cattitle']?($categorys[$catid]['cattitle'].'_'.$catnames):$catnames).'_'.$setting['sitename'] );
	defined('keywords') || define("keywords",$categorys[$catid]['keywords1']?$categorys[$catid]['keywords1']:$setting['keywords'] );
	defined('description') || define("description", $categorys[$catid]['description1']?$categorys[$catid]['description1']:$setting['description'] );
	
}
$template = get_tpl_name($catid);

$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);
$sonidarr = get_sonids($catid);
$sonids = join(',',$sonidarr);

if(!empty($template)){
	require tpl('content/'.$template);
}else{
	exit("模板未设置");
}
