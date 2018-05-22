<?php

/**
 * CWCMS  文件缓存
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Cache.class.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

class Cache {
	
	protected $_cache_path ='';
    
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->_cache_path = WL_CACHEROOT;
	}

    public  function GetDataById($id){
		if ( ! file_exists($this->_cache_path.$id)){
			return FALSE;
		}
		
		$data = read_file($this->_cache_path.$id);
		$data = unserialize($data);
		
		if (time() >  $data['time'] + $data['ttl'])
		{
			unlink($this->_cache_path.$id);
			return FALSE;
		}
		
		return $data['data'];
		
	}
	
	public  function SaveById($id,$data,$ttl=60){
		$contents = array(
				'time'		=> time(),
				'ttl'		=> $ttl,			
				'data'		=> $data
			);
		
		if (write_file($this->_cache_path.$id, serialize($contents)))
		{
			@chmod($this->_cache_path.$id, 0777);
			return TRUE;			
		}

		return FALSE;
		
	}
	
	public  function Delete($id){
		return @unlink($this->_cache_path.$id);
	}
	
	public   function Clean($file ='' )
	{   
		return delete_files(($file ==''?$this->_cache_path:$file));
	}
	
	public function autoSet($cache_name,$data='',$time=''){
		if($data==''){
			return $this->GetDataById($cache_name);
		}else{
			$this->Delete($cache_name);
			$this->SaveById($cache_name,$data,$time);
			return $data;
		}
	}
}