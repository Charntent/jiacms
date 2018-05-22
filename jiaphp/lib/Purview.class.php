<?php

/**
 * CWCMS  权限管理文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Purview.class.php 202 2016-06-03 16:29:08Z Charntent $
 */
class Purview{
	
	public function getpurviewar($func){
		 $userviews = session('userview');
		 $per_ar = array();
		 if(isset($userviews[$func]) && is_array($userviews[$func])){
			 foreach($userviews[$func] as $k=>$v){
				 $per_ar[] = $k;
			 }
		 }
		 return $per_ar; 
	}
	/*
	  **$func 方法
	  **$usergroup 用户组
	  判断这个用户组到底有没有这样的功能
	*/
	public function checkpurview($func,$do){
	    $userviews = session('userview');
	
		if(!$do) $do = 'list';
		if($userviews){
			if(isset($userviews[$func][$do]) && $userviews[$func][$do] == 1){
			    return TRUE;	
			}else{
				return FALSE;
			}
		}else{
		    return FALSE;
		}
	}
	
	
	
}