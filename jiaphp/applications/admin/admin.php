<?php

/**
 * CWCMS  后台admin文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: admin.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$action = gpc('action');

$uid = $_SESSION['admin_nid'];
$me = $db->find("select * from admin where id='$uid'");

$func = 'admin';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	case 'list':
		$level = $me['level'];
		$condition = "";
	
		$list = $db->select("select * from admin $condition order by level asc,id asc");
		include tpl("admin/admin");
	break;

	case 'deletefile':

	    $filename = gpc("filename");
	    if(!$filename){
			echo json_encode(array('error'=>12));
			exit();
		}
		if(strpos($filename,"fileico")){
			echo json_encode(array('error'=>11));
			exit();
		}
	
		if(file_exists($filename) || file_exists("../".$filename)){
				if(@unlink($filename) || @unlink("../".$filename)){
				 
				 //删除成功后	
				 $file_end = explode('/',$filename);
				 $atta_name = end($file_end);
				 $db->query("delete from attachments WHERE title='$atta_name' ");
			     echo json_encode(array('error'=>0));
				 exit();
			}else{
				 echo json_encode(array('error'=>1));
				 exit();
			}
		}else{
			 echo json_encode(array('error'=>10));
			 exit();
		}
	break;
	case 'add':
		$submit = gpc('submit');
		if(empty($submit)){
			require tpl("admin/admin");
		}else{
			$username = gpc('username');
			$password=gpc('password');
			$password2=gpc('password2');
			$level = gpc('level');
			if(empty($username) || !preg_match('/^[a-z0-9_]+$/',$username)){
				alert("用户名必须为字母数字或下划线！","back");
			}
			if(empty($password)){
				alert("密码不能为空！","back");
			}
			if($password!=$password2){
				alert("两次输入的密码不一致！","back");
			}
			$password = md5($password);
			$db->query("insert into admin (username,password,level) values ('$username','$password','$level') ");
			alert("添加成功！",U($m.'/admin')."?action=list");
		}
	break;
	case "ui":

	   $uival = gpc('uival');
	   if($uival != TPLSTYLE){
		   $i = 1;
		   $_SESSION['adminUI'] = $uival;
		   if($i){
			  echo json_encode(array('status'=>1));
			  exit();
		   }else{
			  echo json_encode(array('status'=>0));
			  exit();
		   }
	   }else{
		   echo json_encode(array('status'=>0));
		   exit();
	   }
	break;

	case "lang":

	   $uival = gpc('uival');
	   if($uival != LANG){
		   $i = 1;
		   $_SESSION['adminlang'] = $uival;
		   if($i){
			  echo json_encode(array('status'=>1));
			  exit();
		   }else{
			  echo json_encode(array('status'=>0));
			  exit();
		   }
	   }else{
		   echo json_encode(array('status'=>0));
		   exit();
	   }
	break;

	case 'edit':
		$submit = gpc('submit');
		$id = gpc('id');
		if($submit){
			$level = gpc('level');
			$password=gpc('password');
			if(!empty($password)){
				$password2=gpc('password2');
				if($password!=$password2){
					alert('两次输入密码不一致,请重新输入!');
				}
				$password=md5($password);
				$db->query("update admin set password='$password' where id='$id'");
			}
			if($level){
				$db->query("update admin set level='$level' where id='$id'");
			}
			alert('提交成功',U($m.'/admin')."?action=list");
		}else{
			$r = $db->find("select * from admin where id=$id");
			include tpl('admin/admin');
		}
	break;
	case 'del':
		$id = gpc('id');
		$db->query("delete from admin where id='$id'");
		message("删除成功！",'',1000,0);
	break;
	case 'sub':
		$catid = gpc("catid");
		$r = $db->find("select * from category where id='$catid'");
		$sub = $db->select("select  id,catname,phpscript ,cattype,menugroup from category where parentid='{$r['id']}'  and lang='".LANG."' and  menugroup!=-1  order by weight asc,id asc");
		
		$cat = array();

		if($r['id']<0 || $r['cattype'] == 'article' ||  $r['cattype'] == 'page') {
			 $cat[] = $r;
		}elseif($r['cattype'] == 'diypage'){
			$phpscript = explode('|',$r['phpscript']);
			if($phpscript[0])
		       $cat[] = $r;
		}
		//查看如果有下级的话就不显示同级
		foreach($sub as $rs){
			$cat[] = $rs;
		}
		if(!$sub){
			$tjsub = array();
			if($r['parentid']!=0)
			//查找同级
			$tjsub = $db->select("select  id,catname,phpscript,icon,cattype,menugroup,title,thumb,keywords,description,content,fields from category where parentid='{$r['parentid']}'   and lang='".LANG."' and  menugroup!=-1   order by weight asc,id asc");
			foreach($tjsub as $rs){
				if($rs['id'] == $catid){unset($cat[0]);}
				$cat[] = $rs;
			}
		}
		
		require tpl("admin/subiframe");
	break;
	case "homepage":
		require tpl("admin/homepage");
	break;
	default:
		$me = $db->find("select * from admin where id='$uid'");
	    $category = $db->select("select * from category where parentid=0 and  (lang='al' OR lang='".LANG."') order by weight asc,id asc");
		
		$group = require WL_STATIC.DS.'config'.DS.'menugroup.php';

		require tpl('admin/index');
}

?>
