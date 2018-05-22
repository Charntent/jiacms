<?php

/**
 * CWCMS  库函数 文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Function.inc.php 202 2015-12-10 16:29:08Z Charntent $
 */
/**
 * @version    $Id: functions.php 299 2013-06-05 08:29:39Z Charntent $
 *  自定义函数库
 */

function __autoload($class){
	if(file_exists(WL_WLCMS.DS.$class.'.class.php')){
	    require WL_WLCMS.DS.$class.'.class.php';	
	}elseif(file_exists(WL_WLCMS.DS."WeiXin".DS.$class.'.class.php')){
		require WL_WLCMS.DS."WeiXin".DS.$class.'.class.php';	
	}elseif(file_exists(CORE_ROOT.DS."models".DS.$class.'.class.php')){
        require CORE_ROOT.DS."models".DS.$class.'.class.php';
    }
}

function Import($str){
	
	$strar = explode(".",$str);
	$str_class = '';
	foreach($strar as $k=>$v){
		if($str_class == ''){
	        $str_class .= $v;
		}else{
			$str_class .= DS.$v;
		}
	}
	require WL_WLCMS.DS.$str_class.'.class.php';
	
}

function Vendor($str){
	
	$strar = explode(".",$str);
	$str_class = '';
	foreach($strar as $k=>$v){
		if($str_class == ''){
	        $str_class .= $v;
		}else{
			$str_class .= '/'.$v;
		}
	}
	require WL_WLCMS.DS.$str_class.'.php';
	
}

function gpc($key, $method = 'REQUEST'){
	
    $method = strtoupper($method);
    switch($method)
	{
        case 'GET': $var = &$_GET; break;
        case 'POST': $var = &$_POST; break;
        case 'COOKIE': $var = &$_COOKIE; break;
        case 'REQUEST': $var = &$_REQUEST; break;
    }
    return isset($var[$key])?$var[$key]:null;
}

// 浏览器友好的变量输出
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}

// URL重定向
function redirect($url, $time=0, $msg='') {
    //多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    if (!headers_sent()) {
        // redirect
        if (0 === $time) {
            header('Location: ' . $url);
        } else {
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    } else {
        $str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if ($time != 0)
            $str .= $msg;
        exit($str);
    }
}

function set_gpc($key, $value=null){
	if(is_array($key)){
		foreach($key as $k => $r){
			set_gpc($k, $r);
		}
	}else{
		$_GET[$key] = $_POST[$key] = $_REQUEST[$key] = $_COOKIE[$key] = $value;
	}
}

function gpc_addslashes($string){
	if(!is_array($string)) return addslashes($string);
	foreach($string as $key => $val) $string[$key] = gpc_addslashes($val);
	return $string;
}

function gpc_stripslashes($string) {
	if(!is_array($string)) return stripslashes($string);
	foreach($string as $key => $val) $string[$key] = gpc_stripslashes($val);
	return $string;
}

function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}

function get_client_ip() {
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] AS $xip) {
			if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
				$ip = $xip;
				break;
			}
		}
	}
    return $ip;
}

function randomkeys($length,$type=0)
{
 $pattern='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
 $key ="";

 for($i=0;$i<$length;$i++)
 {
   $key .= $pattern{mt_rand(0,$type==0?35:$type)};    //生成php随机数
 }
 return $key;
}

function str_encode($string, $type = 'ENCODE', $key='') {
	global $auth_key;
	if(empty($key)){
		if(empty($auth_key)) die('auth_key not exists');
		$key = $auth_key;	
	}
	$string = ($type == 'DECODE') ? base64_decode($string) : $string;
	$key_len = strlen($key);
	$key     = md5($key);
	$string_len = strlen($string);
	$code = '';
	for ($i=0; $i<$string_len; $i++) {
		$j = ($i * $key_len) % 32;
		$code .= $string[$i] ^ $key[$j];
	}
	return ($type == 'ENCODE') ? rtrim(base64_encode($code),'=') : $code;
}

