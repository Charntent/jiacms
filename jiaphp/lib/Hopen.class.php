<?php

/**
 * CWCMS  Hopen核心框架文件
 * ============================================================================
 * * 版权所有 2013-2017 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Hopen.class.php 202 2017-03-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

class Hopen
{
	static private $root;
	static private $modelArs = array();
	
		
	
	public static $data;
	private static $option;
	/*
	 * Sets the response status code.
     * This method will set the corresponding status text if `$text` is null.
     * @param int $value the status code
     * @param string $text the status text. If not set, it will be set automatically based on the status code.
     * @throws InvalidParamException if the status code is invalid.
	*/
	static function T($table) {
		self::$root = WL_ROOT.DS.'Models'.DS;
		$modelfile =  self::$root.$table.'Model.class.php';
		
		if(is_file($modelfile)) {	
			require_once $modelfile;
		}else {
			exit($table.'Model.class.php IS NOT FINDED!');
		}
		$modelclass = $table.'Model';
		if(!isset(self::$modelArs[$modelclass])) {
			$model = new $modelclass();
			self::$modelArs[$modelclass] = $model;
			return $model;
		}else {
			return self::$modelArs[$modelclass];
		}
		
	}

	
	static function setApp($myapp) {
		
		 if(!isset($myapp['data']) || !isset($myapp['option']) ){
		    message('配置错误！');	 
		 }
         self::$data = $myapp['data'];
		 self::$option = $myapp['option'];
	}
	static function init() {
		$ar =  self::$data;
		foreach($ar as $k=>$v){
			$ar[$k] = '';
		}
		self::$data = $ar;
		
	}
	
	static function getVal($data) {
		$ar =  self::$data;
		foreach ($ar as $i=>$v) {
			$ar[$i] = isset($data[$i])? $data[$i]:gpc($i);
		}
		self::$data = $ar;

	}
	
	
	static function validate($app,$data = array()) {
		Hopen::setApp($app);
		self::getVal($data);
		$validateOptions =  self::$option;
		
		$result_v = array('error'=>0,'obj'=>'');
		foreach($validateOptions as $key=>$v) {
			$valitype = $validateOptions[$key][0];
			$regular_t = '';
			if($valitype == 'regular') {
				$regular_t = $validateOptions[$key][2];
			}
			$valiResults = self::validateAction($valitype,$key,$regular_t);
			
			if($valiResults['error'] == 1) {
				$result_v['error'] = 1;
				$result_v['obj'] = $key;
				break;
			}
		}
		
		if($result_v['error'] == 1) {
		   message($validateOptions[$result_v['obj']][1]);	
		   return false;
		}
		
		return true;
		
	}
	
	static function validateAction($type,$key,$regular) {
		
		$result = array('error'=>0);
		
		$value = self::$data[$key];
		switch($type){
			case 'required':
			    if($value==''){
					$result['error'] = 1;
				}
			break;
			
			case 'email':

				if (!Validate::email($value)) {
					$result['error'] = 1;
			    }
				
			break;
			
			case 'mobile':
			
				 if (!Validate::mobile($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			case 'username':
			     if (!Validate::user_name($value)) {
					$result['error'] = 1;
			     }
			break;
			
			case 'regular':
				//$regu = $regular ;
				if(preg_match($regular,$value) === false){
					$result['error'] = 1;
				}
				
			break;
			/*IP地址*/
			case 'ip':
			     if (!Validate::ip($value)) {
					$result['error'] = 1;
			     }
			break;
			
			/*电话号码*/
			case 'phone':
			
			     if (!Validate::phone($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*邮政编码*/
			case 'zip':
			
			     if (!Validate::zip($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*QQ号码*/
			case 'qq':
			
			     if (!Validate::qq($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*验证URL地址*/
			case 'url':
			
			     if (!Validate::url($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			
			/*英文字母*/
			case 'alpha':
			
			     if (!Validate::alpha($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*全数字*/
			case 'number':
			
			     if (!Validate::number($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			
			/*数字或者字母*/
			case 'number':
			
			     if (!Validate::num_alpha($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*数字和字母的组合*/
			case 'blend':
			
			     if (!Validate::blend($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			
			/*数字和字母或上划线,下划线*/
			case 'dash':
			
			     if (!Validate::dash($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			
			
			/*浮点数*/
			case 'dash':
			
			     if (!Validate::float($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*中文*/
			case 'chinese':
			
			     if (!Validate::chinese($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*身份证*/
			case 'id_card':
			
			     if (!Validate::id_card($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*
			*  域名
			*/
			case 'domain':
			
			     if (!Validate::domain($value)) {
					$result['error'] = 1;
			     }
				 
			break;
			
			/*
			*  域名
			*/
			case 'domain':
			
			     if (!Validate::domain($value)) {
					$result['error'] = 1;
			     }
				 
			break;

		}
	
		return   $result;
	}
	
	
}
    