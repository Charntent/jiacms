<?php
    /**
     * CWCMS  机器人用户接口
     * ============================================================================
     * * 版权所有 2013-2017 美藤深圳科技有限公司，并保留所有权利。
     * 网站地址: http://www.huimingcn.com；
     * ----------------------------------------------------------------------------
     * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
     * 使用；不允许对程序代码以任何形式任何目的的再发布。
     * ============================================================================
     * $Author: Charntent $
     * $Id: module/api/user.php 202 2017-11-29 16:29:08Z Charntent $
     */

if(!defined('IN_SYS')) exit('Access Denied');
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
$allow_origin = array(
'http://localhost:3000',
'http://test.huimingai.com',
);
if(in_array($origin, $allow_origin)){
header('Access-Control-Allow-Origin:'.$origin);
}

switch ($a) {

    case 'getPartners':
        $partid = gpc('partid');
        $ptype = gpc('ptype');
        $where = [];
        $where[] = ['status','=',1];
        if($partid) {
            $where[] = ['id','=',$partid];
        }

        if($ptype && in_array($ptype,['hospital','maternity','babyshop'])) {
            $where[] = ['ptype','=',$ptype];
        }

        $list = $db->t('partners')->where($where)->orderby("weight",'asc,id desc')->all("id,ptype,agentid,name,logo,createtime");
        foreach ($list as $k=>$v) {
            $v['createtime'] = date("Y-m-d", $v['createtime'] );
            $v['ptype'] = $partNers[$v['ptype']];
            $v['logo'] = getThumb($v['logo']);
            $v['url'] = U('api/partners/detail').'?partid='.$v['id'];
            $list[$k] = $v;
        }

        success('获取成功',$list);
        break;

    case 'getPartInfo':
        $partid = gpc('partid');

        if(!$partid) {
            error("不存在该合作方");
        }
        $r = $db->t('partners')->where([['id','=',$partid],['status','=',1]])->get(1,'name,ptype');
        if(!$r) {
            error("不存在该合作方或者已禁用");
        }

        $list = $db->t('partinfo')->where(['partid','=',$partid])->orderby("weight",'asc,id desc')->all();
        $returnDatas = [];
        $childs = [];
        foreach ($list as $k=>$v) {
           if($v['parent_id'] == 0) {
               $returnDatas[] = $v;
           }else{
               $childs[$v['parent_id']][] = $v;
           }
        }

        foreach ($returnDatas as $k=>$v) {
            if(isset($childs[$v['id']])) {
                $returnDatas[$k]['child'] = $childs[$v['id']];
            }
        }



        success('获取成功',$returnDatas);
        break;

    case 'detail':

        define('title','-美藤AI');

        $partid = gpc('partid');

        if(!$partid) {
            error("不存在该合作方");
        }
        $r = $db->t('partners')->where([['id','=',$partid],['status','=',1]])->get(1,'name,ptype,logo,content');
        if(!$r) {
            error("不存在该合作方或者已禁用");
        }
        $seoname = $r['name'];
        $r['logo']    = getThumb($r['logo']);
        $content = $r['content'];
        require tpl($m.'/'.$c.'/'.$a);
        break;
}