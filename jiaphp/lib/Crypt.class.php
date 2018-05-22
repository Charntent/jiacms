<?php

/**
 * CWCMS  加密操作文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Crypt.class.php 202 2015-12-10 16:29:08Z Charntent $
 */

if(!defined('IN_SYS')) exit('Access Denied');

class Crypt{

    public static function encrypt($data){
        srand((double)microtime() * 1000000);
        $rand = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($data); $i++){
            $ctr = $ctr == strlen($rand) ? 0 : $ctr;
            $tmp .= $rand[$ctr].($data[$i] ^ $rand[$ctr++]);
        }
        return rtrim(base64_encode(self::proc($tmp)),'=');
    }

    public static function decrypt($data){
        $data = self::proc(base64_decode($data));
        $tmp = '';
        for($i = 0; $i < strlen($data); $i++){
            $tmp .= $data[$i] ^ $data[++$i];
        }
        return $tmp;
    }

    protected static function proc($data){
        global $authkey;
		if(empty($authkey)){
			exit('authkey does not exists!');	
		}
        $ctr = 0;
        $tmp = '';
        for($i = 0; $i < strlen($data); $i++){
            $ctr = $ctr == strlen($authkey) ? 0 : $ctr;
            $tmp .= $data[$i] ^ $authkey[$ctr++];
        }
        return $tmp;
    }

}

?>