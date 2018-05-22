<?php
   
/**
 * CWCMS  后台核心文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: admin.inc.php 202 2015-12-10 16:29:08Z Charntent $
 */


 
 
define("ADMIN",dirname(__FILE__));
define("ADMINDIR", '/admin');


define("VERSION", 'WLCMS6.0');


defined('title') || define("title", $setting['sitename'] );
defined('keywords') || define("keywords", $setting['keywords'] );
defined('description') || define("description", $setting['description'] );

if(!isset($_SESSION['adminUI'])) $_SESSION['adminUI'] = 'default';
if(!isset($_SESSION['adminlang'])) $_SESSION['adminlang'] = CLANG;




$_SESSION['adminUI'] = 'default';
define("TPLSTYLE", $_SESSION['adminUI']);
define("LANG", $_SESSION['adminlang']);

if( (!isset($checklogin) || $checklogin != false) && !isset($_SESSION['admin_islogin']) ){
	header("Location: ".U($m.'/index'));	
	exit;
}

//网站用户组
$User_groups = array();
$User_groups_ar = $db->tb('usergroup')->all();
foreach($User_groups_ar as $k=>$v){
	$User_groups[$v['id']] = $v['varname'];
}

require 'langs.php';
$_SESSION['debug'] = $debug;

//皮肤
$adminui = array("ui4"=>"6.0版本","ctcms"=>"ctcms（待开发）");

//栏目组
$cats_groups = array("article"=>"文章组","product"=>"产品组",'page'=>'单页组','diy'=>'自定义组','diy1'=>'自定义1','diy2'=>'自定义2','diy3'=>'自定义3','diy4'=>'自定义4');




function fetchZiDianModel($categoryid,$layer,$s='',&$data='',$wz=0)
	{  global $db;
	   
		$rs =  $db->select(" select * from category where parentid='$categoryid' AND id>0    and lang='".LANG."' order by weight asc,id asc");
		if ($categoryid != "0"){
				$layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
		}
		foreach($rs as $r)
		{
			$span ="";
			$str="";
			  if ($categoryid != '0')
					{
	
						for ($i = 0; $i < $layer; $i++)//如果i=0，显示的时候第一级菜单就少了个空格
						{
							$span .= "　";
						}
						$span.= "┠";//添加前面的空格   
					}
			$isable = '';
			if($wz==1){
				if($r['cattype'] != 'article' || $r['isend']==1) 
				   $isable = 'disabled';
				
			}
			if($s == $r["id"])
			   $str.="<option value='{$r['id']}' selected='selected' $isable>".$span.$r["catname"]."</option>";	
			else
		       $str.="<option value='{$r['id']}' $isable>".$span.$r["catname"]."</option>";	
		
		    $data .= $str;
			fetchZiDianModel($r["id"],$layer,$s,$data,$wz);
		}
		return $data;
}
$allsons = setCache_Byson();
function getAllsonByDiGui($catid=0,$layer=0,$s=''){
	 global $allsons,$m;
	  if ($catid != "0"){
		 $layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
	 }
	 if(isset($allsons[$catid]))
	 foreach($allsons[$catid] as $k=>$v){
		 $span ="";
		 if ($catid != '0'){
			for ($i = 0; $i < $layer; $i++){//如果i=0，显示的时候第一级菜单就少了个空格
			    $span .="&nbsp;&nbsp;";
			}
			//$span.= "┠";//添加前面的空格   
			$span.= "";
		}
	    
		$all_sons = implode(",",get_all_sonids_admin($v["id"]));
		
		$sons = implode(",",get_sonids($v["id"]));
		
	    if($s == $v["id"]){
		    echo "<li id='wf_cat_".$v['id']."' data-cid='".$v['id']."' data-allsons='".$all_sons."' data-sons='".$sons."'><a href='admin?action=sub&catid=".$v['id']."' target='main'>".$span."<span class='glyphicon ".$v["icon"]."' aria-hidden='true'></span>".$v["catname"]."</a></li>";
		}else{
			echo "<li  id='wf_cat_".$v['id']."'  data-cid='".$v['id']."'  data-allsons='".$all_sons."' data-sons='".$sons."'><a href='admin?action=sub&catid=".$v['id']."' target='main'>".$span."<span class='glyphicon ".($v["icon"]?$v["icon"]:'glyphicon-map-marker')."' aria-hidden='true'></span>".$v["catname"]."</a></li>";
		}
		getAllsonByDiGui($v['id'],$layer,$s);
	 }
}