function isAjax()
{
    $with = strtolower('HTTP_X_REQUESTED_WITH');
    $resType = isset($_SERVER[$with])?$_SERVER[$with]:'';
    $value  = strtolower($resType);
    $format = gpc('format');
    //$result = ('xmlhttprequest' == $value) ? true : false;
    if ('xmlhttprequest' == $value || $format == 'json') {
        return true;
    } else {
        return false;
    }
}
function success($msg,$data = []){
    show_json(["code"=>200,"error"=>0,"msg"=>$msg,"data"=>$data]);
}

function error($msg,$data = []){
    show_json(["code"=>400,"error"=>1,"msg"=>$msg,"data"=>$data]);
}

function message($msg,$redirect=-1,$time=1250,$e=1,$tpl='',$sys=0){

		if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") || isAjax())      {
    // ajax 请求的处理方式
	     show_json(array("error"=>$e,"msg"=>$msg,"tourl"=>$redirect,'times'=>$time));
    }else{
		if($redirect=='-1') $redirect = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'javascript:history.go(-1);';
		if($redirect=='back') $redirect = 'javascript:history.go(-1);';
		extract($GLOBALS);
		$GLOBALS['is_user_tpl'] = true;
		
		$tpl_now =  'message';
		if($e ==0){
			$tpl_now =  'success';
		}
		require tpl($tpl?$tpl:$tpl_now,$sys);
		exit;
	}
}

function message_404($msg,$redirect=-1,$time=1250,$e=1,$tpl='',$sys=0){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")      { 
    // ajax 请求的处理方式
	     show_json(array("error"=>$e,"msg"=>$msg,"tourl"=>$redirect)); 
    }else{
		if($redirect=='-1') $redirect = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'javascript:history.go(-1);';
		if($redirect=='back') $redirect = 'javascript:history.go(-1);';
		extract($GLOBALS);
		$GLOBALS['is_user_tpl'] = true;
		header("HTTP/1.1 404 Not Found");
	    header("Status: 404 Not Found");
		require tpl($tpl?$tpl:'message_404',$sys);
		exit;
	}
}
function show_json($ar){
	echo json_encode($ar,JSON_UNESCAPED_UNICODE);
	exit();
}
function cut($str, $length=0, $fix='') {
	$str = strip_tags($str);
	$str = str_replace("&nbsp;"," ",$str);
    if($length>strlen($str)/3) return $str;
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, 0, $length, 'utf-8');
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,0,$length,'utf-8');
    }else{
		$regx   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		preg_match_all($regx, $str, $match);
		$slice = join('',array_slice($match[0], 0, $length));
	}
    if($fix) $slice .= $fix;
    return $slice;
}

function thumb($img, $width, $height=null){
	global $sitepath;
	if(empty($img)) return "static/images/nopic.gif";
    include_once 'Image.class.php';
    if(!extension_loaded('gd')) return $img;
	
	$root = dirname(__FILE__).'/';
   // $img = $root.$img;
    $basename = basename($img);
	if(strstr($basename,'_')){
		$basenames = explode('_',$basename);
		$basename = end($basenames);
	}
    $newimg = dirname($img).'/thumb_'.$width.'_'.$height.'_'.$basename;
	if(!file_exists($newimg))
	{ 
		$image = new Image();
		$image->set_thumb($width, $height, 100);
		$newimg = $image->thumb($img, $newimg) ? $newimg : $img;
	}

    //return $sitepath.'/'.str_replace($root,'',$newimg);
	return str_replace($root,'',$newimg);
}

function table($table, $field, $id){
	global $db;
	$rs = $db->select("SHOW COLUMNS FROM `$table`");
    foreach($rs as $r){
		if($r['Key'] == 'PRI'){
            $primary = $r['Field'];
            break;
		}
	}
	if(!empty($primary)){
		$result = $db->find("SELECT `$field` FROM `$table` WHERE `$primary`= '$id'");
	}
	return isset($result[$field])?$result[$field]:'';
}

