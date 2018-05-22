<?php

/**
 * CWCMS  后台首页文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Index.php 202 2015-12-10 16:29:08Z Charntent $
 */

$checklogin = false;

require 'admin.inc.php';

$action = gpc('action');

switch($action){
	case 'login':
		$username = gpc('username');
		$password = gpc('password');
		$password = md5($password);
		if (!preg_match('/^[a-z0-9_]+$/',$username)) {
			message('用户名必须为字母数字或下划线','back');
		}
		$checkcode = gpc('checkcode');
		
		if ( empty($_SESSION['checkcode']) || strtolower($checkcode)!=strtolower($_SESSION['checkcode']) ) {
			message('验证码错误！','back');
		}
		$sql = "select * from admin where username='$username' and password='$password'";
		$row = $db->find($sql);
		if ($row) {
			$_SESSION['admin_islogin'] = 1;
			$_SESSION['admin_nid'] = $row['id'];
			$_SESSION['admin_username'] = $row['username'];
			$_SESSION['admin_level'] = $row['level'];
			$grant = $db->tb("usergroup")->where(" id='".$row['level']."' ")->FindData('purview');
			session('userview',unserialize($grant['purview']));
			message('登录成功',U($m.'/admin'),1000,1,'success',1);
		} else {
			message('密码错误','back');
		}
	break;
	case 'out':
		unset($_SESSION['admin_islogin']);
		unset($_SESSION['admin_nid']);
		unset($_SESSION['admin_username']);
		message('退出成功',U($m.'/index'),1000,1,'success',1);
	break;
	
	
	default:
	   if ( isset($_SESSION['admin_islogin']) && $_SESSION['admin_islogin']==1 ) {
			message('您已经登录！',U($m.'/admin'));
	   } else {
		     
			//随机加载登录页面
		    $loginStype = array('login','cloud');
			$loginShift = rand(0,1);
			require tpl('index/'.$loginStype[$loginShift]);
	   }
}

?>