$catgroupid = gpc('catgroupid');
$langstr = '';
if(!$catgroupid){
  	$langstr = " and lang='".LANG."' ";
}
$allcatgorys = $db->select(" select * from category  where 1  $langstr  and id>0 order by weight asc,id asc");
function get_all_sonids_admin($id,&$ids=array()){
	global $allcatgorys;
    $rs = array();
	foreach($allcatgorys as $erji){
		if($erji["parentid"] == $id)
		   $rs[$erji["id"]] = $erji ;
	}
	foreach($rs as $r){
		if($r['parentid']==$id){
			$ids[] = $r['id'];
		}
		get_all_sonids_admin($r['id'],$ids);
	}
	return $ids;	
}

function get_all_sonids($id,&$ids=array()){
	global $allcatgorys;
    $rs = array();
	foreach($allcatgorys as $erji){
		if($erji["parentid"] == $id)
		   $rs[$erji["id"]] = $erji ;
	}
	foreach($rs as $r){
		if($r['parentid']==$id){
			$ids[] = $r['id'];
		}
		get_all_sonids($r['id'],$ids);
	}
	return $ids;	
}
function getRegion($rname,$t=0){
	 global $region;
	if(isset($region[$t][$rname]))
	   return $region[$t][$rname]['name'];
	else
	return '未知';
}


function fetchcateggory($categoryid,$layer,$s='',&$data='')
	{  global $db,$database_prex; 
	   //三种方法啊！现在下面这种效率最高啊！！
		$rs = $db->select(" select * from ".$database_prex."category where parent_id='{$categoryid}' order by id asc ");
		//$rs = isset($region[$categoryid])?$region[$categoryid]:array();
		
		if ($categoryid != "0"){
				$layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
		}
		foreach($rs as $r)
		{
			$span ="";
			$str="";
			  if ($categoryid != '0')
					{
						for ($i = 0; $i < $layer; $i++)//如果i=0，显示的时候第一级菜单就少了个空格
						{
							$span .= "　";
						}
						//$span.= "┠";//添加前面的空格   
						$span.= "";
					}
			
			if($s == $r["id"])
			   $str.="<option value='{$r['id']}' selected='selected'>".$span.$r["cat_name"]."</option>";	
				else
		       $str.="<option value='{$r['id']}'>".$span.$r["cat_name"]."</option>";	
		
		    $data .= $str;
			fetchcateggory($r["id"],$layer,$s,$data);
		}
		return $data;
}

function getBrand($brand_id=0){
	global $db,$database_prex;
	$rs = $db->select(" select * from ".$database_prex."brand order by brand_id asc ");
	$str="";
	foreach($rs as $k=>$r){
	    echo "<option value='{$r['brand_id']}'".($brand_id==$r['brand_id']?' selected':'').">".$r["brand_name"]."</option>";		
	}
}


function make_home_page(){
	global $setting, $categorys;
	extract($GLOBALS,EXTR_SKIP);
	$GLOBALS['is_user_tpl'] = true;
	$GLOBALS['ignore_html_output'] = true;
	filter_makehtml_avg();
	set_gpc('makehtml',1);
	ob_start();
	header("Content-type: text/html; charset=utf-8");
	require WL_ROOT.DS.'index.php';
	$output = ob_get_contents();
	ob_end_clean();
	return make_html($output,WL_ROOT.DS.'index.html','生成首页失败！');
	//file_put_contents(WL_ROOT.DS.'index.html',$output);
}

function make_html($data,$url,$title){
	$fp = fopen($url,"wb")  or die($title);
	fwrite($fp,$data);
	fclose($fp);
	return true;
}

