<?php

/**
 * CWCMS  Robots生成文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Robots.class.php 202 2016-07-08 16:29:08Z Charntent $
 */
class Robots
{
    function __construct()
    {
        $this->Robots();
    }
	
    function Robots()
    {
        $this->_robots_file = ROOT_PATH . '/robots.txt';
		if(!file_exists($this->_robots_file)){
			fopen($this->_robots_file, "w") or die("Unable to open file!");
		}
    }

    function index()
    {  
	    return $this->_output_robots();
    }

    /**
     *    输出baidu sitemap
     *
     *    @author    Charntent
     *    @return    void
     */
    function _output_robots()
    {
       
        return $this->_get_robots();
    }
	
	   /**
     *    获取robots的内容
     *
     *    @author    Charntent
     *    @return    void
     */
    function _get_robots()
    {
	   
       return file_get_contents($this->_robots_file);
    }
	
	
	
	


    /**
     *    写入baidu sitemap文件
     *
     *    @author    Charntent
     *    @param     string $sitemap
     *    @return    void
     */
    function _write_robots($txt)
    {
        $myfile = fopen($this->_robots_file, "w") or die("Unable to open file!");
		fwrite($myfile, $txt);
		fclose($myfile);
    }
}
?>