function tpl($tpl,$sys=0){

	if($sys){
	    $path = WL_STATIC.DS;
		$cachepath = WL_DATA.DS.'Tplcache'.DS.'Sys';	
	}else{
		if(defined('IS_ADMIN') && gpc('makehtml')!=1){
			$path = ADMIN.DS.TPLSTYLE;
			$cachepath = WL_DATA.DS.'Tplcache'.DS.'admin';
		}else{
			$path = WL_TEMPLATE.DS.TMLSTYLE;
			$cachepath = WL_DATA.DS.'Tplcache'.DS.TMLSTYLE;
		}
	}
	if($tpl=='message' || $tpl=='success' || $tpl=='message_404'){
		if(!is_file($path.DS.$tpl.'.tpl.php')){
			$path = WL_STATIC.DS;
		    $cachepath = WL_DATA.DS.'Tplcache'.DS.'Sys';	
		}
	}
	$tpl = $tpl.'.tpl.php';
   
	$ins = new Template($tpl,$path,$cachepath);	
	return $ins->view();
}

function textarea($string)
{
	return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}

function javascript($string)
{
	return addslashes(str_replace(array("\r", "\n"), array('', ''), $string));
}

function check_disallow_char($str)
{
	/*if(is_array($str)){
		foreach($str as $r){
			check_disallow_char($r);
		}
	}else{
		$arr = array('execute','update','chr','mid','master','truncate','char','declare','select','create','delete','insert','concat','load_file','hex','substring','ord','intooutfile','intodumpfile','union');
		foreach($arr as $r){
			if(stripos($str,$r)!==false && preg_match('#[\s]+'.$r.'.+|.+'.$r.'[\s]+#is',$str)){
				die('请不要非法提交');
			}
		}
	}*/
}


function compress_html($string){
    $string=str_replace("\r\n",'',$string);//清除换行符
    $string=str_replace("\n",'',$string);//清除换行符
    $string=str_replace("\t",'',$string);//清除制表符
    $pattern=array(
        "/> *([^ ]*) *</",//去掉注释标记
        "/[\s]+/",
        "/<!--[^!]*-->/",
        "/\" /",
        "/ \"/",
        "'/\*[^*]*\*/'"
    );
    $replace=array (
        ">\\1<",
        " ",
        "",
        "\"",
        "\"",
        ""
    );
    return preg_replace($pattern, $replace, $string);
}


function higrid_compress_html($higrid_uncompress_html_source )
{
    $chunks = preg_split( '/(<pre.*?\/pre>)/ms', $higrid_uncompress_html_source, -1, PREG_SPLIT_DELIM_CAPTURE );
    $higrid_uncompress_html_source = '';//[higrid.net]修改压缩html : 清除换行符,清除制表符,去掉注释标记
    foreach ( $chunks as $c )
    {
        if ( strpos( $c, '<pre' ) !== 0 )
        {
//[higrid.net] remove new lines & tabs
            $c = preg_replace( '/[\\n\\r\\t]+/', ' ', $c );
// [higrid.net] remove extra whitespace
            $c = preg_replace( '/\\s{2,}/', ' ', $c );
// [higrid.net] remove inter-tag whitespace
            $c = preg_replace( '/>\\s</', '><', $c );
// [higrid.net] remove CSS & JS comments
            $c = preg_replace( '/\\/\\*.*?\\*\\//i', '', $c );
        }
        $higrid_uncompress_html_source .= $c;
    }
    return $higrid_uncompress_html_source;
}

function formatsize($size) {
	$prec=3;
	$size = round(abs($size));
	$units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
	if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
	$unit = min(4, floor(log($size)/log(2)/10));
	$size = $size * pow(2, -10*$unit);
	$digi = $prec - 1 - floor(log($size)/log(10));
	$size = round($size * pow(10, $digi)) * pow(10, -$digi);
	return $size.$units[$unit];
}

