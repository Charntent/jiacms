<?php

/**
 * CWCMS  文章核心文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Article.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';
define("BRIEF_LENGTH",200);
$action = gpc('action');

$func = 'article';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);


$catid = gpc('catid');
$category = $db->find("select * from category where id='$catid' ");
if(empty($category)) alert("栏目不存在");

if(S('rewrite_type') =='静态'){
   $is_html = 1;
}


$flags = array(
    'h'=>'头条',
	'c'=>'推荐',
	'f'=>'幻灯',
	'a'=>'特荐',
	's'=>'滚动',
	'b'=>'加粗',
	'p'=>'图片',
	'j'=>'跳转',

);


function save_article(){
	global $db,$categorys;
	$addon = array();
	$action = gpc('action');
    $catid = gpc('catid');
	if($action=='add'){
	    $addon['createtime'] = time();
		
	}
	$data = gpc('data');
	if(isset($data)){
		for($i=1;$i<=5;$i++){
			if(isset($data[ 'field'.$i ])) $addon['field'.$i] = $data[ 'field'.$i ];
			unset( $data[ 'field'.$i ] );
		}
		$addon['fields'] = addslashes( serialize( gpc_stripslashes($data)) );
	}


	//缩略图为空的，抽取第一张作为缩略图
	$is_yc = gpc('is_yc');
	//保存远程图片

	$content = gpc('content');
	
	if($is_yc ==1){
		//保存远程图片
		$imgfromweb = new Imgfromweb();
		$addon['content'] = $imgfromweb->saveallimg($content);
		$content = $addon['content'];
		$imgars = $imgfromweb->getImg($addon['content']);
		if(isset($_REQUEST['thumb']) && $_REQUEST['thumb'] == ''  && $categorys[$catid]['thumb']=1){
			if(isset($imgars[0])){
				if(S('imgaddwebsite')=='是'){
				   $addon['thumb'] = str_replace(BASEURL.'/','',$imgars[0]);
				}else{
				   $addon['thumb'] = trim($imgars[0],'/');
				}
				$qz = get_qz();
				$qz = ltrim($qz,'/');
				if($qz!=''){
                    $addon['thumb']  = str_replace($qz.'/','',$addon['thumb']);
				}else{
					$addon['thumb']  = ltrim($addon['thumb'],'/');
				}
				
			}
		 }
	}
    
	
	
	$cutwidth = gpc('cutwidth')+0;
	$cutheight = gpc('cutheight')+0;
	if($cutwidth>0 || $cutheight>0){
		if(isset($addon['thumb'])){
			 $thumb = $addon['thumb'];
			 $addon['thumb'] = thumb($addon['thumb'],$cutwidth,$cutheight);	
		}else{
			 $thumb = gpc('thumb');
			 $addon['thumb'] = thumb($thumb,$cutwidth,$cutheight);	
		}
		if($addon['thumb'] != $thumb && file_exists($thumb)){
		    @unlink($thumb);	
		}
	}
    //找到原来有的tags
	$tags_ar = array();
	$aid = gpc('id');
	if($action == 'edit'){
	    $tags = $db->getfield(" select tags from article where id='$aid' ");
		if($tags != ''){
		   $tags_ar = explode(',',trim($tags,','));
		}
	}
	//TAGS
	if(isset($_REQUEST['tags']) && $_REQUEST['tags']!=''){
		$tags = $_REQUEST['tags'];
		$tags = str_replace("，",',',$tags);
		$exls = explode(',',$tags);
		$ids = '';
		foreach($exls as $k=>$v){
			 
		     $id = $db->t('tags')->where(" title='$v' ")->FindData('id,nums');
			 if($id){
				 $ids .= ','.$id['id'];
				 
				 $keys  = array_search($id['id'],$tags_ar);
				 if($keys) unset($tags_ar[$keys]);
				 
				 if($action == 'add'){
					 //如果添加的时候出现 以前有的，那么它次数就加1
				     $db->t('tags')->where(" id='".$id['id']."' ")->setField('nums',$id['nums']+1);
				 }
			 }else{
				 $db->t('tags')->AddData(array('title'=>$v,'listorder'=>0,'nums'=>1));
				 $ids .= ','.$db->insert_id();
			}
		}
		
		
		$addon['tags'] = $ids.',';
	}
	//删除完之后
	if($action =='edit'){
	    if(!empty($tags_ar)){
		   //判断一下，剩余的是不是可以删除
		   foreach($tags_ar as $k=>$v){
			   
			    $trs = $db->select("select id from article where tags like ',%".$v."%,' ");
				if(count($trs) ==1 && $trs[0]['id'] == $aid){
					//独有的可以删除了
					$db->query(" delete from tags where id='$v' ");
				}else{
					//不是独有的肯定次数少1
					$db->query(" update tags set nums=nums-1 where id='$v' ");
				}
		   }
		}	
	}

	$desc = gpc('description');

	if($desc==''){
		//描述为空的时候，自动截取100个字符作为
		$addon['description'] = msubstr(strip_tags(htmlspecialchars_decode($content)),0,200);
		// Generate_Brief(strip_tags($addon['content']));
	}
	
	$flags_gpc = gpc('flags');
	if(is_array($flags_gpc))
	    $addon['flag'] = implode(',',$flags_gpc);
    else
	    $addon['flag'] = '';
    
	return $db->data($addon)->save("article");
}




switch($action){
	case 'settop':
	    $delids = gpc('delids');
		if(!$delids){
			show_json(array('error'=>1,'msg'=>'参数错误！'));
		}
		$id = trim($delids,',');
		$db->query(" UPDATE article SET is_top=1 where `id` IN($id) ");
		show_json(array('error'=>0,'msg'=>'置顶成功！'));
	break;
	case 'endtop':
	    $delids = gpc('delids');
		if(!$delids){
			show_json(array('error'=>1,'msg'=>'参数错误！'));
		}
		$id = trim($delids,',');
		$db->query(" UPDATE article SET is_top=0 where `id` IN($id) ");
		show_json(array('error'=>0,'msg'=>'取消置顶成功！'));
	break;
	case 'add':
		$catid = $endcatid = gpc('catid');
		$submit = gpc('submit');
		if(empty($submit)){

			//产生一个随机数
			$fw = S('addfanwei');
			$click_sj = 5210;

			$fws = explode(',',$fw);

		    if(sizeof($fws)==2){
				$click_sj = rand($fws[0],$fws[1]);
			}

			$e_sort = $db->getfield("select e_sort from category where id='$catid'");
			require tpl('article/article');
		}else{

			$title = gpc('title');
			$art = $db->find("select id,catid from article where title='{$title}' and  catid='{$catid}'");
			$strtm  = '';
			if($art){
				$strtm = '有同名文章了！';
			}

			$id = save_article();
			if(!empty($is_html)){
				make_article_html($id,1);
				make_list_html($catid);
				while($catid){
					$parentid = $db->getfield("select parentid from category where id='$catid'");
					if($parentid){
						make_list_html($parentid);
					}
					$catid = $parentid;
				}
				make_home_page();
			}
			//然后写入地址

			$art_url  = str_replace("/","_",$categorys[$endcatid]['cat_url'].'/'.$id);
			$db->t('article')->where("id='$id'")->setField("art_url",$art_url);
			$msg = '文章添加成功!';
			
			//添加成功之后，自动生成sitemap
			$SitemapApp = new SitemapApp();
	        $xml =  $SitemapApp->index();
			
			//自动提交该url到
			$msg1 = '';
			$aurl = U_aid($id,$catid);
			if(S('issitemap')=='yes'){
				   if(S('baidutoken')==''){
					  $msg1 .= '已设置自动将URL提交到百度，但无法自动提交到百度，原因：百度token不存在，请先去系统设置填写百度token！';
				  }else{
			        $urls =array($aurl);
					$res = urlToBaidu($urls,S('baidutoken')); 
					$tt = '';
					if(isset($res['success'])){
						$tt = '实时推送成功链接'.$res['success'].'条!<br>';
						foreach($urls as $k=>$v){
							$tt .= $v.'<br/>';
						}
					}else{
						$tt .= '实时推送失败，原因是：'.$res['message'];
					}
					unset($urls,$SitemapApp);
					$msg1 .= $tt;  
				}
			}
			
			alert("添加成功$strtm".$msg1,U($m.'/article')."?action=success&id={$id}&catid={$endcatid}&msg=".$msg.$msg1);
		}
	break;
	
	case 'success':
	     $catid = gpc('catid');
		 $rcatid = gpc('rcatid');
		 if(!$rcatid) $rcatid = $catid;
		 $page = gpc('page');
		 $id = gpc('id');
		 $msg = gpc('msg');
	     require tpl('article/success');
	break;

	case 'setcategory':

	    $handids = gpc('handids');
		$tocatid = gpc('tocatid');
		if(!$handids || !isset($categorys[$tocatid])){
			show_json(array('error'=>1,'msg'=>'参数错误！'));
		}
		$ids = trim($handids,',');

	    $artt = $db->select("select * from article where `id` IN($ids)");

		foreach($artt as $k=>$art){
			
			if(!isset($categorys[$art['catid']])) continue;
			$url  = $categorys[$tocatid]['cat_url'];
			$url = str_replace("/",'_',$url);
			$url = $url.'_'.$art['id'];
		    $db->query("UPDATE `article` SET `catid`='".$tocatid."', art_url='".$url ."'  where `id`='".$art['id']."'");
		}
	
		
		
		show_json(array('error'=>0,'msg'=>'移动成功！'));
	break;

	case 'edit':
	    
		$target = gpc('target');
		
		if($target=='times'){
			$times = gpc('times');
			$times = strtotime($times);
			$aid = gpc('id');
			$db->query("UPDATE `article` SET  createtime='".$times."'  where `id`='".$aid."'");
			message('设置完成！','',1000,0);
		}
		
	 
	    $refresh = gpc('refresh');
		if($refresh=='url'){
		   //更新url
		   	if(!isset($categorys[$catid])) alert('更新完成！');
			/*$url  = $categorys[$catid]['cat_url'];
			$url = str_replace("/",'_',$url);*/
			$allsons = CA_sonsBycatid($catid);
			$where = '';
			if($allsons)  $where =  " OR catid IN($allsons) ";
			$arcs = $db->t('article')->where(" catid='$catid' $where")->all();
			foreach($arcs as $k=>$v){
			   $url = $categorys[$v['catid']]['cat_url'];
			   $url = str_replace("/",'_',$url);
			   $url1 = $url.'_'.$v['id'];
		       $db->query("UPDATE `article` SET  art_url='".$url1."'  where `id`='".$v['id']."'");
			}
			alert('更新完成！','back');
		}
	
		$id = gpc('id');
		$submit = gpc('submit');
		$art = $db->find("select * from article where id=$id");
		if(empty($submit)){
			$data = @unserialize($art['fields']);
			foreach( (array)$data as $_key => $_val){
				if(!isset($art[$_key])) $art[$_key] = $_val;
			}
			unset($art['fields']);
			$e_sort = $db->getfield("select e_sort from category where id='{$art['catid']}'");
			$tags = trim($art['tags'],',');
			if($tags!=''){
				$tags_ar = $db->select(" select * from tags where id IN($tags) ");
				$art['tags'] = '';
				foreach($tags_ar as $kj=>$v){
					$art['tags'] = $art['tags'].','.$v['title'];
				}

				$art['tags'] = trim($art['tags'],',');
			}
			
			//Flags
			$flag_ar = explode(',',$art['flag']);
			$art['content'] = htmlspecialchars($art['content']);
			require tpl('article/article');
		}else{
			$_REQUEST['catid'] = $art['catid'];
			save_article();
			if(!empty($is_html)){
				make_article_html($id,1);
				make_list_html($art['catid']);
				$parentid = $db->getfield("select parentid from category where id='{$art['catid']}'");
				if($parentid){
					make_list_html($parentid);
				}
				make_home_page();
			}
			//然后写入地址
			$art_url  = str_replace("/",'_',$categorys[$art['catid']]['cat_url'].'/'.$id);
			$db->t('article')->where("id='$id'")->setField("art_url",$art_url);
			$msg = '文章修改成功!';
			$rcatid = gpc('rcatid');
			$page = gpc('page');
			
			//添加成功之后，自动生成sitemap
			$SitemapApp = new SitemapApp();
	        $xml =  $SitemapApp->index();
			
			//自动提交该url到
			$msg1 = '';
			$aurl = U_aid($id,$art['catid']);
			if(S('issitemap')=='yes'){
				   if(S('baidutoken')==''){
					  $msg1 .= '已设置自动将URL提交到百度，但无法自动提交到百度，原因：百度token不存在，请先去系统设置填写百度token！';
				  }else{
		        	$urls =array($aurl);
					$res = urlToBaidu($urls,S('baidutoken')); 
					$tt = '';
					if(isset($res['success'])){
						$tt = '实时推送成功链接'.$res['success'].'条!<br>';
						foreach($urls as $k=>$v){
							$tt .= $v.'<br/>';
						}
					}else{
						$tt .= '实时推送失败，原因是：'.$res['message'];
					}
					unset($urls,$SitemapApp);
					$msg1 .= $tt;  
				}
			}
			
			
			alert("修改成功".$msg1,U($m.'/article')."?action=success&id={$id}&catid=".$art['catid'].'&rcatid='.$rcatid.'&page='.$page.'&msg='.$msg.$msg1);
		}
	break;
	case 'del':
		$id = gpc('id');
		$art = $db->find("select * from article where id='$id'");
		//$db->query("delete from favorite where aid=$id");
		if(file_exists($art['thumb'])){
			@unlink($art['thumb']);
		}
		if(file_exists('../'.$art['thumb'])){
			@unlink('../'.$art['thumb']);
		}

		$db->query("delete from article where id=$id");

		if(!empty($is_html)){
			make_list_html($art['catid']);
			$parentid = $db->getfield("select parentid from category where id='{$art['catid']}'");
			if($parentid){
				make_list_html($parentid);
			}
			make_home_page();

			//删除静态文件
			$url = convert_aid_to_url($id,$art['catid']);
			$url = str_replace($web_local.DSS,'',$url);
			$url = str_replace('.html','',$url);

		    $file = $url.'.html';
			if(file_exists($file)){
			   @unlink($file);
			}
		}
		message("删除成功",'back',1000,0);
	break;
	case 'delall':
		$delids = gpc('delids');
		if(!$delids){
			show_json(array('error'=>1,'msg'=>'参数错误！'));
		}
		$id = trim($delids,',');
		$artt = $db->select("select * from article where `id` IN($id)");
		//$db->query("delete from favorite where aid=$id");
		foreach($artt as $k=>$art){
		if(file_exists($art['thumb'])){
			@unlink($art['thumb']);
		}
		if(file_exists('../'.$art['thumb'])){
			@unlink('../'.$art['thumb']);
		}

		$db->query("delete from article where `id`='".$art['id']."'");
		}


		show_json(array('error'=>0,'msg'=>'删除成功！'));
	break;
	case "e_subtype":
		$submit = gpc('submit');
		$catid = gpc('catid');
		if(empty($submit)){
			$subtype = $db->getfield("select subtype from category where id='$catid'");
			$_subtype = explode("\r\n",$subtype);
			$subtype = array();
			foreach($_subtype as $_val){
				if($_val){
					list($x,$y) = explode('|',$_val);
					$subtype[$x] = $y;
				}
			}
			require tpl('article/article');
		}else{
			$data = gpc('data');
			$str = '';
			for($i=0;$i<count($data);$i=$i+2){
				if($data[$i] && $data[$i+1])
					$str .= $data[$i]."|".$data[$i+1]."\r\n";
			}
			$str = trim($str);
			$db->query("update category set subtype='$str' where id='$catid'");
			alert("修改成功",U($m.'/article')."?catid=".$catid);
		}
	break;
	default:
		$where = array();

		$allsons = CA_sonsBycatid($catid);
		$addons = '';
		if($allsons){
			$addons = " OR catid IN($allsons)";
		}
		if($catid!==null){
			$where[] = " (catid='$catid' $addons ) ";
		}
		$keyword = gpc("keyword");
		if($keyword){
			$where[] = "title like '%$keyword%' ";
		}
		$type = gpc("type");
		if($type){
			$where[] = "`type`='$type'";
		}
		$where = empty($where)?"":" WHERE ".join(" AND ",$where);
		if($category['subtype']){
			$_subtype = explode("\r\n",$category['subtype']);
			$category['subtype'] = array();
			foreach($_subtype as $_val){
				list($x,$y) = explode('|',$_val);
				$category['subtype'][$x] = $y;
			}
		}

		$order = gpc("order");
		$order = empty($order)? " is_top desc,weight asc ,createtime  ":$order;
		$orderby = "order by $order";
		if ($order=='weight') {
			$orderby .=", id desc";
		} else {
			$orderby .=" desc";
		}



		$p = new Page("select * from `article` $where $orderby",10);
		//echo "select * from `article` $where $orderby";
		$list = $p->getlist();
		$page = $p->getpage();


		$hasclick = $db->find("select id from article where (catid='$catid'  $addons)  AND click>0");

		$e_sort = $db->getfield("select e_sort from category where id='$catid'");



		require tpl('article/article');
}
function Generate_Brief($text){
    global $Briefing_Length;
    mb_regex_encoding("UTF-8");
    if(mb_strlen($text) <= BRIEF_LENGTH ) return $text;
    $Foremost = mb_substr($text, 0, BRIEF_LENGTH);
    $re = "<(\/?)
(P|DIV|H1|H2|H3|H4|H5|H6|ADDRESS|PRE|TABLE|TR|TD|TH|INPUT|SELECT|TEXTAREA|OBJECT|A|UL|OL|LI|
BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|SPAN)[^>]*(>?)";
    $Single = "/BASE|META|LINK|HR|BR|PARAM|IMG|AREA|INPUT|BR/i";

    $Stack = array(); $posStack = array();

    mb_ereg_search_init($Foremost, $re, 'i');

    while($pos = mb_ereg_search_pos()){
        $match = mb_ereg_search_getregs();
        /*    [Child-matching Formulation]:

            $matche[1] : A "/" charactor indicating whether current "<...>" Friction is
Closing Part
            $matche[2] : Element Name.
            $matche[3] : Right > of a "<...>" Friction
        */
        if($match[1]==""){
            $Elem = $match[2];
            if(mb_eregi($Single, $Elem) && $match[3] !=""){
                continue;
            }
            array_push($Stack, mb_strtoupper($Elem));
            array_push($posStack, $pos[0]);
        }else{
            $StackTop = $Stack[count($Stack)-1];
            $End = mb_strtoupper($match[2]);
            if(strcasecmp($StackTop,$End)==0){
                array_pop($Stack);
                array_pop($posStack);
                if($match[3] ==""){
                    $Foremost = $Foremost.">";
                }
            }
        }
    }

    $cutpos = array_shift($posStack) - 1;
    $Foremost =  mb_substr($Foremost,0,$cutpos,"UTF-8");
    return $Foremost;
}
