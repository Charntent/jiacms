<?php

/**
 * CWCMS  公用CMS文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Wl-Public.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');

function get_sonids($id){
	global $categorys;
	$id = isset($categorys[$id]['id'])?$categorys[$id]['id']:0;
	$ids = array();
	foreach($categorys as $r){
		if($r['parentid']==$id && !in_array($r['id'],$ids)){
			$ids[] = $r['id'];
		}
	}
	return $ids;
}




function get_nav($catid,$topid=0,$son=0){
	global $categorys;
	if(isset($categorys[$catid]['parentid']) && !empty($categorys[$catid]['parentid'])){
		$parentid = $categorys[$catid]['parentid'];
		$flag = 1;
		$fscid = $catid;
		while($flag){
		   $parentid = $categorys[$fscid]['parentid'];
		   if($parentid!=0){
			   $fscid = $parentid;
		   }else{
			  $parentid = $fscid;
			  $flag = 0;
			  break;
		   }
		}
		$topid = $son?$topid:(!empty($topid)?$categorys[$catid]['parentid']:0);
	}else{
		$parentid = 0;
	}
	$nav = array();
	
	foreach($categorys as $_ckey => $_cval){
		
		if($_cval['parentid']!=$topid || $_cval['show']!=1 || $_cval['dir'] == $_ckey) continue;
		
		$nav[$_ckey]['url'] = convert_catid_to_url($_cval['id']);		
		$nav[$_ckey]['id'] = $_cval['id'];
		$nav[$_ckey]['catname'] = $_cval['catname'];
		$nav[$_ckey]['current'] = ( $_cval['id']==$catid || $_cval['id']==$parentid)?true:false;
		$nav[$_ckey]['cattype'] = $_cval['cattype'];
		$nav[$_ckey]['pic'] = $_cval['pic'];
		$nav[$_ckey]['ename'] = $_cval['ename'];
		
	}
	return $nav;
}

function get_tpl_name($catid,$type=0){
	global $categorys;
	if($catid==0) return 'index';
	$template = $categorys[$catid]['template'];
	$cattype = $categorys[$catid]['cattype'];
	if(!empty($template)){
		if($cattype=='article' && strpos($template,'|')!==false){
			$templatearr = explode("|",$template);
			return $templatearr[$type];
		}else{
			return $template;
		}
	}else{
		return '';
	}
}

function get_position($catid,$title=null){
	global $categorys,$sitepath,$wl_lang;
	$position = '';
	if($title!=null){
		//$position = $title;
	}
	while(1){
		if(!isset($categorys[$catid])) break;
		$caturl = convert_catid_to_url($catid);
		if($categorys[$catid]['show']==1){
			
			/*if($caturl){*/
				if($categorys[$catid]['parentid']!=0){
				    $position = '<a href="'.$caturl.'">'.$categorys[$catid]['catname'].'</a> > '.$position;
				}else{
					$position = '<a href="'.$caturl.'">'.$categorys[$catid]['catname'].'</a>>'.$position;
				}
			/*}*/
		}else{
			$position =  '<a href="'.$caturl.'">'.$categorys[$catid]['catname'].'</a> > '.$position;
		}
		$catid = $categorys[$catid]['parentid'];
	}
	$position = '<a href="'.BASEURL.'/">'.$wl_lang['home'].'</a> > '.$position;
	
	return $position;
}
function get_catname($catid,$title=null){
	global $categorys,$sitepath,$wl_lang;
	$catanmes = '';
    $i = 0;
	while(1){
		if(!isset($categorys[$catid])) break;
		$catanmes .= "_".$categorys[$catid]['catname'];
		$catid = $categorys[$catid]['parentid'];
		$i++;
	}
	if ($i ==1) {
		$catanmes = trim($catanmes,'_');
	}
	return $catanmes;
}

function ad($id){
	global $db,$Wls_cache;
	$allads = $cache->GetDataById('web_advs');
	if(!$allads){
		$allads_ar =  $db->select(" select * from ads");
		foreach($allads_ar as $sv){
			$allads[$sv['id']] = '<a href="'.$sv['url'].'" target="_blank"><img src="'.BASEURL.'/'.$sv['pic'].'" width="'.$sv['width'].'" height="'.$sv['height'].'"/></a>';	
		}
		@$cache->SaveById('web_advs',$allads,86400000);
		unset($allads_ar);
	}
	return isset($allads[$id])?$allads[$id]:'';
}



function Wls_get_cats_by_id($cat_id){
	global $Wls_cache ;
	$Wlshop_All_Cats = $Wls_cache->GetDataById('Wlshop_All_Cats');
	if(isset($Wlshop_All_Cats[$cat_id])){
		return $Wlshop_All_Cats[$cat_id];
	}else{
	    return false;	
	}
}
//获取品牌
function Wls_get_brand_by_id($brand_id=0){
	global $Wls_cache ;
	$Wlshop_All_Brands = $Wls_cache->GetDataById('Wlshop_All_Brands');
	if(isset($Wlshop_All_Brands[$brand_id])){
		return $Wlshop_All_Brands[$brand_id];
	}else{
	    return $Wlshop_All_Brands;	
	}
}

function Wls_set_cat_url($cat_id){
	return U('list/'.$cat_id);
}


function Wls_get_cats_by_tree($cat_id){
	global $Wls_cache ;
	$Wlshop_All_Cats = $Wls_cache->GetDataById('Wlshop_All_Cats');
	if(isset($Wlshop_All_Cats[$cat_id])){
		return $Wlshop_All_Cats[$cat_id];
	}else{
	    return false;	
	}
}


function Wls_getAllson_cat_id($cat_id=0,&$ids=array()){
	 global $Wls_cache;
	 $allsons = $Wls_cache->GetDataById('Wlshop_All_Cats');
	 if(isset($allsons[$cat_id]))
	 foreach($allsons[$cat_id] as $k=>$v){
	    $ids[] = $v['id'];
		Wls_getAllson_cat_id($v['id'],$ids);
	 }
	return $ids;
}

function Wls_sons_By_cat_id($cat_id){
	global $Wls_cache;
	$ides = array();
	$ids = $Wls_cache->GetDataById('Wls_sons_By_cat_id_'.$cat_id);
	if($ids == FALSE){
	   $ids = implode(',',Wls_getAllson_cat_id($cat_id,$ides));
	   $Wls_cache->SaveById('Wls_sons_By_cat_id_'.$cat_id,$ids,86400000);
	}
	return $ids;
}

function get_catid_by_dir($dir){
   global $wlcms_tool_relates;
 
   if(!is_array($dir)){
	   if(isset($wlcms_tool_relates[$dir])){
		   return $wlcms_tool_relates[$dir];
	   }else{
		  return -1;   
	   }	
   }else{
	   $str = '';
	   foreach($dir as $k=>$v){
		    if(isset($wlcms_tool_relates[$v])){
			     $str = $str.($str ==''?$wlcms_tool_relates[$v]:','.$wlcms_tool_relates[$v]);
			}
	   }
	   return $str==''?null:$str;
   }
}

function get_topid($catid,$_categorys){
	
	while(isset($_categorys[$catid]) && $_categorys[$catid]['parentid']!=0){
		$catid = $_categorys[$catid]['parentid'];
	}
	
	return $catid;
}