function make_list_html($_list_id){
	global $setting, $categorys, $list_html_rule, $sitepath;
	extract($GLOBALS,EXTR_SKIP);
	$GLOBALS['is_user_tpl'] = true;
	$GLOBALS['ignore_html_output'] = true;
	filter_makehtml_avg();
	$_catinfo = $db->find("select cattype,pagesize,dir,cat_url from category where id='$_list_id' AND template!=''");
	if(!$_catinfo) return false;
	$_pagenum = 0;
	if($_catinfo['cattype']=='article'){
		set_gpc('catid',$_list_id);
		if($_catinfo['pagesize']){
			$_artcount = $db->getfield("select count(id) as num from article where catid='$_list_id'");
			$_pagenum = ceil($_artcount/$_catinfo['pagesize']);
			$_pagenum = $_pagenum<0?0:$_pagenum;
		}
	}else{
		set_gpc('pageid',$_list_id);
	}
	for($i=0;$i<=$_pagenum;$i++){
		set_gpc('page',$i);
		set_gpc('makehtml',1);
		ob_start();
		header("Content-type: text/html; charset=utf-8");
		require WL_ROOT.DS.'index.php';
		
		$__id = $i?'_'.$i:'';
		
		$html_forder_this = $html_forder;
		if($html_forder != ''){
			$html_forder_this = $html_forder.DSS;	
		}
		
		$file = WL_ROOT.DS.$html_forder_this.$_catinfo['cat_url'].$__id.'.html';
		$path = dirname($file);
		is_dir($path) || mkdir($path,0777,true);
		$output = ob_get_contents();
		ob_end_clean();
		file_put_contents($file,$output);
	}
}


function make_article_html($_article_id,$num=20){
		global $setting, $categorys, $article_html_rule, $sitepath;
		extract($GLOBALS,EXTR_SKIP);
		$GLOBALS['is_user_tpl'] = true;
		$GLOBALS['ignore_html_output'] = true;
		filter_makehtml_avg();
		
		$_articleinfo = $db->select("select id,catid from article where id>='$_article_id' order by id asc limit $num");
		if(empty($_articleinfo)){
			return false;
		}
		foreach($_articleinfo as $_article_one){
			set_gpc('aid',$_article_one['id']);
			set_gpc('makehtml',1);
			ob_start();
			header("Content-type: text/html; charset=utf-8");
			require WL_ROOT.DS.'index.php';
		//	$file = WL_ROOT.DS.str_replace(array('{id}'),array($_article_one['id']),$article_html_rule);
			
			$html_forder_this = $html_forder;
			if($html_forder != ''){
				$html_forder_this = $html_forder.DSS;	
			}
			
			$url = convert_aid_to_url($_article_one['id'],$_article_one['catid']);
			$url = str_replace($web_local.DSS,'',$url);
			$url = str_replace('.html','',$url);
			
			$file = WL_ROOT.DS.$url.'.html';
			
			$path = dirname($file);
			is_dir($path) || mkdir($path,0777,true);
			$output = ob_get_contents();
			ob_end_clean();
			file_put_contents($file,$output);
			
		}
		return $_article_one['id']+1;
	}


function filter_makehtml_avg(){
	$_SERVER["REQUEST_URI"] = $_SERVER["PHP_SELF"] = "index.php";
	$_gp = array_merge($_GET, $_POST);
	
	foreach($_gp as $_k => $_v){
		set_gpc($_k,null);
	}
}
function lang($id){
    global $lang;
	if(isset($lang[$id])){
		return $lang[$id];
	}else{
	    return false;	
	}
}

function status($s){
	global $lang;
	$status = $lang['status0'];
	if($s) $status = $lang['status1'];
	return $status;
}

$viewar = array();

//自动提交到百度
function urlToBaidu($urls,$token){
    $site = parse_url(BASEURL);
	$api = 'http://data.zz.baidu.com/urls?site='.$site['host'].'&token='.$token;
	$ch = curl_init();
	$options =  array(
		CURLOPT_URL => $api,
		CURLOPT_POST => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POSTFIELDS => implode("\n", $urls),
		CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
	);
	curl_setopt_array($ch, $options);
	$result = curl_exec($ch);
	return json_decode($result,true);
}
