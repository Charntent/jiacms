<?php

/**
 * CWCMS  文章内容页控制器文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: module/view.php 202 2016-06-30 160:07:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

$r = $db->find("select * from article where id='$aid' and lang='".CLANG."'");

if(empty($r)) message_404("文章不存在！",BASEURL);
$db->query("update article set click=click+1,realclick=realclick+1 where id='$aid'");
extract($r,EXTR_SKIP);

if(!empty($fields)){
	extract( (array) unserialize($fields),EXTR_SKIP);
}
//如果是手机版本
if(is_mobile()){
    $content = replace_img($content);
}
$description = (CLANG=='english'?$description:trimall($description));
$title = (CLANG=='english'?$title:trimall($title));
//看看是否分页
$ipage = gpc('ipage')?gpc('ipage'):0;
$contents = explode("<hr>",$content);
if(isset($contents[$ipage])){
	$content = $contents[$ipage];
}
$ipage_str = '';
if(count($contents)>1){
	for($i=0;$i<count($contents);$i++)
	 $ipage_str .= '<li class="'.($ipage==$i?'acitve':'').'"><a href="?ipage='.$i.'">第'.($i+1).'页</a></li>';
}

// 可用变量 以及 article 表所有字段
$catid = $r['catid'];
$parentid = $categorys[$catid]['parentid'];
$catname = $categorys[$catid]['catname'];
$subtype = $categorys[$catid]['subtype'];
$position = get_position($catid,$title);

$catnames = get_catname($catid,$title);

if(is_mobile()  || $fw_type =='mb'){
    defined('title') || define("title", $title.'_'.$catnames.'_'.$setting['msitename']);
	defined('keywords') || define("keywords", empty($keywords)?$setting['mkeywords']:$keywords );
	defined('description') || define("description", empty($description)?$setting['mdescription']:$description );
}else{
	
	defined('title') || define("title", $title.'_'.$catnames.'_'.$setting['sitename']);
	defined('keywords') || define("keywords", empty($keywords)?$setting['keywords']:$keywords );
	defined('description') || define("description", empty($description)?$setting['description']:$description );
}

$laytpl  = (isset($laytpl) && $laytpl!='')?$laytpl:'';
$template = $laytpl?$laytpl:get_tpl_name($catid,1);

$nav = get_nav($catid);
$subnav = get_nav($catid,$catid);
$sonidarr = get_sonids($catid);
$sonids = join(',',$sonidarr);

$prev = $db->find('select * from article where catid='.$catid." and id <".$aid." order by id desc limit 1");

$next = $db->find('select * from article where catid='.$catid." and id >".$aid." order by id asc limit 1");

if(!isset($wl_lang['prev_arc'])){
	$wl_lang['prev_arc'] = '上一篇';
}
if(!isset($wl_lang['next_arc'])){
	$wl_lang['next_arc'] = '下一篇';
}

if(!isset($wl_lang['return_his'])){
	$wl_lang['return_his'] = '返回列表';
}

if($prev) $prev = "<li class='previous'>".$wl_lang['prev_arc']."：<a href='".U_aid($prev['id'],$catid)."' title='".$prev['title']."'>".$prev['title']."</a></li>";
else  $prev ="<li class='previous'>".$wl_lang['prev_arc']."：<a href='".U_catid($catid)."'>".$wl_lang['return_his']."</a></li>";
if($next) $next = "<li class='next'>".$wl_lang['next_arc']."：<a href='".U_aid($next['id'],$catid)."' title='".$next['title']."'>".$next['title']."</a></li>";
else  $next ="<li class='next'>".$wl_lang['next_arc']."：<a href='".U_catid($catid)."'>".$wl_lang['return_his']."</a></li>";

$nextpage = $prev.$next;

//推荐文章
if($tags==''){
     $rec_articles = $db->t('article')->where(" catid = '$catid' ")->get(10);	 
}else{
	 
	$tags = trim(',',$tags);
	 
	$tagss =  explode(',',$tags);
	$strs = '';
	foreach($tagss as $k=>$v){
		 $strs .= " or tags like '%,$v,%' ";
	}
	 
	 $rec_articles = $db->t('article')->where(" 1=1 and lang='".CLANG."' $strs ")->get(10);	 
 }

$rec_articles = Tag::sql_select($rec_articles);
//顶级topID
$topid = get_topid($catid,$categorys);

if(!empty($template)){
	require tpl('content/'.$template);
}else{
	exit("模板未设置");
}
