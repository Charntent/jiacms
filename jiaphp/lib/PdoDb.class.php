<?php

/**
 * CWCMS  PDoDb数据库操作文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: PdoDb.class.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

/**
 * 网站数据库类文件
 * @author		Charntent(752293795@QQ.com)
 * @copyright	(c) 2014-07-22 by ctphp.com
 * @version		$Id$
 * @package		PdoDB
 */
class PdoDB extends PDO {

	/**
	 * PdoDB构造函数
	 * @author	长藤 <752293795@QQ.com>
	 * @param	string $dsn    数据源
	 * @param	string $usr    用户名
	 * @param	string $pwd    密码
	 * @param	array $array   参数数组
	 * @return  object
	 */
	function __construct($dsn,$usr=null,$pwd=null,$array=null){
		
		parent::__construct($dsn,$usr,$pwd,$array);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PdoDBStatement'));
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}

}

