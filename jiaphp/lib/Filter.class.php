<?php

/**
 * CWCMS  仿SQL注入、XSS攻击操作文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Filter.class.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');
class Filter
{
	private static $_allowtags = 'p|br|b|strong|hr|a|object|param|form|input|label|dl|dt|dd|div|font|blockquote|span',
	               $_allowattrs = 'id|class|align|valign|src|border|href|target|width|height|title|alt|name|action|method|value|type',
	               $_disallowattrvals = 'expression|javascript:|behaviour:|vbscript:|mocha:|livescript:';
	
	function __construct($allowtags = null, $allowattrs = null, $disallowattrvals = null)
	{
		if ($allowtags) self::$_allowtags = $allowtags;
		if ($allowattrs) self::$_allowattrs = $allowattrs;
		if ($disallowattrvals) self::$_disallowattrvals = $disallowattrvals;
	}
	
	static function input($cleanxss = 1)
	{
		
        if (!get_magic_quotes_gpc())
        {
           $_POST = gpc_addslashes($_POST);
           $_GET = gpc_addslashes($_GET);
           $_COOKIE = gpc_addslashes($_COOKIE);
           $_REQUEST = gpc_addslashes($_REQUEST);
		   
        }
	
        if (!defined('IS_ADMIN') && $cleanxss)
        {  
        	$_POST = self::xss($_POST);
        	$_GET = self::xss($_GET);
        	$_COOKIE = self::xss($_COOKIE);
        	$_REQUEST = self::xss($_REQUEST);
			check_disallow_char($_REQUEST);
        }
		
	}
	
	static function xss($string)
	{
		if (is_array($string))
		{
			$string = array_map(array('self', 'xss'), $string);
		}
		else 
		{
			if (strlen($string) > 20)
			{  
                if (get_magic_quotes_gpc()){
                    $string = gpc_addslashes( self::_strip_tags(gpc_stripslashes($string)));
                }else{
                   $string = self::_strip_tags( $string );
                }
			}
		}
		return $string;
	}
	
	static function _strip_tags($string)
	{
		return preg_replace_callback("|(<)(/?)(\w+)([^>]*)(>)|", array('self', '_strip_attrs'), $string);
	}
	
	static function _strip_attrs($matches)
	{   
		if (preg_match("/^(".self::$_allowtags.")$/", $matches[3]))
		{   
			if ($matches[4])
			{
				preg_match_all("/\s(".self::$_allowattrs.")\s*=\s*(['\"]?)(.*?)\\2/i", $matches[4], $m, PREG_SET_ORDER);
				
				$matches[4] = '';
				
				foreach ($m as $k=>$v)
				{
					if (!preg_match("/(".self::$_disallowattrvals.")/", $v[3]))
					{
						$matches[4] .= $v[0];
					}
				}
				
			}
		}
		else 
		{
			$matches[1] = '&lt;';
			$matches[5] = '&gt;';
		}
		unset($matches[0]);
		return implode('', $matches);
	}
}