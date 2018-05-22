<?php

/**
 * CWCMS  正则验证文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Validate.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');
class Validate
{
    
    /**
     * IP地址
     * @param string $str
     * @return boolean
     */
    public static function ip($ip)
    {
        if(preg_match('/((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3}/',$ip) === false){
          return false;
        }
        return true;
    }

    /**
     * 手机号码
     *
     * @param string $str
     * @return boolean
     */
    public static function mobile($str)
    {
        //return preg_match("/^[0]?(18[0|1|2|3|4|5|6|7|8|9]|13[0-9]{1}|15[0-9]{1}+)(\d{8})$/", $str);
		return 
		preg_match("/^1[3458]{1}\d{9}$/", $str);
		
    }

    
    /**
     * 电话号码
     *
     * @param string $phone
     * @return boolean
     */
    public static function phone($phone)
    {
        $regex = "/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/";
        return preg_match($regex,$phone);
    }
    
    /**
     * 邮政编码
     *
     * @param string $str
     * @return boolean
     */
    public static function zip($str)
    {
        return preg_match("/^[1-9]\d{5}$/", $str);
    }

    /**
     * 邮件地址
     * @param string $str
     * @return boolean
     */
    public static function email($str)
    {
        return preg_match('/^[a-z0-9]+([\+_\-\.]?[a-z0-9]+)*@([a-z0-9]+[\-]?[a-z0-9]+\.)+[a-z]{2,6}$/i', $str);
    }

    /**
     * QQ号码
     * @param <type> $str
     * @return <type>
     */
    public static function qq($str)
    {
        return preg_match("/^[1-9]{1}[0-9]{4,13}$/i", $str);
    }
    
    /**
     * 验证URL地址
     *
     * @param string $str
     * @return boolean
     */
    public static function url($str)
    {
        return preg_match("|^http://[_=&/?\.a-z0-9-]+$|i", $str);
    }

    /**
     * 全英文字母
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function alpha($str, $len = 0)
    {
        if(is_int($len) && ($len > 0)) {
            return preg_match("/^([a-z]{".$len."})$/i", $str);
        } else {
            return preg_match("/^([a-z])+$/i", $str);
        }
    }

    /**
     * 全数字
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function number($str, $len = 0)
    {
        if(is_int($len) && ($len > 0)) {
            return preg_match("/^([0-9]{".$len."})$/", $str);
        } else {
            return preg_match("/^([0-9])+$/", $str);
        }
    }

    /**
     * 数字或字母
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function num_alpha($str, $len = 0)
    {
        if(is_int($len) && ($len > 0)) {
            return preg_match("/^([a-z0-9]{".$len."})$/i", $str);
        } else {
            return preg_match("/^([a-z0-9])+$/i", $str);
        }
    }

    /**
     * 数字和字母的组合
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function blend($str, $len = 0 ,$max_len = 0)
    {
        if(is_int($max_len) && ($max_len > 0)) {
            if(!self::len($str, $len, $max_len)) {
                return false;
                exit;
            }
        } elseif (is_int($len) && ($len > 0) && !$max_len) {
            if(strlen($str) > $len) {
                return false;
                exit;
            }
        }
        return preg_match("/^(((\d+[a-z]+)|([a-z]+\d+))[0-9a-z_]*)$/i", $str);
    }

    /**
     * 数字和字母或上划线,下划线
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function dash($str, $len = 0)
    {
        if(is_int($len) && ($len > 0)) {
            return preg_match("/^([_a-z0-9-]{".$len."})$/i", $str);
        } else {
            return preg_match("/^([_a-z0-9-])+$/i", $str);
        }
    }

    /**
     * 浮点数
     *
     * @param string $str
     * @return boolean
     */
     
    public static function float($str)
    {
        return preg_match("/^[0-9]+\.[0-9]+$/", $str);
    }

    /**
     * 中文
     *
     * @param string $str
     * @param integer $len
     * @return boolean
     */
    public static function chinese($str)
    {
        return preg_match("/^[\x{4e00}-\x{9fa5}]+$/u", $str);
    }

    /**
     *  身份证
     *
     * @param string $id
     * @return boolean
     */
    public static function id_card( $id )
    {
        $id = strtoupper($id);
        $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
        $arr_split = array();
        if(!preg_match($regx,$id))
        {
            return false;
        }
        if(15==strlen($id)) //检查15位
        {
            $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
            @preg_match($regx, $id, $arr_split);
            //检查生日日期是否正确
            $dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))
            {
                return false;
            }else{
                return true;
            }
        }
        else           //检查18位
        {
            $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
            @preg_match($regx, $id, $arr_split);
            $dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
            if(!strtotime($dtm_birth))  //检查生日日期是否正确
            {
                return false;
            }
            else
            {
                //检验18位身份证的校验码是否正确。
                //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
                $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
                $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
                $sign = 0;
                for ( $i = 0; $i < 17; $i++ )
                {
                    $b = (int) $id{$i};
                    $w = $arr_int[$i];
                    $sign += $b * $w;
                }
                $n  = $sign % 11;
                $val_num = $arr_ch[$n];
                if ($val_num != substr($id,17, 1))
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }

    }

    /**
     * 域名
     *
     * @param string $domain
     * @return boolean
     */
    public static function domain($domain)
    {
        return preg_match('#^([0-9a-z]+(\-?[0-9a-z]+)*\.)+[a-z]{2,6}$#i', $domain);
    }

    /**
     * 用户名
     *
     * @param string $user_name
     * @return bool
     */
    public static function user_name($user_name)
    {
        return preg_match('/^[a-z0-9\x{4e00}-\x{9fa5}]+(_*[a-z0-9\x{4e00}-\x{9fa5}]+)*$/iu', $user_name)
        && strlen($user_name) >= 0 && mb_strlen($user_name, 'UTF-8') <= 30;
    }
    
    /**
     * 自定义正则验证
     *
     * @param string $str
     * @param string $type
     * type为正则表达示格式，如 /[a-z]+[\d]{3,5}/i
     * @return boolean
     */
    public static function custom($str, $type)
    {
         return preg_match($type, $str);
    }

}

?>