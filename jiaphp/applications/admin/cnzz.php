<?php

/**
 * CWCMS  网站统计文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: cnzz.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$action = gpc('action');

$_file = WL_DATA.DS.'cnzz.php';

switch($action){
	
	case "apply":
	   
		$domain = $_SERVER['HTTP_HOST'];
		$key = md5($domain.'F0dkYYtw');
		$surl = 'http://wss.cnzz.com/user/companion/phpcms.php';
		
		if ($data = @file_get_contents($surl.'?domain='.$domain.'&key='.$key.'&cms=wccms'))
		{
			if(!strstr($data,'@'))
			{
				if($data=='-1'){
					alert("KEY值有误");
				}elseif($data=='-2'){
					alert("域名长度有误（1~64）");
				}elseif($data=='-3'){
					alert("域名输入有误");
				}elseif($data=='-4'){
					alert("域名插入数据库有误");
				}elseif($data=='-5'){
					alert("同一个IP,用户申请次数过多,请稍后再试");
				}else{
					echo $data;
				}
				exit;
			}else
			{
				list($list['siteid'],$list['password']) = explode('@', $data);
				$data = var_export($list,true);
				file_put_contents($_file,"<"."?php\r\nreturn ".$data."\r\n?".">");
				alert("申请成功！");
			}
		}
		else
		{
			alert("很抱歉，与cnzz通信失败，无法完成注册，请检查您的服务器是否有远程访问权限");
		}
	break;
	
	default:
		if(!is_file($_file)){
			require tpl('cnzz');
		}else{
			$cnzz = require $_file;
			$surl = 'http://wss.cnzz.com/user/companion/phpcms_login.php';
			$surl .= '?site_id='.$cnzz['siteid'].'&password='.$cnzz['password'];
			if( stripos($_SERVER['HTTP_USER_AGENT'],'Chrome')!==false){
				echo '<script type="text/javascript">window.open("'.$surl.'");</script>';
				echo '<a href="'.$surl.'" target="_blank" style="font-size:12px; color:blue;">如果没有弹出窗口，点击这里</a>';
			}else{
				echo '<script type="text/javascript">window.top.main.location="'.$surl.'";</script>';
			}
		}
	
}

