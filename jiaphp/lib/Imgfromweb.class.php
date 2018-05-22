<?php

/**
 * CWCMS  远程获取图片文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: ImgFromweb.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

class Imgfromweb
{
	
	//方法一ob_get_contents
	/***
    /***   $para $url	图片的路径
	/***   $filename  文件名
	/***   $savefile  保存的路径
	*/
	function get_photo($url,$filename='',$savefile='test/')   
		{     
			$imgArr = array('gif','bmp','png','ico','jpg','jepg');  
		  
			if(!$url) return false;  
			
			if(!$filename) {     
			  $ext=strtolower(end(explode('.',$url)));     
			  if(!in_array($ext,$imgArr)) return false;  
			  $filename=date("dMYHis").'.'.$ext;     
			}     
		  
			if(!is_dir($savefile)) mkdir($savefile, 0777);  
			if(!is_readable($savefile)) chmod($savefile, 0777);  
			  
			$filename = $savefile.$filename;  
		  
			ob_start();     
			readfile($url);     
			$img = ob_get_contents();     
			ob_end_clean();     
			$size = strlen($img);     
			
			$fp2=@fopen($filename, "a");     
			fwrite($fp2,$img);     
			fclose($fp2);     
			
			return $filename;     
		 }  
	//方法二
	//*****
	//****$url保存的路径
	//	 利用file_get_contents
    function my_file_get_contents($url, $timeout=10) 
		{  
		     global $web_local,$skins_admin,$wlcms_templates;
		     $usr = parse_url($url);
			 $pathinfo = pathinfo($url);
			 $extension = isset($pathinfo['extension'])?$pathinfo['extension']:'';
			 $file_contents = '';
			 $status = 0;
			 
			 if(isset($usr['host'])){
				 $locals = parse_url($web_local);
				
				 if($usr['host']!=$locals['host']){
				
					 if ( function_exists('curl_init') ){ 
						$ch = curl_init(); 
						if(defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')){
						    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
						}
						curl_setopt ($ch, CURLOPT_URL, $url); 
						curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
						curl_setopt ($ch,  CURLOPT_CONNECTTIMEOUT, $timeout);
						curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1); 
						//curl_setopt ( $ch,  CURLOPT_NOSIGNAL,true);
						$file_contents = curl_exec($ch); 
						$codes = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						if($codes==200 && $file_contents){
							$fw_type = 'pc';
							$TMLSTYLE = $wlcms_templates[$fw_type.'temp'][LANG];
							$dir = WL_ROOT.DS.'Uploads'.DS.$TMLSTYLE.DS.date('Ymd');
							if(!is_dir($dir)) mkdir($dir,0777,true);
							$relname = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
                            $relfile = $dir.DS.$relname;
							//保存图片
							$status = $this->saveimg($file_contents,$relfile);
						
						}
						curl_close($ch); 
					}elseif ( ini_get('allow_url_fopen') == 1 || strtolower(ini_get('allow_url_fopen')) == 'on' ){ 
					    $file_contents = @file_get_contents($url); 
						$dir = WL_ROOT.DS.'Uploads'.DS.TMLSTYLE.DS.date('Ymd');
						if(!is_dir($dir)) mkdir($dir,0777,true);
						$relname = date("YmdHis").mt_rand(1000,9999).'.'.$extension;
						$relfile = $dir.DS.$relname;
						//保存图片
						$status = $this->saveimg($file_contents,$relfile);
					}
				 }
			 }else{
			    return $url;	 
			 }
			if($status){
			   $filename = str_replace(WL_ROOT.DS,"",$relfile);
			   if(S('imgaddwebsite')=='是'){
			      $filename = BASEURL.'/'.str_replace(DS,DSS,$filename);
			   }else{
				  $qz =  get_qz();
				  $filename = $qz.'/'.str_replace(DS,DSS,$filename);  
			  }
			  return $filename; 
			}
		    return $url; 
		  
		}
	//获取照片的地址
	//获取所有图片标签的全部信息    
	function getImg($str){  
	
		$str = stripslashes($str);  
		//$pattern = "/<img[^>]*src\=\"(([^>]*)(jpg|gif|png|bmp|jpeg))\"/i";   //获取所有图片标签的全部信息  
		/*$pattern = "/[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg|.png]))['|\"].*?[\/]?>/";*/
		/*$pattern = "/[img|IMG].*?src=['|\"](.*?(?:[.gif|.jpg]))['|\"].*?[\/]?>/";*/
		/*$pattern = "/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png|\.bmp|\.jpeg]))[\'|\"].*?[\/]?>/";*/
       // $pattern = "/\<img.*?src\=\"(.*?)\"[^>]*>/i";
	    $pattern= '|src="(.*)"|isU'; 
		//'|src="(.*)"|isU'
		preg_match_all($pattern, $str, $matches); 
		
		//preg_match_all($pattern, $str, $matches);  
		
		return $matches[1];   //$matches[1]中就是所想匹配的结果,结果为数组  
    }  
	
	//自动保存所有的图片
	//$str 保存的代码
	function saveallimg($str,$type=2){
		global $web_local;
		 $imgurls = $this->getImg($str);
		
		 //替换img
		 $new_img_ar = array();
		 
		 foreach($imgurls  as $url){
			$usr = parse_url($url);
			$locals = parse_url($web_local);
			$fileInfo   = pathinfo($url);
            $extension  = strtolower($fileInfo['extension']);
			
			if(!in_array($extension,array('jpg','jpeg','png','gif'))) {
				continue;
			}
			if(!isset($usr['host']) || $usr['host'] == $locals) continue;
		    if($type==1){
			    $this->get_photo($url);
		    }else{
			   $newimg =  $this->my_file_get_contents($url,1);
			  // $new_img_ar[$url] =  $newimg;
			   $str = str_replace($url,$newimg,$str);
		    }
		}  
		
		return $str;
	}
	
	//获取第一张
	//$str 保存的代码
	function getfirstimg($str){
		$arimgs = $this->getImg($str);
		return isset($arimgs[0])?$arimgs[0]:'';
	}
	
	
	function get_content($url){
		return $this->my_file_get_contents($url,30);
	}
	
	//保存图片
	function saveimg($file_contents,$filename){
	     if($file_contents){
			$fp2=@fopen($filename, "a");     
			fwrite($fp2,$file_contents);     
			fclose($fp2); 
			return true;
	     }else{
		  return false; 
	   }
	}
	
}