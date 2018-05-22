<?php

/**
 * CWCMS  WLCMS核心文件，公用文件，CWCMS，C是Change，W是World，意思是改变世界的CMS
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent <752293795@qq.com>$
 * $Id: WlCMS.php 202 2015-12-10 16:29:08Z Charntent $
 */

//网站配置文件开始
require_once "Wl-Load.php";
require_once 'Functions.inc.php';
require_once 'Wl-Public.php';

$session_id = gpc("session_id");
if($session_id){
	@session_id($session_id);
}
@session_start();
if(empty($ignore_ob_start)) {
	ob_start();
}

$db = new mysqlDbo();
//开始缓存fsd
$Wls_cache = new Cache();

//获取网站模板缓存
$const = 'wlcms_templates';
$wlcms_templates = $Wls_cache->GetDataById('wlcms_templates');
if($wlcms_templates == FALSE){
	$wlcms_templates = $db->find("select value from svalue where sname='$const' ");
	$wlcms_templates = @unserialize($wlcms_templates['value']);
	@$Wls_cache->SaveById('wlcms_templates',$wlcms_templates,86400000);
}


$dqurl = $_SERVER['HTTP_HOST'];
$request_uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']:(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'');//判断地址

if(!session('clang')  || session('clang_url')!=$dqurl){
	/*这里开始查询域名*/
	if(is_mobile()){
		$fw_type = 'mb';
	}else{
		$fw_type = 'pc'; 
	}
	$flaglang = '';
	foreach($wlcms_templates[$fw_type.'url'] as $k=>$v){
		$urls = explode(",",$v);
		if(in_array($dqurl,$urls)){
			$flaglang = $k;
			break;
		}
	}
	if($flaglang == ''){
		 //获取默认的语言
		 $langs = $db->t('langs')->where("isdefault=1")->get(1,'langid');
		 if(!$langs){
			 message_404('请到后台设置默认语言包！'); 
		 }
		 $flaglang = $langs['langid'];
	}
	//电脑访问
	session('clang',$flaglang);
	session('clang_url',$dqurl);
}else {
	$forcelang = gpc('forcelang');
	if($forcelang ) {
		$exlangs = $db->t('langs')->where("langid='$forcelang'")->get(1,'langid');
		if($exlangs){
			session('clang',$forcelang);
		}
	}
}


$loadUrl = Loader::setlangFromUrl();
Filter::input();
define("CLANG",session('clang'));
$langType = CLANG;
if(defined('IS_ADMIN') && session('adminlang')){
	$langType = session('adminlang');
}

$setting = $Wls_cache->GetDataById('config_'.$langType);
if($setting == FALSE){
	$setting_arr = $db->select("select * from svalue where lang='".$langType."' ");
	foreach($setting_arr as $sv){
		$setting[$sv['sname']] = $sv['value'];	
	}
	@$Wls_cache->SaveById('config_'.$langType,$setting,86400000);
	unset($setting_arr);
}

//语言缓存
$langs = $Wls_cache->GetDataById('langs');
if($langs == FALSE){
	$langs_arr = $db->select(" select * from langs ");
	foreach($langs_arr as $sv){
		$langs[$sv['langid']] = $sv;	
	}
	@$Wls_cache->SaveById('langs',$langs,86400000);
	unset($langs_arr);
}

$debug  = ((isset($setting['debug'])&&$setting['debug']=='是')?true:false);
$ob_gzhandler = ((isset($setting['ob_gzhandler'])&&$setting['ob_gzhandler']=='是')?true:false);
define("IMGADDWEB",((isset($setting['imgaddwebsite']) && $setting['imgaddwebsite']=='是')?true:false));
$erjimulu = trim(str_replace("http://".$_SERVER['HTTP_HOST'],'',$web_local),'/');
define("ERJIMULU",$erjimulu);

/*
*系统自带的语言，默认中文版
*/
require_once 'lang.php';

if ( !defined('IS_ADMIN')) {
	if(S('closeWeb') =='yes') {
		exit($wl_lang['wl_sys_closeweb']);	
	}
}	

