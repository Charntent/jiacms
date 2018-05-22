<?php

/**
 * JIACMS  Adminmodel文件文件
 * ============================================================================
 * * 版权所有 2013-2018 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: DomainsModel.class.php 001 2018-05-22 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');


class DomainsModel {

    private $db;

    function __construct() {
        global $db;
        $this->db = $db;
    }


    /*
    * $PAMRA
    * 用于后台管理员的登陆
    * $username
    * $password
    */
    public function login($username,$password) {
        $password = md5($password);
        $sql = " SELECT * FROM  `admin` WHERE `username`='$username' AND `password`='$password' ";
        return  $this->db->find($sql);
    }

    /*
    * 获取全局的domain
    * $domain
    */
    public function getSiteidByDomain($domain)
    {
        return $this->db->tb('th_domains')->where(['domain','=',$domain])->field('site_id');
    }

    /*
    * 查看该网站是否过期或者续费了
    * $domain
    */
    public function getSiteStatus($site_id)
    {
        return $this->db->t('th_sites')->where(['site_id','=',$site_id])->field('status');
    }

}