function convert_catid_to_url($id,$start=null,$type=''){
    
	global $is_html,$categorys,$sitepath,$list_html_rule,$setting,$web_local,$fengefu,$html_forder,$defaultCtl;
	if(!isset($categorys[$id])){
	     return "javascript:alert('栏目id".$id."不存在！')";		
	}
	if($categorys[$id]['cattype']=='diypage'){
		$phpscript = explode('|',$categorys[$id]['phpscript']);
		if($setting["rewrite_type"] == '程序伪静态' && $setting["UrlOptimization"] =='是'){
			$url = empty($phpscript[1])?$phpscript[0]:$phpscript[1];
			if(!strstr($url,"http")){
			   if($sitepath)
				  return $web_local.DSS.$sitepath.DSS.$url;
			   else  
			      return $web_local.DSS.$url;
			}else{
				return $url;
			}
		}else{
		    return empty($phpscript[1])?$phpscript[0]:$phpscript[1];
		}
	}else{
		if(empty($is_html)){
			
			if($categorys[$id]['cattype']=='article'){

				if($setting["rewrite_type"] == '程序伪静态' && $setting["UrlOptimization"] =='是' ){
					   //return $web_local.DSS.($categorys[$id]['dir']==''?"catid".$fengefu.$id:$categorys[$id]['dir']).file_prefix();
					   return $web_local.DSS.$categorys[$id]['cat_url'].$type.file_prefix();
					   
				}elseif($setting["rewrite_type"] == '静态' && $setting["UrlOptimization"] =='是' ){
					$html_forder_this = $html_forder;
					if($html_forder != ''){
						$html_forder_this = $html_forder.DSS;	
					}
					//静态的url
					if($categorys[$id]['dir'] == ''){
						return "javascript:alert('请设置栏目英文url')";	
					}
					
					return $web_local.DSS.$html_forder_this.$categorys[$id]['cat_url'].file_prefix();
					
				}else{
				    return $web_local.DSS."$defaultCtl?catid=".$id;
				}
			}elseif($categorys[$id]['cattype']=='page'){
				if($setting["rewrite_type"] == '程序伪静态' && $setting["UrlOptimization"] =='是'){
					 return $web_local.DSS.($categorys[$id]['dir']==''?"page".$fengefu.$id:$categorys[$id]['cat_url']).file_prefix();

				}elseif($setting["rewrite_type"] == '静态' && $setting["UrlOptimization"] =='是' ){
					$html_forder_this = $html_forder;
					if($html_forder != ''){
						$html_forder_this = $html_forder.DSS;	
					}
					//静态的url
					if($categorys[$id]['dir'] == ''){
						return "javascript:alert('请设置栏目英文url')";	
					}
					
					return $web_local.DSS.$html_forder_this.$categorys[$id]['cat_url'].file_prefix();
					
				}else{
				    return $web_local.DSS."$defaultCtl?pageid=".$id;
				}
			}
		}else{
			$html_forder_this = $html_forder;
			if($html_forder != ''){
				$html_forder_this = $html_forder.DSS;	
			}
			//静态的url
			if($categorys[$id]['dir'] == ''){
				return "javascript:alert('请设置栏目英文url')";	
			}
			return $web_local.DSS.$html_forder_this.$categorys[$id]['cat_url'].file_prefix();
		}
	}
}


