<?php

/**
 * CWCMS  PdoDBStatement文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: PdoDBStatement.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');
/**
 * PdoDBStatement类文件
 * @author		长藤
 * @copyright	(c) 2014 by Charntent
 * @version		$Id$
 * @package		PdoDBStatement
 */
class PdoDBStatement extends PDOStatement{

	/**
	 * PdoDBStatement构造函数
	 * @author	长藤 <752293795@qq.com>
	 * @return  object
	 */
	private function __construct(){
		$this->setFetchMode(PDO::FETCH_ASSOC);
	}

}
