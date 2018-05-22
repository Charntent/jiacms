<?php

/**
 * CWCMS  TAG文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: TAG.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

class Tag{
	
	public static function sql_select($arr){
		foreach($arr as $k=>$r){
			if(!isset($r['id'])) return $arr;
			if(!empty($r['fields'])){
				$data = @unserialize($r['fields']);
				foreach((array)$data as $_k=>$_r){
					$arr[$k][$_k] = isset($arr[$k][$_k])?$arr[$k][$_k]:$_r;
				}
			}
			if(!isset($arr[$k]['url']) && isset($arr[$k]['catid'])){
				   if(isset($arr[$k]['out_url']) && $arr[$k]['out_url']!=''){
					  $arr[$k]['url'] =  $arr[$k]['out_url'];
				   }else{
				      $arr[$k]['url'] = convert_aid_to_url($r['id'],isset($r['catid'])?$r['catid']:0);
				   }
			}
			if(isset($arr[$k]['thumb']) && $arr[$k]['thumb']!=''){
				  $thumb = _STATIC."/images/nopic.jpg";
				  if( file_exists($arr[$k]['thumb']) || file_exists('../'.$arr[$k]['thumb'])){
					   $thumb = BASEURL."/".$arr[$k]['thumb'];
				  }
				  $arr[$k]['thumb'] = $thumb;
			}else{
				  $arr[$k]['thumb'] = _STATIC."/images/nopic.jpg";
			}
		}
		
		return $arr;
	}

	public static function var_protect($type="IN"){
		static $keepvar,$index;
		if($type=="IN"){
			$syskey = array('GLOBALS','_ENV','HTTP_ENV_VARS','_POST','HTTP_POST_VARS','_GET','HTTP_GET_VARS','_COOKIE','HTTP_COOKIE_VARS',
				'_SERVER','HTTP_SERVER_VARS','_FILES','HTTP_POST_FILES','_REQUEST','HTTP_SESSION_VARS','_SESSION');
			$index = isset($index)?$index+1:0;
			$keepvar[$index] = array();
			foreach($GLOBALS as $k => $r){
				if(!in_array($k,$syskey)){
					$keepvar[$index][$k] = $r;
				}
			}
		}else{
			foreach($keepvar[$index] as $k => $r){
				$GLOBALS[$k] = $r;
			}
			$index--;
		}
	}

	public static function _parse_echo($str){
		$str = substr($str,1,-1);
		return '<?php echo '.self::_parse_var($str).' ?>';
	}

	public static function _parse_var($str){

		if(preg_match('/^@?\$[a-z0-9\.\_\$]+[a-z0-9]$/i',$str)){
			$arr = explode('.',$str);
			foreach($arr as $k => $r){
				if($k==0){
					$str = $r;
				}else{
					if(strpos($r,'$')===false){
						$str .= "['$r']";	
					}else{
						$str .= "[$r]";	
					}
				}
			}
		}

		return $str;
	}
	
	public static function _parse_tag($str){

		return preg_replace_callback('/\$[a-z0-9\.\_]*/si',function ($matches){ return self::_parse_var($matches[0]);},stripslashes($str));
	}


	
	public static function _parse_function($str){

		$stripstr = stripslashes($str);

		$funcstr = self::_parse_tag($str);

		$funcname = preg_replace('/\(.*?$/','',$funcstr);

		if(function_exists($funcname)){
			return '<?php echo '.$funcstr.' ?>';
		}
		return $stripstr;
	}

}

?>