function convert_aid_to_url($id,$catid=0){
	global $is_html,$sitepath,$article_html_rule,$setting,$web_local,$fengefu,$categorys,$html_forder,$defaultCtl;
	if(!isset($categorys[$catid]))
	return "javascript:alert('$catid栏目不存在！');";	
	//url路径设置 1为续上，二为单个！
	$url_style = 1;
	
	if(empty($is_html)){
		if($setting["rewrite_type"] == '程序伪静态' && $setting["UrlOptimization"] =='是'){
			//看看有没有级别
			
			if($url_style == 1){
				
				/*$fscid = $catid;
				$str_url = '';
				$flag = 1;
				while($flag){
					if($categorys[$fscid]['parentid']!=0){
						 if(!$categorys[$fscid]['dir']){
							$flag = 0;
							return "javascript:alert('起码有一级没有设置url!请联系网站管理员！');";	
							break; 
						 }else{
							 $str_url .= $fengefu.$categorys[$fscid]['dir'];
							 $fscid = $categorys[$fscid]['parentid'];
						 }
					}else{
						$str_url .= $fengefu.$categorys[$fscid]['dir'];
						$flag = 0;
						break;
					}
				}*/
				
				$art_url = str_replace("_","/",$categorys[$catid]['cat_url']).'/'.$id;
				return $web_local.DSS.$art_url.file_prefix();
				//return $web_local.DSS.implode($fengefu,array_reverse(explode($fengefu,trim($str_url,$fengefu)))).$fengefu.$id.file_prefix();
				
			}else{
			    return $web_local.DSS.((!isset($categorys[$catid]['dir']) ||$categorys[$catid]['dir'])==''?"aid".$fengefu.$id:(isset($categorys[$catid]['dir'])?$categorys[$catid]['dir'].$fengefu.$id:"aid".$fengefu.$id)).file_prefix();
			}
			
			
		}elseif($setting["rewrite_type"] == '静态' && $setting["UrlOptimization"] =='是'){
			
		
			$fscid = $catid;
			$str_url = '';
			$flag = 1;
			while($flag){
				if($categorys[$fscid]['parentid']!=0){
					if(!$categorys[$fscid]['dir']){
						$flag = 0;
							return "javascript:alert('起码有一级没有设置url!请联系网站管理员！');";	
							break; 
					}else{
						$str_url .= $fengefu.$categorys[$fscid]['dir'];
						$fscid = $categorys[$fscid]['parentid'];
					}
				}else{
					$str_url .= $fengefu.$categorys[$fscid]['dir'];
					$flag = 0;
					break;
				}
			}
			
			$html_forder_this = $html_forder;
			if($html_forder != ''){
				$html_forder_this = $html_forder.DSS;	
			}
			
			return $web_local.DSS.$html_forder_this.implode($fengefu,array_reverse(explode($fengefu,trim($str_url,$fengefu)))).$fengefu.$id.file_prefix();
			
			
			
		}else{
		    return $web_local.DSS."$defaultCtl?aid=".$id;
		}
	}else{
		//return $sitepath.'/'.str_replace(array('{id}'),array($id),$article_html_rule);
    	$fscid = $catid;
		$str_url = '';
		$flag = 1;
		while($flag){
			if($categorys[$fscid]['parentid']!=0){
				if(!$categorys[$fscid]['dir']){
					$flag = 0;
						return "javascript:alert('起码有一级没有设置url!请联系网站管理员！');";	
						break; 
				}else{
					$str_url .= $fengefu.$categorys[$fscid]['dir'];
					$fscid = $categorys[$fscid]['parentid'];
				}
			}else{
				$str_url .= $fengefu.$categorys[$fscid]['dir'];
				$flag = 0;
				break;
			}
		}
		
		$html_forder_this = $html_forder;
		if($html_forder != ''){
			$html_forder_this = $html_forder.DSS;	
		}
		
		return $web_local.DSS.$html_forder_this.implode($fengefu,array_reverse(explode($fengefu,trim($str_url,$fengefu)))).$fengefu.$id.file_prefix();

	}
}
//路径选择器,全局通用
function  S($id){
	global $setting;
	
	return isset($setting[$id])?$setting[$id]:'';
}
//路径选择器,全局通用
function  U($url = ''){
	global $web_local,$setting,$fengefu,$defaultCtl;
	if(!isset($setting["rewrite_type"])) $setting["rewrite_type"] = '程序伪静态';
	if($setting["rewrite_type"] == '程序伪静态' && $setting["UrlOptimization"] =='是'){
		if($fengefu!='/'){
		   $url = str_replace('/',$fengefu,$url);
		} 
		return $web_local.($url==''?'':DSS.$url.file_prefix());
	}else{
		if($fengefu!='/'){
		   $url = str_replace('/',$fengefu,$url);
		}
		//假如是动态
		if($setting["rewrite_type"] == '动态'){
			
			$url_ars = explode($fengefu,$url);
			$l = count($url_ars);
			$para = '';
			if($url) $para = '?';
			
			switch($l){
				case 1:
				   $para .= 'm='.$url_ars[0];
				break;
				case 2:
				   $para .= 'm='.$url_ars[0].'&c='.$url_ars[1];
				break;
				case 3:
				   $para .= 'm='.$url_ars[0].'&c='.$url_ars[1].'&a='.$url_ars[2];
				break;
				default:
				   $para .= 'm='.$url_ars[0].'&c='.$url_ars[1].'&a='.$url_ars[2];
				   unset($url_ars[0],$url_ars[1],$url_ars[2]);
				   for($i=3; $i<$l;$i=$i+2){
					     $para .= "&".$url_ars[$i]."=".$url_ars[$i+1];
				   };
				   
				break;
			}
			
			return $web_local.DSS.$defaultCtl.$para;
		}else{
		    return $web_local.($url==''?'':DSS.$defaultCtl.DSS.$url.file_prefix());
		}
	}
}

