<?php

/**
 * CWCMS  开发模式文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳美藤科技有限公司，并保留所有权利。
 * 网站地址: http://www.ziyouteng.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Dev.php 202 2015-12-10 16:29:08Z Charntent $
 */

require 'admin.inc.php';

$action = gpc('action');

$func = 'dev';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

$cache = new Cache();

$catgroupid = gpc('catgroupid');

switch($action){
	
	case 'setdelall':
	    
		$doids = gpc('doids');
		$id = trim($doids,',');
		if(!$doids || !$id){
			message('参数错误！');
		}
		
		$ids = explode(',',$id);
		
		foreach($ids as $l=>$h){
			$cates = get_cates_by_until($allcatgorys);
		    $r =  get_by_sonids($h,$cates);
			
			if($r){
				$rs_ids = implode(",",$r);
				if($rs_ids)
				$db->query(" DELETE FROM `category`  WHERE `id` IN($rs_ids) ");
				$db->query(" DELETE FROM `article`  WHERE `catid` IN($rs_ids) ");
				$db->query(" DELETE FROM `page`  WHERE `id` IN($rs_ids) ");
			}
			$allcatgorys = unlink_ids($r,$allcatgorys);
		}
		
	    message('删除成功！');
		
	
	break;
	
	case 'getroups':
	  echo '<p style="border-bottom:1px solid #CCC; padding-bottom:10px; margin-bottom:10px; width:100%">《'.$cats_groups[$catgroupid].'》有以下栏目</p>' ;
	 $rr =  fetgroupcates(0,0,0);
	 
	break;
	
	case 'setgroups':
	   
	   echo '<p style="padding-bottom:10px">请选择栏目分组</p>';
	   echo '<select name="groupid" id="groupid" class="form-control">';
	    foreach($cats_groups as $k=>$v){
			echo '<option value="'.$k.'">'.$v.'</option>';
		}
	  echo '</select>';
	break;
	
	case 'setgroups_do':
	    
		$doids = gpc('doids');
		$type = gpc('type');
		if(!isset($cats_groups[$type])){
			message('参数错误！');
		}
		$id = trim($doids,',');
		if(!$doids || !$id){
			message('参数错误！');
		}
		
		$db->query(" UPDATE `category` SET `catgroup`='$type' WHERE `id` IN($id) ");
		message('设置成功！','',1000,0);
		
	break;
	
	case 'add':
		$sub = gpc("sub");
		if($sub){
			$data = array();
			$cattype = gpc("cattype");
			if(empty($cattype)){
				alert("请选择栏目类型",'back');
			}
			$phpscript = gpc("phpscript");
			$phpscript = join("|",$phpscript);
			set_gpc('phpscript',$phpscript);
			
			//首先判断
			$ident = gpc("ident");
			$ident = trim($ident);
			if(!$ident){
				alert("请填写栏目标识",'back');
			}
			
			$identis = $db->t('category')->where(" ident='$ident' and lang='".LANG."' ")->FindData('*');
			if($identis){
				alert("该栏目标识已经存在了！",'back');
			}
			$catname = gpc('catname');
			$catnames = $db->t('category')->where(" catname='$catname' and lang='".LANG."' ")->FindData('*');
			$msg = '';
			if($catnames){
				
				$msg = ',但是栏目名称已经存在了！';
			}
			$transname = gpc('transname');
			if($transname==''){
				$data['transname'] = $catname;
			}
			$data['ident'] = $ident;
			$data['dir'] = trim(gpc('dir'));
			$dirs = $db->t('category')->where(" dir='".$data['dir']."' and lang='".LANG."' ")->FindData('id');
			if($dirs){
				alert("该栏目的链接已经存在，请重新输入！",'back');
			}
			
			if($cattype == 'article' || $cattype == 'page'){
			    $data['title'] = $data['thumb'] = $data['keywords'] = $data['description'] =  1;
			}
			
			$data['menugroup'] = '内容管理';
			//查看是否独有
			$langsingle = gpc('langsingle');
			//计算cat_url
			$addpare = gpc('addpare');
			if($addpare !=1){
				//证明不续上
			    $data['cat_url'] = $data['dir'];
			}else{
				$parentid = gpc('parentid');
				if($parentid==0){
					$data['cat_url'] = $data['dir'];
				}else{
					
					$fscid = $parentid;
				    $str_url = '';
					$flag = 1;
					while($flag){
						if($categorys[$fscid]['parentid']!=0){
							 if(!$categorys[$fscid]['dir']){
								$flag = 0;
								alert('栏目id为'.$fscid.'没有设置url！');
								break; 
							 }else{
								 $str_url .= '/'.$categorys[$fscid]['dir'];
								 $fscid = $categorys[$fscid]['parentid'];
							 }
						}else{
							$str_url .= '/'.$categorys[$fscid]['dir'];
							$flag = 0;
							break;
						}
					}
					
					$data['cat_url'] = implode('/',array_reverse(explode('/',trim($str_url,'/')))).'/'.$data['dir'];
				}
			}
			
			$id = $db->data($data)->save("category");
			if($cattype=='page')
			$db->query("insert into page (id) values ('$id')");
			$idf = $cache->Delete('_categorys_'.LANG);
			$cache->Delete('web_url_'.LANG);
			//获取栏目之后自动生成各个版本的栏目
			
			$data = $db->t('category')->where(" id='$id'")->FindData('*');
			unset($data['id']);
			if($langsingle ==1){
				foreach($langs as $k=>$v){
					$data['lang'] = $v['langid'];
					//LANG是当前的栏目
					if($v['langid']!=LANG){
						//首先看顶级栏目是不是先
						if($data['parentid']!=0){
							//获取上级栏目
							$data_parent = $db->t('category')->where(" id='".$data['parentid']."'")->FindData('*');
							$data_parent_id = $db->t('category')->where(" ident='".$data_parent['ident']."' and lang='".$v['langid']."' ")->FindData('id');
							$data['parentid'] = $data_parent_id['id'];
						}
						$rs_get = $db->t('category')->where(" ident='".$data['ident']."' and lang='".$v['langid']."' ")->FindData('*');
						if(!$rs_get)
						$db->t('category')->AddData($data);
					}
				}
				alert("添加成功".$msg,U($m.'/dev'));
			}else{
				alert("添加成功".$msg,U($m.'/dev'));
			}
		}
		$pid = gpc('pid');
		$cattype = $db->select("select * from category where parentid=0 AND id>0  order by weight asc,id asc");
		
		
		//生成栏目标识
		$ident_auto = 'W'.date("is").randomkeys(5);
		
		require tpl('dev/dev');
	break;
	
	
	case 'batchadd':
		$sub = gpc("sub");
		if($sub){
			$data = array();
			$cattype = gpc("cattype");
				//查看是否独有
			$langsingle = gpc('langsingle');
			
			if(empty($cattype)){
				alert("请选择栏目类型",'back');
			}
			$phpscript = gpc("phpscript");
			$phpscript = join("|",$phpscript);
			set_gpc('phpscript',$phpscript);
			
			//批量栏目
			$catnames = gpc('catnames');
			$dirs = gpc('dirs');
			if(!$catnames){
				alert("请输入栏目",'back');
			}
			$catnames_ar = explode(',',trim($catnames));
			$dirs_ar = explode(',',trim($dirs));
			
			foreach($catnames_ar as $k=>$v){
				$data['menugroup'] = '内容管理'; 
				$data['transname'] = $data['catname'] = $v;
				//生成ident
				$data['ident'] = 'W'.date("is").randomkeys(5);
				//url
				if(isset($dirs_ar[$k]) &&  $dirs_ar[$k]!=''){
					$data['dir'] = $dirs_ar[$k];  
				}else{
					$data['dir'] = $data['ident'];
				}
				$data['cat_url'] = $data['dir'];
				
				if($cattype=='article'){
					$data['title'] = $data['thumb'] = $data['description'] =  1;
				}
				
				//添加分类成功
				$id = $db->data($data)->save("category");
				if($cattype=='page')
				     $db->query("insert into page (id) values ('$id')");
					
				$data1 = $db->t('category')->where(" id='$id'")->FindData('*');
				unset($data1['id']);
				
				if($langsingle ==1){
					//等于1的话就同步
					foreach($langs as $k=>$v){
						$data1['lang'] = $v['langid'];
						//LANG是当前的栏目
						if($v['langid']!=LANG){
							//首先看顶级栏目是不是先
							if($data1['parentid']!=0){
								//获取上级栏目
								$data_parent = $db->t('category')->where(" id='".$data1['parentid']."'")->FindData('ident');
								$data_parent_id = $db->t('category')->where(" ident='".$data_parent['ident']."' and lang='".$v['langid']."' ")->FindData('id');
								$data1['parentid'] = isset($data_parent_id['id'])?$data_parent_id['id']:'noexits';
							}
							if($data1['parentid'] != 'noexits'){
								$rs_get = $db->t('category')->where(" ident='".$data1['ident']."' and lang='".$v['langid']."' ")->FindData('id');
								if(!$rs_get){
								   $lid = $db->t('category')->AddData($data1);
								   if($cattype=='page')
									   $db->query("insert into page (id) values ('$lid')");
								}
								unset($data1);
							}
						}
					}
				}	
			}
			//首先判断
			
			
		
			
			
			
			$cache ->Clean(WL_DATA.DS."Cache".DS);
			$cache->Delete('web_url_'.LANG);
			//获取栏目之后自动生成各个版本的栏目
		
			alert('添加成功！',U($m.'/dev'));
			
		}
		$pid = gpc('pid');
		$cattype = $db->select("select * from category where parentid=0 AND id>0  order by weight asc,id asc");
		
		require tpl('dev/dev');
	break;
	
	
	case 'edit':
		$id = gpc("id");
		$sub = gpc("sub");
		if($sub){
			$istb = gpc('istb');
			$tbids = gpc('tbids');
		
			$phpscript = gpc("phpscript");
			$phpscript = join("|",$phpscript);
			set_gpc('phpscript',$phpscript);
			$data = array();
			$ident = trim(gpc('ident'));
			if($ident)
			$data['ident'] = $ident;
			 
			$data['dir'] = trim(gpc('dir'));
			
			$dirs = $db->t('category')->where(" dir='".$data['dir']."' and lang='".LANG."' ")->FindData('id');
		
			if($dirs && $dirs['id'] != $id){
				alert("该栏目自定义链接已经存在！",'back');
			}
			$addpare = gpc('addpare');
			if($addpare !=1){
				//证明不续上
			    $data['cat_url'] = $data['dir'];
			}else{
				$parentid = gpc('parentid');
				if($parentid==0){
					$data['cat_url'] = $data['dir'];
				}else{
					
					$fscid = $parentid;
				    $str_url = '';
					$flag = 1;
					while($flag){
						if($categorys[$fscid]['parentid']!=0){
							 if(!$categorys[$fscid]['dir']){
								$flag = 0;
								alert('栏目id为'.$fscid.'没有设置url！');
								break; 
							 }else{
								 $str_url .= '/'.$categorys[$fscid]['dir'];
								 $fscid = $categorys[$fscid]['parentid'];
							 }
						}else{
							$str_url .= '/'.$categorys[$fscid]['dir'];
							$flag = 0;
							break;
						}
					}
					
					$data['cat_url'] = implode('/',array_reverse(explode('/',trim($str_url,'/')))).'/'.$data['dir'];
				}
			}
			$oldurl = $db->t('category')->where("  id='$id' ")->FindData('cat_url,isend');
			$isend = gpc('isend');
			if($isend==1){
				$data['isend'] = 1;
			}else{
				$data['isend'] = 0;
			}
			
			$db->data($data)->save("category");

			if($istb && $tbids){
				$datas = $db->t('category')->where("id='$id'")->FindData(" `cattype`,`subtype`,`fields`,`template`,`phpscript`");
                $db->t('category')->UpdateTable($datas, array(" `id` IN ($tbids) "));
			}
			//如果修改了栏目链接，则它本级或者下级的文章URL就得变化了
			
		
			//不相等的话就修改了
			if($data['cat_url'] != $oldurl['cat_url']){
                /* $replace = $oldurl['cat_url'];
				 $to = str_replace('/','_',$data['cat_url']);
				//更新文章的URL
				if($oldurl['isend']==0){
				  
				   //首先更新本级的文章URL
				   $db->query("update article set art_url=REPLACE(art_url,'$replace','$to') WHERE catid='$id' ");	
				}
				//查找下级
				$sonidarr = get_sonids($id);
				
				foreach($sonidarr as $k=>$v){
					if($categorys[$v]['addpare']==1){
						//更新栏目的URL
						$db->query("update category set cat_url=REPLACE(cat_url,'$replace','".$data['cat_url']."') WHERE id='$v' ");
						//更新文章的URL				
						$db->query("update article set art_url=REPLACE(art_url,'$replace','$to') WHERE catid='$v' ");	
					}
				}*/
				$allarticles = $db->t('article')->where("catid='".$id."'")->all();
				
				foreach($allarticles as $k=>$v){
					if(!isset($categorys[$v['catid']])) continue;
					$url  = $categorys[$v['catid']]['cat_url'];
					$url = str_replace("/",'_',$url);
					$url = $url.'_'.$v['id'];
					$db->query(" update article set art_url='".$url ."' where id='".$v['id']."'");
				}	
			}
			$idf = $cache->Delete('_categorys_'.LANG);
			$cache->Delete('web_url_'.LANG);
			
			alert("修改成功！",U($m.'/dev'));
		}
		$r = $db->find("select * from category where id='$id'");
		$cattype = $db->select("select * from category where parentid=0 order by weight desc,id asc");
		
		$phpscript = explode("|",$r['phpscript']);
		require tpl("dev/dev");
	break;
	case 'del':
		$id = gpc("id");
		if($db->find("select id from category where parentid='$id'")) alert("该栏目下还有下级栏目，不能删除");
		$db->query("delete from category where id='$id' ");
		$db->query("delete from article where catid='$id'");
		$db->query("delete from page where id='$id'");
		$idf = $cache->Delete('_categorys_'.LANG);
		alert("删除成功！");
	break;
	
	case 'group':
		$submit = gpc('submit');
		$cachefile =  WL_STATIC.DS.'config'.DS.'menugroup.php';
		if($submit){
			$data = gpc('data');
			$arr = array();
			for($i=0;$i<count($data);$i=$i+3){
				if($data[$i]){
					$data[$i] = stripslashes($data[$i]);
					$arr[] = array('name'=>$data[$i],'show'=>$data[$i+1],'icon'=>$data[$i+2]);
				}
			}
			$str = "<?php return ".var_export($arr,true).';';
			file_put_contents($cachefile,$str);
			$idf = $cache->Delete('_categorys_'.LANG);
			alert("保存成功！");
		}else{
			$data = require $cachefile;
			require tpl('dev/dev');
		}
	break;
	default:
		$sub = gpc("sub");
		if(!empty($sub)){
			$data = gpc("data");

			foreach($data as $k => $r){
				$setWhere = '';
				$menugroup = @$r['menugroup'];
				if(isset($r['title'])){
					$title = @$r['title'];
					$setWhere .= ",title='$title'";
				}else{
                    $setWhere .= ",title=''";
                }
				if(isset($r['thumb'])){
					$thumb = @$r['thumb'];
					$setWhere .= ",thumb='$thumb'";
				}else{
                    $setWhere .= ",thumb=''";
                }
				if(isset($r['keywords'])){
					$keywords = @$r['keywords'];
					$setWhere .= ",keywords='$keywords'";
				}else{
                    $setWhere .= ",keywords=''";
                }
				if(isset($r['description'])){
					$description = @$r['description'];
					$setWhere .= ",description='$description'";
				}else{
                    $setWhere .= ",description=''";
                }
				if(isset($r['content'])){
					$content = @$r['content'];
					$setWhere .= ",content='$content'";
				}else{
                    $setWhere .= ",content=''";
                }
				$template = @$r['template'];
				$pagesize = @$r['pagesize'];
				$weight   = @$r['weight'];
				


				$show = @$r['show'];
				$db->query("update category set menugroup='$menugroup' $setWhere,template='$template',pagesize='$pagesize',weight='$weight',`show`='$show' where id='$k' ");

			}
			$idf = $cache->Delete('_categorys_'.LANG);
			alert("提交成功！");
		}
		$group = require  WL_STATIC.DS.'config'.DS.'menugroup.php';

		$category = $db->select("select * from category where parentid=0 order by weight asc,id asc");
		require tpl('dev/dev');
}



