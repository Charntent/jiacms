<?php

/**
 * CWCMS 验证码生成文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Checkcode.class.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

class Checkcode{

	private $width  = 70;
	private $height = 28;

    public function __construct(){
		header("Content-type: image/PNG");  
    }
    
    public function randstr(){
        $chars='ABCDEFGHJKMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz23456789';
        $i = 4;
        $str = '';
        $len = strlen($chars);
        while($i--){
            $r = mt_rand(0,$len-1);
            $str .= $chars[$r];
        }
        $_SESSION['checkcode'] = $str;
        return $str;
    }
    
	public function show() {
		$im = imagecreate($this->width, $this->height);

		$gray = imagecolorallocate($im, 238,238,238); 
		$randcolor = imagecolorallocate($im, rand(0,150),rand(0,150),rand(0,150)); 
		
		imagefill($im,0,0,$gray);
		$randstr = $this->randstr();

		imagettftext($im, 14, 0, 7, 22, $randcolor, dirname(__FILE__).'/elephant.ttf', $randstr);
		
		for($i=0; $i<200; $i++) { 
     		$randcolor = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
     		imagesetpixel($im, rand()%100 , rand()%30 , $randcolor); 
		}
		
		$a = imagepng($im); 
		imagedestroy($im);
		return $a;
	}
	
}

?>