<?php

/**
 * CWCMS  后台绑定文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:bind.php 202 2017-03-08 13:21:08Z Charntent $
 */

require 'admin.inc.php';
$catid = gpc('catid');
if($catid != 136){
	alert('请到PC案例那里绑定');
}
switch($a){
	
	case 'getArc':
	     $pcatid = gpc('pcatid');
		 $pcaid = gpc('pcaid');
		 $zd = '';
		  if($pcatid == 138){//app
		        $zd = 'appid';
		   }
		   if($pcatid == 139){//手机
		        $zd = 'mobileid';
		   }
		   if($pcatid == 522){//微信
		       $zd = 'wxid';
		   }
		 $rs = $db->select("select * FROM article where catid='$pcatid' and id NOT IN(select $zd from binds WHERE pcid!='$pcaid')");
		 $str = '';
		 foreach($rs as $k=>$v){
			$str .= '<option value="'.$v['id'].'">'.$v['title'].'</option>';
		 }
		 message('成功',$str,0,0);
	break;
	
	case 'bindok':
	       $pcatid = gpc('pcatid');
		   $pcaid = gpc('pcaid');
		   $maid = gpc('maid');
		   if($pcatid == 138){//app
		       $db->query("update binds set appid='$maid' where pcid='$pcaid'");
		   }
		   if($pcatid == 139){//手机
		       $db->query("update binds set  mobileid='$maid'  where pcid='$pcaid'");
		   }
		   if($pcatid == 522){//微信
		       $db->query("update binds set  wxid='$maid'  where pcid='$pcaid'");
		   }
		   message('绑定成功');
	break;
	
	case  'index':
	    
		$aid = gpc('aid');
		
		if($catid == 136){
			
			$info_pc = $db->find("select id,catid,title from article where id='$aid' ");
			$info_mobile = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.mobileid=a.id where pcid='$aid' ");
			$info_weixin = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.wxid=a.id where pcid='$aid' ");
			$info_app = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.appid=a.id where pcid='$aid' ");
		}
		
		if(!$db->find("select * from  binds where pcid='$aid' ")){
		    $db->t('binds')->AddData(array('pcid'=>$aid));	
		}
		
		
		/*if($catid == 138){
			$info_app = $db->find("select id,catid,title from article where id='$aid' ");
			$info_mobile = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.mobileid=a.id where appid='$aid' ");
			$info_weixin = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.wxid=a.id where appid='$aid' ");
			$info_pc = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.pcid=a.id where appid='$aid' ");
		}
		if($catid == 139){
			$info_mobile = $db->find("select id,catid,title from article where id='$aid' ");
			$info_pc = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.pcid=a.id where mobileid='$aid' ");
			$info_weixin = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.wxid=a.id where mobileid='$aid' ");
			$info_app = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.appid=a.id where mobileid='$aid' ");
		}
		if($catid == 522){
			$info_weixin = $db->find("select id,catid,title from article where id='$aid' ");
			$info_mobile = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.mobileid=a.id where wxid='$aid' ");
			$info_pc = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.pcid=a.id where wxid='$aid' ");
			$info_app = $db->find("select a.id,a.catid,a.title from article a left join binds b ON b.appid=a.id where wxid='$aid' ");
		}*/
		require tpl('bind/index');
		
	break;
}