$request_uri = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']:(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'');//判断地址后面部分
//这里是核心了
if(!defined('IS_ADMIN') || (isset($checklogin) && $checklogin == false)){
  if(!defined('IS_ADMIN')){
	 if(is_mobile()){
		 $fw_type = 'mb';
	 }else{
		 $fw_type = 'pc'; 
	 }
	//电脑访问
	$dqurl = $_SERVER['HTTP_HOST'];

	if(!isset($wlcms_templates[$fw_type.'url'][CLANG]) || $wlcms_templates[$fw_type.'url'][CLANG]=='' || !isset($wlcms_templates[$fw_type.'temp'][CLANG]) || $wlcms_templates[$fw_type.'temp'][CLANG]==''){
	  die('请在后台设置模板再访问！');	
	}
	$domain = $wlcms_templates[$fw_type.'url'][CLANG];
	if(strpos($wlcms_templates[$fw_type.'url'][CLANG],',')){
		$domain = explode(',',$domain);
		if(!in_array($dqurl, $domain)){
			session('clang_url',$domain[0]);
			redirect('http://'.$domain[0].$request_uri);
			exit();
		}
	}else{
		if($dqurl != $domain){
			session('clang_url',$domain);
			$typem = substr($dqurl,0,1);
			if($typem == 'm'){
				$fw_type = 'mb';
			}else{
				redirect('http://'.$wlcms_templates[$fw_type.'url'][CLANG].$request_uri);
			}
		}	
	}
	
	$templates = $wlcms_templates[$fw_type.'temp'][CLANG];
	defined("TMLSTYLE")  || define("TMLSTYLE",$templates?$templates:$dqxm);
	$thistpl= $templates;
	define("_PUBLIC",BASEURL."/templates/".$thistpl);
	
	//用户自定义的语言
	$langfile = WL_ROOT.DS."templates".DS.$thistpl.DS.session('clang').".php";
	if(file_exists($langfile))
		require_once $langfile;
	}
}
if(isset($debug) && $debug==true){
	error_reporting(E_ALL);	
}else{
	error_reporting(0);	
}


//栏目数据
if(!defined('IS_ADMIN')){
	$ht = CLANG;
}else{
	if(!isset($_SESSION['adminlang'])) $_SESSION['adminlang'] = 'zh_cn';
	$ht = $_SESSION['adminlang'];
}
$categorys = $Wls_cache->GetDataById('_categorys_'.$ht);
if($categorys == FALSE){
	$_categorys = $db->select("select `id`,`parentid`,`catname`,`ename`,`cattype`,`subtype`,`cattitle`,`description1`,`dir`,`ident`,`pic`,`icon`,`template`,`fields`,`phpscript`,`pagesize`,`weight`,`show`,`isend`,`lang`,`cat_url`,`addpare`,`keywords1`,`description1`,`mbtitle`,`mbkeywords`,`mbdescription` from `category` where id>0 and lang='".$ht."'  order by weight asc,id asc");

	$categorys = array();
	foreach($_categorys as $_ckey => $_cval){
		$categorys[$_cval['id']] = $_cval;
		$subtype = array();
		if($_cval['subtype']){
			$subtypes = explode("\r\n",$_cval['subtype']);
			foreach($subtypes as $_key => $_val){
				$_subtype = explode('|',$_val);
				$subtype[$_subtype[0]]['typeid'] = $_subtype[0];
				$subtype[$_subtype[0]]['typename'] = $_subtype[1];
			}
		}
		$categorys[$_cval['id']]['subtype'] = $subtype;
		//if($_cval['dir'])  $categorys[$_cval['dir']] = $_cval;
		//if($_cval['cat_url']) $categorys[str_replace("/",'_',$_cval['cat_url'])] = $_cval;
	}
	//获取顶级栏目的pic
	foreach($categorys as $k=>$v){
		
		if($v['pic']=='' && $v['parentid']!=0){
			$topid = get_topid($v['id'],$categorys);
			$categorys[$k]['pic'] = isset($categorys[$topid]['pic'])?$categorys[$topid]['pic']:'';
		}
	}
	$Wls_cache->SaveById('_categorys_'.$ht,$categorys,86400000);
	unset($_categorys);unset($_subtype);unset($subtype);
}

