<?php

/**
 * CWCMS  后台切换语言文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: lang.php 001 2015-12-10 16:29:08Z Charntent $
 */


require 'admin.inc.php';


$action = gpc('action');
$a = $action?$action:$a;
$gpcpage = intval(gpc('page'));
switch ($a) {

    case 'del':
        $ids = gpc('id');
        if(!$ids){
            $ids = trim(gpc('delids'),',');
        }
        if (!$ids) {
            message("参数错误");
        }
        $idsAr = explode(',',$ids);
        foreach($idsAr as $v=>$id){
            $db->query("delete from `partners` where id='$id'");
            $db->query("delete from `partinfo` where partid='$id'");

        }
        message("删除成功",-1,1000,0);
        break;
    case "add":

        $submit = gpc('submit');
        if(empty($submit)){
            require tpl('hospitals/index');
        }else{
            $data = gpc('data');
            $data['createtime'] = time();
            if($db->t('partners')->AddData($data)){
                alert("添加成功！",U($m."/hospitals").'?page='.$gpcpage,1000,1,0);
            }else{
                alert("添加失败！",U($m."/hospitals").'?page='.$gpcpage);
            }
        }
        break;

    case "edit":

        $id = gpc('id');

        $submit = gpc('submit');
        $r = $db->find(" select * from partners where id='$id'");
        if(empty($submit)){
            require tpl('hospitals/index');
        }else{
            $data = gpc('data');
            $db->settable('partners');
            $id =  gpc('id');
            $data['uptime'] = time();
            if($db->UpdateTable($data,array(" id='{$id}'"))){
                alert("修改成功！",U($m."/hospitals").'?page='.$gpcpage);
            }else{
                alert("修改失败！",U($m."/hospitals").'?page='.$gpcpage);
            }
        }

        break;


    default:

        $where = '';
        $ptype = gpc('ptype');
        $keyword = gpc("keyword");
        if($keyword){
            $where .= " AND name like '%$keyword%' ";
        }

        if($ptype){
            $where .= " AND  ptype  ='$ptype' ";
        }


        $p = new Page("select * from 	`partners`  WHERE 1=1 $where ORDER BY weight asc ,id desc ",8);
        $list = $p->getlist();
        $page = $p->getpage();


        require tpl('hospitals/index');
        break;

    //保存排序
    case 'saveWeight':

        $pid = gpc('pid');
        $value = gpc('value');
        $db->t('partners')->UpdateTable(['weight'=>$value],["id='$pid'"]);
        success('修改成功');
        break;

}