function  OutCatgorys($categoryid,$layer,$s='',&$data='',&$catgorys="")
	{  
	    global $allcatgorys,$group,$skins_admin,$debug;

        $rs = array();
		foreach($allcatgorys as $erji){
			if($erji["parentid"] == $categoryid)
		      $rs[$erji["id"]] = $erji ;
		}
		if ($categoryid != "0"){
				$layer++; //默认为第一层 ,以前有实例是数据库一列保存层的数据，这里是自动计算，默认为0
		}
	  if($rs){
		foreach($rs as $r)
		{
			
			$span ="";
			$str="";
			  if ($categoryid != '0')
					{
	
						for ($i = 0; $i < $layer; $i++)//如果i=0，显示的时候第一级菜单就少了个空格
						{
							$span .= "&nbsp;&nbsp;&nbsp;&nbsp;";
						}
						$span.= "┠";//添加前面的空格   
					}
			/*if($categoryid!=0){
			   $catgorys .=' <tr style="display:'.($categoryid==0?"table-row;":'none').'" pid="'.($categoryid==0?$r["id"]:$r["parentid"]).'"><td colspan="15"><table width="100%">';	
			}*/
			$all_sons = implode(",",get_all_sonids($r["id"]));
			$catgorys .='<tr id="tr'.$r["id"].'" onMouseOver="this.bgColor=\''.($categoryid==0?"#FFFCEF":"#F2F2F2").'\'" onMouseOut="this.bgColor=\''.($categoryid==0?"#fff":"#ffffff").'\'" style="display:'.($categoryid==0?"table-row;":"none").'" class="ptr tr-bg-'.$layer.'" pid="'.($categoryid==0?$r["id"]:$r["parentid"]).'" chilid= "'.$r["id"].'" '.($r["id"]==-1?'style="color:#666;"':"").($categoryid==0?' bgcolor="#fff"':"").'>';
			        $sons = implode(",",get_sonids($r["id"]));
					
                	$catgorys .='<td align="center">';
					if($sons)
					$catgorys .='<span onClick="_Setstatu(\''.$sons.'\',this)" style="cursor:pointer;color:#7D7D7D" data-status="0" id="img_click_'.$r["id"].'" data-handid="'.$r["id"].'"  data-allsons="'.$all_sons.'" class="glyphicon glyphicon-plus"></span>';
					$catgorys .='</td>';
					$catgorys .='<td align="center">
					<input type="checkbox" name="id" class="idars form-control" id="cat_'.$r["id"].'" data-ids="'.$sons.'" value="'.$r["id"].'">
					</td>';
                   $catgorys .='<td align="center">
					<select name="data['.$r["id"].'][menugroup]" class="form-control">';
					if($categoryid==0)
					{
						foreach($group as $g){
							
						  if ($debug){
                          $catgorys .=' <option value="'.$g["name"].'" '.($r["menugroup"]==$g["name"]?"selected":"").'>'.$g["name"].'</option>';  }
                          else{
							  if($g["name"]=='内容管理')
							   $catgorys .=' <option value="'.$g["name"].'" '.($r["menugroup"]==$g["name"]?"selected":"").'>'.$g["name"].'</option>';  
							  
							  }
						}
						$catgorys .='<option value="-1" '.($r["menugroup"]==-1?"selected":"").' style="background-color:#FFC;">不显示</option>';	
                   
                    }else{
					    $catgorys .='<option value="0" '.($r["menugroup"]==0?"selected":"").'>显示</option>
                    <option value="-1" '.($r["menugroup"]==-1?"selected":"").' style="background-color:#FFC;">不显示</option>';	
					} 
                 $catgorys .=' </select></td>
					<td align="center" width="50">'.$r["id"].'</td>
					<td align="left" style="text-align:left"> <span class="glyphicon '.$r['icon'].'" style="display:block; line-height:18px; float:left; padding-left:10px; font-size:20px;" id="glyphicon_icon"></span>'.$span.$r["catname"].'</td>
					<td align="center">';
					$str = "";
					if($r["id"]<0) $str = "系统栏目";elseif($r["cattype"]=='article') $str = "文章栏目";elseif($r["cattype"]=='page') $str = "独立页面";else $str = "自定义处理";
					$catgorys .=$str.'</td>
					<td align="center">';
					
					if($r["id"]>-100){
						$str = '<input name="data['.$r["id"].'][show]" type="checkbox" value="1" '.($r["show"]>0?"checked":"").'>';
					}
					$catgorys .= $str.'</td>';
					
				
					
				  if ($debug) {
					    $catgorys .='<td align="center">';
					  	$str = "";
					   
						if($r["id"]>-100){
							 if($r["cattype"]!='diypage'){
								 $str = '<input name="data['.$r["id"].'][title]" type="checkbox" value="1" '.(!empty($r["title"])?"checked":"").'>';
							 }
						}
						$catgorys .= $str.'</td>';
						//thumb
						$catgorys .='<td align="center">';
						$str = "";
						if($r["id"]>-100){
							 if($r["cattype"]!='diypage'){
								 $str = '<input name="data['.$r["id"].'][thumb]" type="checkbox" value="1" '.(!empty($r["thumb"])?"checked":"").'>';
								 }
						}
						
						$catgorys .= $str.'</td>';
						//keywords
						 $str = "";
						 $catgorys .='<td align="center">';
						if($r["id"]>-100){
							 if($r["cattype"]!='diypage'){
								 $str = '<input name="data['.$r["id"].'][keywords]" type="checkbox" value="1" '.(!empty($r["keywords"])?"checked":"").'>';
							 }
						}
						$catgorys .= $str.'</td>';
					   //description
						 $str = "";
						 $catgorys .='<td align="center">';
						if($r["id"]>-100){
							 if($r["cattype"]!='diypage'){
								 $str = '<input name="data['.$r["id"].'][description]" type="checkbox" value="1" '.(!empty($r["description"])?"checked":"").'>';
								 }
						}
						$catgorys .= $str.'</td>';
						  //content
						 $str = "";
						 $catgorys .='<td align="center">';
						if($r["id"]>-100){
							 if($r["cattype"]!='content'){
								 $str = '<input name="data['.$r["id"].'][content]" type="checkbox" value="1" '.(!empty($r["content"])?"checked":"").'>';
								 }
						}
						$catgorys .= $str.'</td>';
				 }
					
					  //template
					 $str = "";
					 $catgorys .='<td align="center">';
					if($r["id"]>-100){
						 if($r["cattype"]!='template'){
							 $str = '<input name="data['.$r["id"].'][template]" type="text" value="'.(!empty($r["template"])?$r["template"]:"").'" class="form-control">';
							 }
					}
					$catgorys .= $str.'</td>';
					
				  
					
					  //pagesize
					 $str = "";
					 $catgorys .='<td align="center">';
					if($r["id"]>-100){
						 if($r["cattype"]!='pagesize'){
							 $str = '<input name="data['.$r["id"].'][pagesize]" type="text" value="'.(!empty($r["pagesize"])?$r["pagesize"]:"").'" class="form-control">';
							 }
					}
					$catgorys .= $str.'</td>';
					
					$catgorys .= '<td align="center"><input name="data['.$r["id"].'][weight]" type="text" value="'.$r["weight"].'" style="width:60px;text-align:center" class="form-control"></td>
					<td align="center"><div  class="btn-group" role="group">';
					
					if($r["id"]>-100){
						$catgorys .= '
						<a href="?action=add&pid='.$r["id"].'" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>子栏目</a>
						<a href="?action=edit&id='.$r["id"].'"  class="btn btn-info"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a>
						<a href="?action=del&id='.$r["id"].'"  class="btn btn-danger" onClick="return confirm(\'删除后不能恢复，您确定要删除吗！\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>';
					}
					$catgorys .='</div></td>
					</tr>';
					
					/*if($categoryid!=0){
			           $catgorys .='</table></td></tr>';	
			        }*/
			OutCatgorys($r["id"],$layer,$s,$data,$catgorys);
		}
	  }
		return $catgorys;
   }
  
