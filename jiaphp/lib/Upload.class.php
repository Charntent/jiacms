<?php

/**
 * CWCMS  图片和文件上传文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Upload.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

class Upload{
    
    public $dir;
    
    public function __construct(){
        $this->dir = ROOT_PATH.DS.'uploads'.DS.TMLSTYLE.DS.date('Ymd');
        if(!is_dir($this->dir)) mkdir($this->dir,0777,true);
    }
    
    public function upload($name){
		global $db;
        $upfile=$_FILES[$name];
		$error_ar = array(
		   1=>'超过了文件大小php.ini中即系统设定的大小'.ini_get('upload_max_filesize').'。',
           2=>'超过了文件大小MAX_FILE_SIZE 选项指定的值。',
           3=>'文件只有部分被上传。',
           4=>'没有文件被上传。',
           5=>'上传文件大小为0。',
		   6=>'权限不够，临时文件夹无法写入！!'
		);
		if(!isset($upfile['tmp_name']) || $upfile['tmp_name']==''){
			return '无法使用上传!原因是：'.$error_ar[$upfile['error']];
		}
        $upfilename = $upfile['name'];
        $fileInfo   = pathinfo($upfilename);
        $extension  = strtolower($fileInfo['extension']);
        if(in_array($extension,array('php','asp','aspx'))) {
			return false;
		}
        $relname = randomkeys(15).date("H-i-s").'.'.$extension;
        $relfile = $this->dir.DS.$relname;
        move_uploaded_file($upfile['tmp_name'],$relfile);
		
		$returnimg = str_replace(DS,'/',str_replace(ROOT_PATH.DS,'',$relfile));
		
		//取得图片大小
		$filesize = filesize($returnimg);
		$isImage = in_array(strtolower($extension),array('jpg','jpeg','png','gif','bmp'));
		if($filesize>=2097152 && $isImage){
			//大于2M的就开始截取
			$imageH = new Image();
			$returnimg = $imageH->equalThumb($returnimg,$returnimg,'',1000,1000);
		}
		
		
		if(S('ifwater') =='是' && S('waterpic') !='' && $isImage){
			$wt = '../'.S('waterpic');
			$img = new Image();
			$img->set_watermark($wt);
			$rr = $img->watermark('../'.$returnimg);
		}
		
		//开始文件管理器,系统只分两种类型
		$fileType = 'file';
		if($isImage) $fileType = 'img';
		$adminId = session('admin_nid');
		$fSize = round($filesize/1024,2);
		$sysImages = array(
		    'user_id'=>$adminId?$adminId:0,
			'type'=>$fileType,
			'name'=>$upfilename,
			'title'=>$relname,
			'url'=>$returnimg,
			'fsize'=>$fSize,
			'addtime'=>time(),
			'status'=>1
	    );
		$db->t('attachments')->AddData($sysImages);
		unset($sysImages);
        return $returnimg;
    }
	
	
	public function upload_ar($name){
        $upfile = $_FILES[$name];
		$returns = array();
		foreach($upfile['name'] as $k=>$filev){
			if($upfile['name'][$k]!=''){
				$upfilename = $upfile['name'][$k];
				$fileInfo=pathinfo($upfilename);
				$extension= strtolower($fileInfo['extension']);
				if(in_array($extension,array('php','asp','aspx'))) {
					return false;
				}
				$relname = randomkeys(15).date("H-i-s").'.'.$extension;
				$relfile = $this->dir.DS.$relname;
				move_uploaded_file($upfile['tmp_name'][$k],$relfile);
				$returns[$k] = str_replace(DS,'/',str_replace(ROOT_PATH.DS,'',$relfile));
				$filesize = filesize($returns[$k]);
				if($filesize>=2097152){
					//大于2M的就开始截取
					$imageH = new Image();
					$returns[$k] = $imageH->equalThumb($returns[$k],$returns[$k],'',1000,1000);
				}
			}
		}
		return $returns;
        
    }
	
    
    public function autoupload(){
        $rs = array();
        foreach($_FILES as $k => $r){
            if(!empty($r['name'])){
                $rs[$k] = $this->upload($k);
            }
        }
        return $rs;
    }
    
}

?>