//栏目路径，遇到栏目的时候用这个
function  U_catid($catid,$type=''){
	
	return convert_catid_to_url($catid,null,$type);
}
//文章的路径
function  U_aid($id,$catid=null){
	return convert_aid_to_url($id,$catid);
}
/**
 * 获得指定国家的所有省份
 * GH000 region_type 0为国家  1为省直辖市市 2地级市 3为区或县
 * @access      public
 * @param       int     country    国家的编号
 * @return      array
 */
function get_regions($type = 0, $parent = 0)
{
    global $db;
    $sql = "SELECT region_id, region_name FROM dr_region WHERE region_type = '$type' AND parent_id = '$parent'";
    return $db->select($sql);
}


function file_prefix(){
	global $file_prefix;
	$prefixs = explode("|",$file_prefix);
	if(isset($prefixs[0]) && $prefixs[0] !='') return  ".".$prefixs[0];
	else  return '/';
}



function read_file($file)
	{
		if ( ! file_exists($file))
		{
			return FALSE;
		}

		if (function_exists('file_get_contents'))
		{
			return file_get_contents($file);
		}

		if ( ! $fp = @fopen($file, 'rb'))
		{
			return FALSE;
		}

		flock($fp, LOCK_SH);

		$data = '';
		if (filesize($file) > 0)
		{
			$data =& fread($fp, filesize($file));
		}

		flock($fp, LOCK_UN);
		fclose($fp);

		return $data;
}

function write_file($path, $data, $mode = 'wb'){
	if ( ! $fp = @fopen($path, $mode))
	{
			return FALSE;
	}
	flock($fp, LOCK_EX);
    fwrite($fp, $data);
	flock($fp, LOCK_UN);
	fclose($fp);
	return TRUE;
}

if ( ! function_exists('delete_files'))
{
	function delete_files($path, $del_dir = FALSE, $level = 0)
	{
		// Trim the trailing slash
		$path = rtrim($path, DIRECTORY_SEPARATOR);

		if ( ! $current_dir = @opendir($path))
		{
			return FALSE;
		}

		while (FALSE !== ($filename = @readdir($current_dir)))
		{
			if ($filename != "." and $filename != "..")
			{
				if (is_dir($path.DIRECTORY_SEPARATOR.$filename))
				{
					// Ignore empty folders
					if (substr($filename, 0, 1) != '.')
					{
						delete_files($path.DIRECTORY_SEPARATOR.$filename, $del_dir, $level + 1);
					}
				}
				else
				{
					unlink($path.DIRECTORY_SEPARATOR.$filename);
				}
			}
		}
		@closedir($current_dir);

		if ($del_dir == TRUE AND $level > 0)
		{
			return @rmdir($path);
		}

		return TRUE;
	}
}

function session($name,$v=''){
	if($v==''){
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return null;
	}else{
		$_SESSION[$name] = $v;
	}
}

function unsetsession($ss){
	if(is_array($ss)){
	   	foreach($ss as $k=>$v){
			if(isset($_SESSION[$v]))
			    unset($_SESSION[$v]);
		}
	}else{
		if(isset($_SESSION[$ss]))
			unset($_SESSION[$ss]);
	}
}

function time_day($addtime, $endtime){
	
	$aetime = $endtime - $addtime;
	$days= -1;
	if($aetime > 0){
		$days=floor($aetime/86400);	
	}
	return $days;
}

function select_m($m){
	if(!isset($m['cattype'])) return false;
	switch($m['cattype']){
	    case "diypage":
		    $phpscript = explode('|',$m['phpscript']);
			return array("diypage",$url = empty($phpscript[1])?$phpscript[0]:$phpscript[1]);  
		break;	
		case "article":
		case "page":
		    return array($m['cattype'],"");    
		break;
	}
}


function gettradetypefromotype($otype){
	switch($otype){
		case 17:
			return 15;
		break;
		case 18:
			return 18;
		break;
		default:
			return 16;
	    break;
	}
}

function seg($i){
	global $url;
	if(isset($url[$i]))
	    return $url[$i];
	else{
		return false;
	}
}