function fetgroupcates($categoryid,$layer,$s='',&$data='',&$catgorys="")
	{  
	    global $allcatgorys,$catgroupid;
		
        $rs = array();
		foreach($allcatgorys as $erji){
			if($erji["parentid"] == $categoryid)
		      $rs[$erji["id"]] = $erji ;
		}
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
							$span .= " &nbsp;&nbsp;";
						}
						$span.= "┠";//添加前面的空格   
						
						
					}
				if($r['catgroup'] == $catgroupid)	
				echo  '<p>'.$span.'<input type="checkbox" name="tbid[]" class="tbid_check" checked value="'.$r['id'].'">'.$r['catname'].'('.$r['lang'].')</p>';
			   fetgroupcates($r["id"],$layer,$s,$data,$catgorys);
               }
}

function get_by_sonids($id,$categorys,&$data=array()){
		$rs = isset($categorys[$id])?$categorys[$id]:array();
		if(!in_array($id,$data)){
		   $data[] = $id;	
		}
		foreach($rs as $r)
		{   if(!in_array($r['id'],$data)){
		       $data[] = $r['id'];
		    }
			get_by_sonids($r["id"],$categorys,$data);
		}
		return $data;
}

function get_cates_by_until($allcatgorys){
	 $cates = array();
	 foreach($allcatgorys as $k=>$v){
		 $cates[$v['parentid']][$v['id']] = array('id'=>$v['id'],'parentid'=>$v['parentid']);
	 }
	 return 	$cates;
}

function unlink_ids($ids,$allcatgorys){
	if(!$ids) return array();
	foreach($allcatgorys as $k=>$v){
		if(in_array($v['id'],$ids)){
			unset($allcatgorys[$k]);
		}
	}
	return $allcatgorys;
}