<?php

/**
 * CWCMS  sitemap自动生成的文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id:Template.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';


$action = gpc('action');

$func = 'sitemap';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	
	
	
	default:
	
	 
	  $SitemapApp = new SitemapApp();
	  $xml =  $SitemapApp->index();
	  $msg =  '已经成功更新！';
	  if(empty($SitemapApp->urls)){
			$SitemapApp->_get_updated_items(0);
	  }
	  $urls = $SitemapApp->urls;
	  //自动提交到百度
	  if(S('issitemap')=='yes'){
		  
		  //自动提交代码到issitemap  
		  if(S('baidutoken')==''){
			  $msg .= '但是无法自动提交到百度，原因：百度token不存在，请先去系统设置填写百度token！';
		  }else{
			// $xml = file_get_contents($SitemapApp->_baidu_sitemmap_file);
			// $tt= tobaidu($xml,S('baidutoken')); //实时推送新发文章的url
			
			
			
			$res = urlToBaidu($urls,S('baidutoken')); 
			$tt = '';
			if(isset($res['success'])){
				$tt = '实时推送成功链接'.$res['success'].'条!<br>';
				foreach($urls as $k=>$v){
					$tt .= $v.'<br/>';
				}
			}else{
				$tt .= '实时推送失败，原因是：'.$res['message'];
			}
			unset($urls,$SitemapApp);
			$msg .= $tt;   
		  }
	  }else{
		  $msg .= '没有设置自动提交到百度,sitemap.xml条数更新到'.count($urls).'条';   
	  }
	  
	  
	require tpl('sitemap/index');
	break;
}


function tobaidu($xml,$token){
	
    $baidu_info = array(
	    200=>'成功提交到百度！',//'无使用方式错误，需要进一步观察返回的内容是否正确'
		400=>'必选参数未提供',
		405=>'不支持的请求方式，我们只支持POST方式提交数据',
		411=>'HTTP头中缺少Content-Length字段',
		413=>'推送的数据过大，超过了10MB的限制',
		422=>'HTTP头中Content-Length声明的长度和实际发送的数据长度不一致',
		500=>'站长平台服务器内部错误',
		403=>'传输格式错误！'
	);
    $site = parse_url(BASEURL);
	$pingurl = "http://ping.baidu.com/sitemap?site=".$site['host']."&resource_name=sitemap&access_token=".$token;//你的接口地址
	
	$curl= curl_init();// 启动一个CURL会话
	 
	curl_setopt($curl, CURLOPT_URL,$pingurl);// 要访问的地址
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);// 对认证证书来源的检查
	
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);// 从证书中检查SSL加密算法是否存在
	
	curl_setopt($curl, CURLOPT_USERAGENT,"curl/7.12.1");// 模拟用户使用的浏览器
	
	//curl_setopt($curl, CURLOPT_COOKIE,$cookie);
	
	curl_setopt($curl, CURLOPT_REFERER,"");
	
	curl_setopt($curl, CURLOPT_POST, 1);// 发送一个常规的Post请求
	
	curl_setopt($curl, CURLOPT_POSTFIELDS,$xml);// Post提交的数据包
	
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);// 设置超时限制防止死循环
	
	curl_setopt($curl, CURLOPT_HEADER, 0);// 显示返回的Header区域内容
	
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);// 获取的信息以文件流的形式返回
	
	curl_setopt($curl, CURLOPT_TIMEOUT,10);
	
	$tmpInfo = curl_exec($curl); // 执行操作
	
	if(curl_errno($curl)) {
	
	    echo 'Errno'.curl_error($curl); //捕抓异常
		exit();
	
	}
	//$httpinfo = curl_getinfo($ch);
	curl_close($curl); // 关闭CURL会话
	
	return isset($baidu_info[$tmpInfo])?$baidu_info[$tmpInfo]:$tmpInfo; // 返回数据

}






