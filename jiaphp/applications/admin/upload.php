<?php

/**
 * CWCMS  后台上传文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:upload.php 202 2015-12-10 16:29:08Z Charntent $
 */
$checklogin = false;



require 'admin.inc.php';

$fw_type = 'pc'; 
$dqlang = LANG;
//dump(LANG);
$lang = gpc('lang');

if($lang != $dqlang){
	exit('请刷新页面，语言包已经切换了！'.$lang.$dqlang);
}

//$temp = $wlcms_templates[$fw_type.'temp'][$dqlang];
defined('TMLSTYLE') || define("TMLSTYLE", $dqxm);

$up = new Upload();
$file = $up->autoupload();

$CKEditorFuncNum = gpc('CKEditorFuncNum');
$WangEditor = gpc('WangEditor');

if($CKEditorFuncNum){
	if(S('imgaddwebsite')=='是'){
	      $file_url = BASEURL.'/'.$file['upload'];
	}else{
	    $qz =  get_qz();
		$file_url = $qz.'/'.$file['upload'];
	}
	$str = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$CKEditorFuncNum.', \''.$file_url.'\', \''."上传成功".'\');</script>';
	echo $str;
	exit();
}

if($WangEditor){
	if(S('imgaddwebsite')=='是'){
	      $file_url = BASEURL.'/'.$file['file'];
	}else{
	    $qz =  get_qz();
		$file_url = $qz.'/'.$file['file'];
	}
	
	echo $file_url;
	exit();
}
if(isset($file['Filedata']) && $file['Filedata']!=false){
	echo 'FILEID:'.$file['Filedata'];
}else{
	echo "上传错误";	
}



?>