<?php

/**
 * CWCMS  Adminmodel文件文件
 * ============================================================================
 * * 版权所有 2013-2017 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: AdminModel.class.php 202 2017-03-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');


class AdminModel {

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


}