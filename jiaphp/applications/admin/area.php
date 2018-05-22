<?php

/**
 * CWCMS  地区修改文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: area.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';
$action = gpc('action');

$cache = new Cache();
$region = $cache->GetDataById('region_all');
if(empty($region)){
	$allregion = $db->select(" select id,parentid,name from region order by id asc ");
	foreach($allregion as $r){
		$region[$r["parentid"]][$r['id']] = $r; 
	}
    $cache->SaveById('region_all',$region,86400000);
}
switch($action){
	
	     case 'del':
				$id = gpc('id');
				$db->query("delete from `region` where id=$id");
				message("删除成功",-1,1000,0);
			break;
			case "add":
			
			   $submit = gpc('submit');
			   if(empty($submit)){
			        require tpl('area');
			   }else{
				    $data = gpc('data');
					$db->settable('region');
					if($db->AddData($data)){
						$cache->Delete('region_all');
						alert("添加成功",U($m.'/area'));
					}else{
						alert("添加成功",U($m.'/area'));
					}
			   }
			break;
			default :
				$where = array();
			
				$keyword = gpc("keyword");
				if($keyword){
					$where[] = " and name like '%$keyword%' ";
				}
			
				$where = empty($where)?"":join(" AND ",$where);
				$p = new Page("select * from 	`region`  WHERE 1=1 $where ",8);
				$list = $p->getlist();
				$page = $p->getpage();
				require tpl('area/area');
			
			break;
			
			case "edit":
			    
				$id = gpc('id');
				
			   $submit = gpc('submit');
			   $r = $db->find('select * from region where id='.$id);
			   if(empty($submit)){
				   require tpl('area');
			   }else{
				    $data = gpc('data');
					$db->settable('region');
					if($db->UpdateTable($data,array(" id='{$id}'"))){
						alert("修改成功！",U($m.'/area'));
					}else{
						alert("修改失败！",U($m.'/area'));
					}  
			   }
			
			break;
}

function fetcharea($categoryid,$layer,$s='',&$data='')
	{  global $db,$areas,$region; 
	   //三种方法啊！现在下面这种效率最高啊！！
		//$rs = $db->select(" select id,name from region where parentid='{$categoryid}' order by id asc ");
		$rs = isset($region[$categoryid])?$region[$categoryid]:array();
		
		if ($categoryid != "0"){
				$layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
		}
		foreach($rs as $r)
		{
			$span ="";
			$str="";
			  if ($categoryid != '0')
					{
						for ($i = 0; $i < $layer; $i++)//如果i=0，显示的时候第一级菜单就少了个空格
						{
							$span .= "　";
						}
						$span.= "┠";//添加前面的空格   
					}
			
			if($s == $r["id"])
			   $str.="<option value='{$r['id']}' selected='selected'>".$span.$r["name"]."</option>";	
				else
		       $str.="<option value='{$r['id']}'>".$span.$r["name"]."</option>";	
		
		    $data .= $str;
			fetcharea($r["id"],$layer,$s,$data);
		}
		return $data;
}