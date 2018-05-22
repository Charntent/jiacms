<?php

/**
 * CWCMS  文章列表控制器文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: module/list.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

if(!isset($categorys[$catid])) message_404("栏目不存在！".$catid,BASEURL);

if($categorys[$catid]['cattype']=='page') header("Location: $defaultCtl?pageid=".$catid);

$catid = $categorys[$catid]["id"];

$type = gpc("type");
$addon = empty($type)?"":" and type='$type' ";


$allsons = CA_sonsBycatid($catid);
if($allsons!=''){
	$catid_str = " ( catid='$catid' OR catid IN($allsons) )";
}else{
    $catid_str = " catid='$catid' ";
}


$categorys[$catid]['pagesize'] = empty($categorys[$catid]['pagesize'])?15:$categorys[$catid]['pagesize'];

$_page = new Page(" select * from article where  $catid_str $addon order by is_top desc,weight asc,id desc",$categorys[$catid]['pagesize']);

// 可用变量
$list = $_page->getlist();
$ui_type = 'default';
if(is_mobile()){
	 $ui_type = 'amazeui';
}
$page = $_page->getpage($ui_type);
$parentid = $categorys[$catid]['parentid'];
$catname = $categorys[$catid]['catname'];
$subtype = $categorys[$catid]['subtype'];
$position = get_position($catid);


$catnames = get_catname($catid,$catname);

$list = Tag::sql_select($list);

if(is_mobile()  || $fw_type =='mb'){
	
	defined('title') || define("title", ($categorys[$catid]['mbtitle']?($categorys[$catid]['mbtitle'].'_'.$catnames):$catnames).'_'.$setting['msitename'] );
	defined('keywords') || define("keywords",$categorys[$catid]['mbkeywords']?$categorys[$catid]['mbkeywords']:$setting['mkeywords'] );
	defined('description') || define("description", $categorys[$catid]['mbdescription']?$categorys[$catid]['mbdescription']:$setting['mdescription'] );
	
}else{
	
    defined('title') || define("title", ($categorys[$catid]['cattitle']?($categorys[$catid]['cattitle'].'_'.$catnames):$catnames).'_'.$setting['sitename'] );
	defined('keywords') || define("keywords",$categorys[$catid]['keywords1']?$categorys[$catid]['keywords1']:$setting['keywords'] );
	defined('description') || define("description", $categorys[$catid]['description1']?$categorys[$catid]['description1']:$setting['description'] );
	
}

$template = get_tpl_name($catid,0);

$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);

$sonidarr = get_sonids($catid);


$sonids = join(',',$sonidarr);
//顶级topID
$topid = get_topid($catid,$categorys);


if(!empty($template)){
	require tpl('content/'.$template);
}else{
	exit("模板未设置");
}