<?php

/**
 * CWCMS  路由操作文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 
 * $Id: Loader.class.php 203 2017-06-28 14:21:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');


class Loader{

		
	/******************************************************************************
	*****************************************************************************
	*@@PATH_INFO
	*****************************************************************************
	******************************************************************************/
	public static function _get_path_info(){
       $_get_PATH_INFO =  _get_PATH_INFO();
	   $_get_PATH_INFO = trim($_get_PATH_INFO,'/');
	   $adminUrl = substr($_get_PATH_INFO,0,strlen(ADMINURL));
	   if ($adminUrl == ADMINURL || defined('NOCMS')) {
			$action = gpc('action');
			if ($action !='makearticle' && $action!='makehome' && $action!='makelist') {
				 defined('IS_ADMIN') || define("IS_ADMIN",1);	
			}
	    }
	    return  $_get_PATH_INFO;	
	}
		
	/******************************************************************************
	*****************************************************************************
	*@@获取Action
	*****************************************************************************
	******************************************************************************/
	public static function _getaction($type=0){
		
		global $file_prefix,$fengefu,$defaultCtl;
	    $path_info  = self::_get_path_info();
		$path_info  = trim(str_replace($defaultCtl,'',$path_info),'/');
		if($path_info){
			
			$lengh = strlen($file_prefix);
			
			if($lengh>0){
				$fuckhtml = substr($path_info,-$lengh,$lengh);
				if($fuckhtml != $file_prefix){
					if(!defined('IS_ADMIN'))
					   message_404('您访问的页面不存在',-1,1000,1,'',1);
				}
			}
			//$houcou = substr($path_info,0,-$lengh);
			$houcou = preg_replace('/\.('.$file_prefix.')$/','',$path_info);
			$houcou = preg_replace('/\'.+?$/','',$houcou);
			$modandaction = explode($fengefu,trim($houcou,"/"));
			if($fengefu=='/' && strpos($houcou,'_') == true && implode("_",$modandaction) == $houcou){
				message_404('您访问的页面不存在');
			}
				//查看参数是否含有攻击
			foreach($modandaction as $k=>$v){
				
				if(preg_match('/[~!@#$%^&*()\'+]/',$v) || strpos($v,'script')>0){
					 message_404('您访问的页面不存在');
				}
			}
			$modandaction  = gpc_addslashes($modandaction);
			$modandaction  = Filter::xss($modandaction);
			return $modandaction;
		}else{
			return false;
		}
	}
	
	
	static  function setlangFromUrl(){
		global $db;
		$urlAr = self::_getaction();
		if (is_array($urlAr) && $urlAr){
			$url  = implode("_",$urlAr);
			//如果不存在的话,看文章
			$art = $db->t("article")->where("art_url='$url'")->get(1,'id,catid,lang');
			
			if( $art &&  session('clang') != $art['lang']) {
				session('clang',$art['lang']);
			}
		}
		return 	$urlAr;	
	}	
	
	/******************************************************************************
	*****************************************************************************
	*@@执行函数
	*****************************************************************************
	******************************************************************************/
	public static function run($m='content',$urlar){
		
		global $web_url,$db;
		$array = array("m"=>$m,"c"=>'',"a"=>'','catid'=>0,'aid'=>0,'pageid'=>0);
		if($urlar == false) {
			return $array;
		}
		$url = implode("_",$urlar);
		
		$url_page_id_ar = array('','');
		if(isset($urlar[1])){
			$url_page_id_ar = explode('_',$urlar[1]);
		}
		/*$url_type = trim(preg_replace('/(_type-(\d+))$/','',$url));
		$url_page = trim(preg_replace('/(_page-(\d+))$/','',$url));
		$url_type_and_page = trim(preg_replace('/(_type-(.*?)-page-(\d+))$/','',$url));*/
		
		if(isset($web_url[$url])){
			if($web_url[$url]['cattype'] == 'diypage') {
				redirect(convert_catid_to_url($web_url[$url]['id'])); 
			}
			if($web_url[$url]['cattype'] == 'article'){
			   	$array['c'] = 'list';
				$array['catid']  = $web_url[$url]['id'];
			}
			if($web_url[$url]['cattype'] == 'page'){
			   	$array['c']  = 'page';
				$array['pageid']  = $web_url[$url]['id'];
			}
		}elseif(isset($web_url[$urlar[0]]) && count($url_page_id_ar)==2 || count($url_page_id_ar)==4){
			
			//小分类
			
			if($web_url[$urlar[0]]['cattype'] == 'article'){
				if(count($url_page_id_ar)==2 && intval($url_page_id_ar[1])>0){
					if($url_page_id_ar[0] == 'type'){
						
						$type = $url_page_id_ar[1];
						set_gpc('type',$type);
						$array['c'] = 'list';
						$array['catid']  = $web_url[$urlar[0]]['id'];
					}
					
					if($url_page_id_ar[0] == 'page'){
						$page = $url_page_id_ar[1];
						set_gpc('page',$page);
						$array['c'] = 'list';
						$array['catid']  = $web_url[$urlar[0]]['id'];
						
					}
				}elseif(count($url_page_id_ar)==4 &&  $url_page_id_ar[0]=='type' &&  $url_page_id_ar[2]=='page' && intval($url_page_id_ar[1])>0 && intval($url_page_id_ar[3])>0){
					
					    $type = $url_page_id_ar[1];
						set_gpc('type',$type);
						$page = $url_page_id_ar[3];
						set_gpc('page',$page);
						$array['c'] = 'list';
						$array['catid']  = $web_url[$urlar[0]]['id'];
				}
				
			}	
		}else{
			
			//看看两个type先
			
			
			//如果不存在的话,看文章
			$art = $db->t("article")->where("art_url='$url'")->get(1,'id,catid,lang');
		
			if($art){
				$array['c']  = 'view';
				$array['catid']  = $art['catid'];
				$array['aid']  = $art['id'];

			}else{
			    //考虑控制器
				$l = count($urlar);
				if($l==1){
					$array['m']  = $urlar[0];
					$array['c']  = 'index';
				}elseif($l==2){
					$array['m']  = $urlar[0];
					$array['c']  = $urlar[1];
				}elseif($l==3){
					$array['m']  = $urlar[0];
					$array['c']  = $urlar[1];
					$array['a']  = $urlar[2];
				}else{
				    $array['m']  = $urlar[0];
					$array['c']  = $urlar[1];
					$array['a']  = $urlar[2];
					for($i=3;$i<$l;$i=$j+1){
						$j = $i+1;
						set_gpc($urlar[$i],isset($urlar[$j])?$urlar[$j]:'');
				    }
						
				}
			}
			
			
		}
		
		return $array;
	}
}