function is_mobile() {
 $user_agent = $_SERVER['HTTP_USER_AGENT'];
 $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
 $is_mobile = false;
 foreach ($mobile_agents as $device) {
  if (stristr($user_agent, $device)) {
   $is_mobile = true;
   break;
  }
 }
 return $is_mobile;
}

function getCateFromCatid($catid=0,$type=1){
	global $categorys;
	$cats = array();
	$incs =  array();
	foreach($categorys as $k=>$v){
		if($v['parentid']==$catid && !in_array($v['id'],$incs)){
			if($type==1){
				$v['url'] = convert_catid_to_url($v['id']);	
				$cats[] = $v;
				$incs[] = $v['id']	;
			}else{
				$cats[] = $v['id'];
				$incs[] = $v['id']	;
			}
		}
	}
	unset($incs);
	return $cats;
}
function CA_catid($catid=0,$type=1){
	return getCateFromCatid($catid,$type);
}

function setCache_Byson(){
	global $categorys,$Wls_cache;
	if(!defined('IS_ADMIN')){
		$ht = CLANG;
	}else{
		$ht = LANG;
	}
	$ids = $incs =array();
	$ids = $Wls_cache->GetDataById('setCacheBySon_'.$ht);
	if($ids == FALSE){
		foreach($categorys as $k=>$v){
			if(!in_array($v['id'],$incs) && $v['id']>0 && $v['parentid']!=0){
				$ids[$v['parentid']][] = $v;
				$incs[] = $v['id']	;
			}
		}
		unset($incs);
	   }
	$Wls_cache->SaveById('setCacheBySon_'.$ht,$ids,86400000);
	return $ids;
}




function getAllsoncatid($catid=0,&$ids=array(),&$incs=array()){
	 $allsons = setCache_Byson();
	 if(isset($allsons[$catid]))
	 foreach($allsons[$catid] as $k=>$v){
		if(!in_array($v['id'],$incs)){
	        $ids[] = $v['id'];
			$incs[] = $v['id']	;
		}
		getAllsoncatid($v['id'],$ids,$incs);
	 }
	return $ids;
}

function CA_sonsBycatid($catid){
	global $Wls_cache;
	$ides = array();
	$incs = array();
	$ids = $Wls_cache->GetDataById('CA_sonsBycatid_'.$catid);
	if($ids == FALSE){
	   $ids = implode(',',getAllsoncatid($catid,$ides,$incs));
	   $Wls_cache->SaveById('CA_sonsBycatid_'.$catid,$ids,86400000);
	}
	return $ids;
}

function excelTime($days, $time=false){
    if(is_numeric($days)){
        //based on 1900-1-1
        $jd = GregorianToJD(1, 1, 1970);

        $gregorian = JDToGregorian($jd+intval($days)-25569);

        $myDate = explode('/',$gregorian);

        $myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)

                ."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)

                ."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)

                .($time?" 00:00:00":'');
        return $myDateStr;
    }
    return $days;
}

function db_create_in($item_list, $field_name = '')
{
    if (empty($item_list))
    {
        return $field_name . " IN ('') ";
    }
    else
    {
        if (!is_array($item_list))
        {
            $item_list = explode(',', $item_list);
        }
        $item_list = array_unique($item_list);
        $item_list_tmp = '';
        foreach ($item_list AS $item)
        {
            if ($item !== '')
            {
                $item_list_tmp .= $item_list_tmp ? ",'$item'" : "'$item'";
            }
        }
        if (empty($item_list_tmp))
        {
            return $field_name . " IN ('') ";
        }
        else
        {
            return $field_name . ' IN (' . $item_list_tmp . ') ';
        }
    }
}

function F($cacheid,$value=null){
	global $Wls_cache;
	$holder = explode("/",$cacheid);
	if(strstr($cacheid,"/") && !is_dir(WL_CACHEROOT.$holder[0])){
           mkdir(WL_CACHEROOT.$holder[0],0777,true);
	}
	if($value===null){
	    return 	$Wls_cache->GetDataById($cacheid);
	}else{
		
		return $Wls_cache->SaveById($cacheid,$value,1258000);
	}
	
}

function M($tb){
	global $db;
	$db->t($tb);
	return $db;
}


