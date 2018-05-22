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

$partNers = [
    'hospital' => '医院产科',
    'maternity' => '月子中心',
    'babyshop' => '母婴商超',
];



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
            $db->query("delete from `$c` where id='$id'");
            $db->query("delete from `$c` where parent_id='$id'");
        }
        message("删除成功",-1,1000,0);
        break;
    case "add":

        $partid = gpc('partid');
        $list = $db->t($c)->where([['parent_id','=',0],['partid','=',$partid]])->all();
        $submit = gpc('submit');
        if(empty($submit)){

            require tpl($c.'/index');

        }else{

            $data = gpc('data');
            $data['allname'] = trim($data['allname']);
            $dataAr = explode("\r\n",$data['allname']);
            unset($data['allname']);

            foreach ($dataAr as $k=>$v) {
                if($v != '') {
                    $data['name'] = $v;
                    $db->t($c)->AddData($data);
                }
            }
            alert("添加成功！",U($m."/".$c).'?id='.$data['partid'].'&page='.$gpcpage,1000,1,0);

        }
        break;

    case "edit":
        $list = $db->t($c)->where(['parent_id','=',0])->all();
        $id = gpc('id');
        $partid = gpc('partid');
        $submit = gpc('submit');
        $r = $db->find(" select * from $c where id='$id'");
        if(empty($submit)){
            require tpl($c.'/index');
        }else{
            $data = gpc('data');
            $id =  gpc('id');
            $data['name'] = $data['allname'];
            unset($data['allname']);
            if($db->t($c)->UpdateTable($data,array(" id='{$id}'"))){
                alert("修改成功！",U($m."/".$c).'?id='.$data['partid'].'page='.$gpcpage);
            }else{
                alert("修改失败！",U($m."/".$c).'?id='.$data['partid'].'page='.$gpcpage);
            }
        }

        break;


    default:

        $id = intval(gpc('id'));
        if(!$id) {
            alert("不存在该合作方");
        }
        $r = $db->t('partners')->where(['id','=',$id])->get(1,'name,ptype');
        if(!$r) {
            alert("不存在该合作方");
        }
        if($r['ptype'] == 'maternity') {
            alert("不支持该功能");
        }
        $menu_list = $db->t($c)->where(['partid','=',$id])->orderby("weight",'asc,id desc')->all();
        $typeName = ($r['ptype'] == 'hospital'?'科室引导':'楼层分布');

        $data = '';
        require tpl($c.'/index');
        break;

    //保存排序
    case 'saveWeight':

        $pid = gpc('pid');
        $value = gpc('value');
        $db->t($c)->UpdateTable(['weight'=>$value],["id='$pid'"]);
        success('修改成功');
        break;
}

function menuget($categoryid,$layer,&$data='')
{  global $menu_list;
    //三种方法啊！现在下面这种效率最高啊！！
    $rs = array();
    foreach($menu_list as $k=>$v){
        if($v['parent_id'] == $categoryid)
            $rs[$k] =  $v;
    }

    if ($categoryid != 0){
        $layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
    }


    foreach($rs as $r)
    {
        $span ="";
        $str="";

        if ($categoryid != 0)
        {

            for ($i = 0; $i < $layer; $i++)//如果i=0，显示的时候第一级菜单就少了个空格
            {
                $span .= "　";
            }
            $span.= "┠";//添加前面的空格
        }


        $str .= '<tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="'.$r['id'].'"></td><td align="center">'.$r['id'].'</td><td align="left" style="text-align:left">'.$span.$r['name'].'</td><td align="left" style="text-align:center"><input type="text" class="form-control weight" data-pid="'.$r['id'].'" style="width:100px" value="'.$r['weight'].'"/></td><td align="left" style="text-align:center">'.($r['status']?'启用':'禁用').'</td><td align="center"><div class="btn-group" role="group" aria-label="..."><a href="?mod=menu&amp;action=edit&amp;id='.$r['id'].'&partid='.$r['partid'].'" class="btn btn-info">修改</a>
<a href="?mod=menu&amp;action=del&amp;id='.$r['id'].'" class="btn btn-danger" onclick="return confirm(\'您真的要删除它吗？\');">删除</a></div></td></tr>';

        $data .= $str;

        menuget($r["id"],$layer,$data);
    }
    return $data;
}
