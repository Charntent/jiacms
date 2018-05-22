<?php

/**
 * CWCMS  首页入口文件
 * ============================================================================
 * * 版权所有 2014-2017 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Index.php 252 2017-04-11 16:29:08Z Charntent $
 */

define("ROOT_PATH",dirname(__FILE__));
define("Core_ROOT",substr(ROOT_PATH,0,-6));
define("WL_ROOT",Core_ROOT);
define("ADMINURL",'admin');
$cms = defined("NOCMS")?false:true;
/*
* $m 是应用模块,$c是控制器,$a是动作
*/
$m = "content";
$c = $a = $catid = $aid = null;

/*
*  默认首页的入口文件
*/
$defaultCtl = 'index.php';
/*
*  加载核心文件
*/
require_once WL_ROOT.'/jiaphp/lib/Wlcms.php';