//WEB_URL
$web_url = $Wls_cache->GetDataById('web_url_'.$ht);
if($web_url == FALSE){
	 foreach($categorys as $k=>$_cval){
		 if($_cval['cat_url']) $web_url[str_replace("/",'_',$_cval['cat_url'])] = array("id"=>$_cval['id'],"cattype"=>$_cval['cattype']);
	 }
	@$Wls_cache->SaveById('web_url_'.$ht,$web_url,8640000);
}

//获取网站多语言对应的缓存
$wlcms_tool_relates = $Wls_cache->GetDataById('wlcms_duiying_'.$ht);
if($wlcms_tool_relates == FALSE){
	 $re_categorys = $db->select("select * from category where id>0 and lang='".$ht."'  order by weight asc,id asc");
	 foreach($re_categorys as $k=>$v){
		 if($v['ident']!='' && !isset($wlcms_tool_relates[$v['ident']]))
			 $wlcms_tool_relates[$v['ident']] = $v['id'];
	 }
	@$Wls_cache->SaveById('wlcms_duiying_'.$ht,$wlcms_tool_relates,86400000);
}


//获取全站的site_id
$domainObj = new DomainsModel();
$site_id = $domainObj->getSiteidByDomain($_SERVER['HTTP_HOST']);


if($site_id === null) {
    require  tpl('error/index');exit();
}
if($domainObj->getSiteStatus($site_id) == 0 ) {
    require  tpl('error/nopay');exit();
}

//静态文件夹
$html_forder = S('html_forder');
if($_SERVER['REQUEST_URI'] == 'index.php'){
    redirect(BASEURL.'/');
}
if (isset($cms)  && $cms === true) {
		
	$mca = Loader::run($m,$loadUrl);
	$url = $loadUrl;
	
	if($url && $url[0]!='' && gpc('makehtml')!=1){
		$m = $mca['m'];
		$c = $mca['c'];
		$a = $mca['a'];
		$catid = $mca['catid'];
		$aid = $mca['aid'];
		$pageid = $mca['pageid'];
		$modfile = WL_COMMON_MODULES.DS.$m.DS.$c.'.php';
		$modfile_extends = WL_EXTEND_MODULES.DS.$m.DS.$c.'.php';
	
		if ($m==ADMINURL ) defined('IS_ADMIN') || define("IS_ADMIN",1);	
		
		if(is_file($modfile_extends)){
			require_once $modfile_extends;
		}elseif(is_file($modfile)){
			require_once $modfile;
		}else{
			message_404('您访问的模块不存在',-1,1000,1,'message_404',1);	
		}
	}else{
		
		$catid = gpc('catid');
		$aid = gpc('aid');
		$pageid = gpc('pageid');
		$m = gpc('m');
		$c = gpc('c');
		$a = gpc('a');
		$c = empty($c)?"index":$c;
		if(!$m){
			$m ='content';
		}
		if($catid && $m != 'admin'){
		  $c = 'list';
		  $catid = isset($categorys[$catid]['id'])?$categorys[$catid]['id']:0;
		}
		if($aid){
		  $c = 'view';
		}
		if($pageid){
		  $c = 'page';
		}
		$modfile = WL_COMMON_MODULES.DS.$m.DS.$c.'.php';
		$modfile_extends = WL_EXTEND_MODULES.DS.$m.DS.$c.'.php';
		if($m==ADMINURL) defined('IS_ADMIN') || define("IS_ADMIN",1);	
		
		if(is_file($modfile_extends)){
			require_once $modfile_extends;
		}elseif(is_file($modfile)){
			require_once $modfile;
		}else{
			message_404('您访问的模块不存在',-1,1000,1,'message_404',1);	
		}
	}
	if($ob_gzhandler) {
		$output = ob_get_contents();
		ob_clean();
		echo $output;
	}
}