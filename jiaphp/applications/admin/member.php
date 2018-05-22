<?php

/**
 * CWCMS  后台用户操作文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: member.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$viewar = $Purview->getpurviewar($func);

$userMgroup = $cache->autoSet("userMgroup");
$mod = gpc("m");
$action = $mod;

$func = 'member';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}

switch($mod)
{
	case 'message':
		$p = new Page("select * from message where receiver='管理员' and isdeleted='0' order by mid desc",20);
		$list = $p->getlist();
		$page = $p->getpage();
		include tpl("member/messagebox");
	break;
	case 'read':
		$id = gpc("id");
		if(empty($id)){
			echo "参数不正确。";
			exit(0);
		};
		$message = $db->find("select title,sender,content,sendtime from message where mid='$id'");
		$db->query("update message set isreaded='1' where mid='$id'");
		$message['sendtime']=date("Y-m-d H:i",$message['sendtime']);
		echo json_encode($message);
	break;
	case 'reply':
		$receiver = mysql_escape_string(gpc("receiver"));
		$content = mysql_escape_string(gpc("content"));
		if(empty($receiver) || empty($content)) alert('内容不完整！','member.php?m=message');
		else
		{
			if(!($res = $db->find("select uid from member where username='$receiver'"))) alert('没有这个用户！','member.php?m=message');
			else {
				$db->query("insert into message (sender, receiver, title, content, sendtime, isreaded, isdeleted) values ('管理员','$receiver','管理员回复','$content','$timestamp','0','0')");
				alert('回复成功！',U($m."/member").'?m=message');
			}
		}
	case "del":
		$id = gpc("id");
		if($res = $db->find("select * from outbox where mid=$id"))
		{
			$db->query("update message set isdeleted='1' where mid='$id'");
		}
		else $db->query("delete from message where mid='$id'");
		message("删除成功！",U($m."/member")."?m=message",1000,0);
	break;
	case 'info':
		$id = gpc("id");
		$user = $db->find("select * from member where uid='$id' or username='$id'");
		if(empty($user)) alert('该用户不存在！',U($m."/member"));
		else require tpl('member/memberinfo');
	break;
	case 'edit':
		$submit = gpc("submit");
		if(empty($submit)){
			$id = gpc("id");
			$user = $db->find("select * from member where uid='$id' or username='$id'");
			if(empty($user)) alert('该用户不存在！',U($m."/member"));
			else require tpl('memberedit');
		}
		else{
			$data = array();
			$db->data($data)->save('member');
			alert('修改成功！',U($m."/member"));
		}
	break;
	
	case 'delu':
	    $uid = gpc('uid');
		$db->t('member')->DeleteData($uid,'uid');
		message('删除成功！');
	break;
	
	default:
		$keyword = gpc("keyword");
		$keyword = mysql_escape_string($keyword);
		$where = '';
		if(!empty($keyword)) $where = " where username='$keyword'";
		$p = new Page("select uid,username,nickname,lastdate,email from member".$where,20);
		$list = $p->getlist();
		$page = $p->getpage();
		include tpl("member/member");
}