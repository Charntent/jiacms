<?php

/**
 * CWCMS  机器人用户接口
 * ============================================================================
 * * 版权所有 2013-2017 慧名深圳科技有限公司，并保留所有权利。
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


    case 'detail':
        $aid = gpc('aid');
        $r = $db->find("select id,catid,title,thumb,content,createtime,click from article where id = '$aid'");
        if(empty($r)) error("文章不存在！");
        $res = $db->query("update article set click=click+1 where id='$aid'");
        if(!$res) {
            error("更新失败");
        }

        $terminal = gpc('terminal');
        if(!in_array($terminal,['pc','app','miniprogram'])) {
            return error('无权访问');
        }
        if(in_array($terminal,['app','miniprogram'])) {
            //小程序和app
            $_arr = preg_split('/(<img.*?>)/i', $r['content'], -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $_r = array();
            $gupload = 'http://data.huimingcn.com/';
            foreach ($_arr as $_txt) {
                if (substr($_txt, 0, 4) == '<img') {
                    $_matchs = array();
                    preg_match('/<img.*?src="(.*?)"/i', $_txt, $_matchs);
                    $_txt = $_matchs[1];
                    if (preg_match('/^\//', $_txt)) $_txt = $gupload . $_txt;
                    $_r[] = array('type' => 'img', 'data' => $_txt);
                } else {
                    $_txt = preg_replace('/&.*?;/', ' ', $_txt);
                    $_txt = preg_replace('/\s+/', ' ', $_txt);
                    $_txt = preg_replace(array('/<br.*?>/i', '/<p.*?>/i', '/<li.*?>/i', '/<div.*?>/i', '/<tr.*?>/i', '/<th.*?>/i'),
                        "\n", $_txt);
                    $_txt = preg_replace('/<.*?>/', '', $_txt);
                    $_r[] = array('type' => 'txt', 'data' => $_txt);
                }
            }
            $r['title'] = $r['title'];
            $r['content'] = $_r;

        }
        $r['thumb'] = BASEURL.'/'.$r['thumb'];
        $r['createtime'] = date('Y-m-d H:i:s',$r['createtime']);
        $r['click'] = $r['click'] + 1;
        $results = array();
        $results['arc'] = $r;

        $catid = $r['catid'];

        $prev = $db->find('select id,catid,title from article where catid='.$catid." and id <".$aid." order by id desc limit 1");

        $next = $db->find('select id,catid,title from article where catid='.$catid." and id >".$aid." order by id asc limit 1");


      //推荐文章
        $tags = $r['tags'];
        if($tags==''){
            $rec_articles = $db->t('article')->where(" catid = '$catid' ")->get(10,"id,catid,title,thumb");
        }else{
            $tags = trim(',',$tags);
            $tagss =  explode(',',$tags);
            $strs = '';
            foreach($tagss as $k=>$v){
                $strs .= " or tags like '%,$v,%' ";
            }
            $rec_articles = $db->t('article')->where(" 1=1 and lang='".CLANG."' $strs ")->get(10,"id,catid,title,thumb");
        }
        $rec_articles = Tag::sql_select($rec_articles);

        $results['other'] = [
            'next' => [
                'title'=>$next?$next['title']:'没有了',
                'url'  =>$next?U_aid($next['id'],$next['catid']):'',
                'id'   =>$next?$next['id']:''
            ],

            'prev' => [
                'title'=>$prev?$prev['title']:'没有了',
                'url'  =>$prev?U_aid($prev['id'],$prev['catid']):'',
                'id'   =>$prev?$prev['id']:''
            ],
            'recommonds'=>$rec_articles
        ];

        return success('获取成功',$results);
        break;

    case 'getList':

        $catid = intval(gpc('catid'));
        $nums = intval(gpc('nums'));
        if(!$catid || !isset($categorys[$catid])) {
            return error('文章列表不存在');
        }

        $type = gpc("type");
        $addon = empty($type)?"":" and type='$type' ";
        $allsons = CA_sonsBycatid($catid);
        if($allsons!=''){
            $catid_str = " ( catid='$catid' OR catid IN($allsons) )";
        }else{
            $catid_str = " catid='$catid' ";
        }


        $pagesize = $nums?$nums:(empty($categorys[$catid]['pagesize'])?15:$categorys[$catid]['pagesize']);

        $_page = new Page(" select id,catid,title,thumb,click,createtime,description,fields from article where  $catid_str  order by is_top desc,weight asc,id desc",$pagesize);

        // 可用变量

        $list = $_page->getlist();
        $page = $_page->getpage();


        $list = Tag::sql_select($list);
        $results = array();
        $results['total'] = $_page->total;
        $results['totalpage'] = ceil($_page->total/$pagesize);
        foreach ($list as $k=>$v) {
            unset($v['fields']);
            if(!isset($v['bigpic']) || $v['bigpic'] =='') $v['bigpic'] = '';
            $v['date'] = date('Y-m-d',$v['createtime']);
            $list[$k] = $v;
        }
        $results['data'] = $list;
        return success('请求成功',$results);
        break;

    case 'getCat':

        $pid = intval(gpc('pid'));
        $results = [];
        foreach ($categorys as $k=>$v) {
            if($v['parentid'] == $pid && ($v['id'] == 17 || $v['id'] == 18 || $v['id'] == 19))
            $results[] = [
                'catid'   => $v['id'],
                'catname' => $v['catname'],
                'icon'    => getThumb('resources/images/icons/'.$v['parentid'].'/'.$v['id'].'.png')
            ];
        }
        return success('请求成功',$results);
        break;



}