function alert($msg,$redirect=-1,$time=1250){
    if($redirect=='-1') $redirect = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'javascript:history.go(-1);';
    if($redirect=='back') $redirect = 'javascript:history.go(-1);';
	die('<script type="text/javascript">window.top.message("'.javascript($msg).'",'.$time.');window.location="'.$redirect.'";</script>');
}
//手机网站 去除图片的属性
function replace_img($content){
	$reg = '/(<img.*?)width=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
    $content = preg_replace($reg,'$1$3',$content);
    $reg = '/(<img.*?)height=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';   //去除图片的高度
    $content = preg_replace($reg,'$1$3',$content);
	//$reg = '/(<img.*?)style=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
   // $content = preg_replace($reg,'$1$3',$content);
	return $content;
}

function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)

{ 
	if(function_exists("mb_substr")){
	
	if($suffix)
	
	return mb_substr($str, $start, $length, $charset);
	
	else
	
	return mb_substr($str, $start, $length, $charset);
	
	}
	
	elseif(function_exists('iconv_substr')) {
	
	if($suffix)
	
	return iconv_substr($str,$start,$length,$charset);
	
	else
	
	return iconv_substr($str,$start,$length,$charset);
	
	}
	
	$re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
	[x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
	
	$re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
	
	$re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
	
	$re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
	
	preg_match_all($re[$charset], $str, $match);
	
	$slice = join("",array_slice($match[0], $start, $length));
	
	if($suffix) return $slice;
	
	return $slice;

}

function get_qz(){
    $usr = parse_url(BASEURL);
    $sy = str_replace($usr['scheme'].'://'.$usr['host'].(isset($usr['port'])?':'.$usr['port']:''),'',BASEURL);	
	return $sy;
}

function _get_PATH_INFO(){
	if(isset($_SERVER['PATH_INFO'])) return $_SERVER['PATH_INFO'];
	if ( !isset($_SERVER['PATH_INFO']) && isset($_SERVER['ORIG_PATH_INFO']) )  return  $_SERVER['ORIG_PATH_INFO'];
	if (isset($_SERVER['REDIRECT_URL']) )  return  $_SERVER['REDIRECT_URL'];
    if (isset($_SERVER['REDIRECT_QUERY_STRING']) )  return  $_SERVER['REDIRECT_QUERY_STRING'];
}

function trimall($str){
    $qian=array(" ","　","\t","\n","\r");
    return str_replace($qian, '', $str);   
}
/*是否含有中文*/
function chkGb2312($str) {
	 if(preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str)>0) {
  		return true;
     }
	return false;
}

/*获取幻灯片
*  参数:$type 使用端口
*  参数:$abc 标识
*  参数:$sid 幻灯片的id
*/
function getSlider($type="PC",$abc='index',$sid=0){
	global $db;
	if($sid == 0) {
		$where = " lang='".CLANG."' ";
		if ($type) {
			$where .= " AND type='$type' ";
		}
		if ($abc) {
			$where .= " AND abc='$abc' ";
		}
		return $db->t('ads')->where($where)->orderby(" weight  ASC,id ",'DESC')->all();
	}else{
		$where = " id='$sid' ";
		return $db->t('ads')->where($where)->orderby(" weight  ASC,id ",'DESC')->get(1);
	}
}

/*获取所有的文章
*  参数:$catid 栏目的ID
*  参数:$field 选择的字段
*  参数:$son 是否包含下级
*/
function getArc( $catid, $field = "*", $son = 0 ) {
	global $db;
	$where = " catid='$catid' ";
	if ($son == 1) {
		//如果包含子级的就使用这样的
		$allsons = CA_sonsBycatid($catid);
		if ( $allsons != '') {
			$where = "  ( catid='$catid' OR catid IN($allsons) ) ";
		}
	}
	return $db->t('article')->where($where)->orderby(" `is_top` DESC,`weight` ASC,`id`",'DESC')->all($field);
}


/*
* 获取用户头像
*/
function getThumb($thumb){
    if($thumb == '') {
        return BASEURL.'/resources/images/nopic.jpg';
    }
    if(strstr($thumb,'http') || strstr($thumb,'https')){
        return $thumb;
    }else{
        return BASEURL.'/'.$thumb;
    }
}