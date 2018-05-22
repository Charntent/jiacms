<?php

/**
 * CWCMS  开始文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Wl-load.php 202 2015-12-10 16:29:08Z Charntent $
 */

 
define("IN_SYS",true);
if(!defined('DS')) define('DS',DIRECTORY_SEPARATOR);
if(!defined('DSS')) define('DSS',"/");

require_once WL_ROOT.DS."config.php";


define("WL_WLCMS",dirname(__FILE__));
define("WL_CONTENT",WL_ROOT.DS."runtimes");
define("WL_TEMPLATE",ROOT_PATH.DS."templates");
define("CORE_ROOT",substr(WL_WLCMS,0,-3));
define("WL_COMMON_MODULES",CORE_ROOT.DS."applications");
define("WL_EXTEND_MODULES",WL_ROOT.DS."apps");

define("WL_DATA",WL_CONTENT);

define("WL_CACHEROOT",WL_DATA.DS."Cache".DS.$dqxm.DS);
define("WL_STATIC",ROOT_PATH.DS.'resources');
define("HMCDN",'https://static.huimingcn.com/v2.0/webapp');

/*
开启缓存路径
*/

if(!is_dir(WL_DATA.'/Cache/'.$dqxm))
   mkdir(WL_DATA.'/Cache/'.$dqxm,0777,true);
if(!is_dir(WL_DATA.'/Session/'.$dqxm))
   mkdir(WL_DATA.'/Session/'.$dqxm,0777,true);
if(!is_dir(WL_DATA.'/Tplcache/'.$dqxm)){
	mkdir(WL_DATA.'/Tplcache/'.$dqxm,0777,true);
}

/*
*备份数据
*/
if(!is_dir(WL_DATA.'/Backup')){
	mkdir(WL_DATA.'/Backup',0777,true);
}
/*设置系统URL*/
$_CONFIG['message_tpl'] = WL_STATIC.DS.'message';
$_CONFIG['message404_tpl'] =  WL_STATIC.DS.'message404';
// 伪静态
$rewrite = false;
$ignore_ob_start = false;
// 生成静态
$is_html = false;
$list_html_rule = "html/list/{id}.html";
$article_html_rule = "html/content/{id}.html";


//安装路径，一级目录的话就不用了，二级的要
$sitepath = "";//安装目录

set_include_path(WL_WLCMS.PATH_SEPARATOR.get_include_path());
function_exists('date_default_timezone_set') && date_default_timezone_set('PRC');
header("Content-type: text/html; charset=utf-8");
session_save_path(WL_DATA.DS."Session".DS.$dqxm);
session_cache_limiter( 'private, must-revalidate' );
defined('BASEURL') or define('BASEURL',$web_local.($sitepath==""?"":DSS.$sitepath));
define('BURL',BASEURL);
define("WL_PUBLIC",BASEURL."/templates/");
define("_STATIC",BASEURL.DSS."resources");