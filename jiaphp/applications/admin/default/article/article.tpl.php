<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>慧名科技内容管理系统</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/editor.js" id="cms_editor" use="{S('cms_editor')}" pb="{$skins_index}"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>
<script>
function loading(){
	var title = $("input[name=title]").val();
	if(title != undefined &&title==''){
		 $("#article_nav li").eq(0).click();
		layer.msg('请输入标题');
		$("input[name=title]").focus();
		return false;
	}
	
	layer.msg('不好！！也许有远程图片需要下载，卡住了，请耐心等待！');
}
var usercut_flag = 0;
function usercut(){
	$(".usercut").removeClass('hide');
	if(usercut_flag==0){
		$(".usercut").fadeIn();
		usercut_flag = 1;
	}else{
	    $(".usercut").fadeOut();
		usercut_flag = 0;
	}
}

function set_times(obj,id,catid){
	var old = $(obj).data('old');
	var v = $(obj).val();
	if(old!=v){
		$.post("{U($m.'/article')}",{action:'edit',target:'times',id:id,times:v,catid:catid},function(data){
		    if(data.error==0){ 
			  $(obj).data('old',v);
			  layer.msg('修改成功');
			  return true;
			}
		},'json');
	}
}
</script>
</head>

<body>
<!--if empty($action) -->
<style>
input.timeinput{ border:0; background:none; height:28px; line-height:28px; padding:4px}
input.timeinput:hover,.form input.iptext:hover{ border:1px solid #46B8DA}
.form input.iptext, .form textarea.iptext{ border:0}

</style>
	<div class="subnav">
   
    <span>{$category.catname}</span> - {if $category.e_subtype}<a href="{U($m.'/article')}?action=e_subtype&catid={$catid}" class="editicon">更改分类</a> -{/if} 
    {if $category.isend!=1}
    <a href="{U($m.'/article')}?action=add&catid={$catid}" class="btn btn-default"><span class="glyphicon glyphicon-plus" aria-hidden="true" style=" color:#F60"></span>添加数据</a>
    {/if}
  
    </div>
	<div class="searchbar clearfix" >
		<form method="post" class="fkdsd">
           <ul>
			<li style="float:left">关键词：</li>
            
            <li style="float:left"><input name="keyword" type="text" style="width:180px; float:left;" value="{$keyword}" class="form-control" > &nbsp;&nbsp;
           </li>
			<!--if $category.subtype -->
			<li style="float:left">分类：</li>
            <li style="float:left"><select name="type" class="form-control" style="float:left; width:180px;">
				<!--foreach $category.subtype $key $value -->
					<option value="{$key}" {if $type==$key}selected{/if}>{$value}</option>
				<!--/foreach -->
				</select> &nbsp;&nbsp;
			<!--/if -->
          </li>
			<li style="float:left">排序：</li>
            <li style="float:left"><select name="order" class="form-control w400" style="float:left;width:100px;">
                    <option value="" {if $order==''}selected{/if}>默认</option>
            	    <option value="is_top" {if $order=='is_top'}selected{/if}>置顶</option>
					<option value="weight" {if $order=='weight'}selected{/if}>权重</option>
                    <option value="createtime" {if $order=='createtime'}selected{/if}>按发表时间</option>
					<option value="click" {if $order=='click'}selected{/if}>按点击数</option>
				</select> &nbsp;&nbsp;
          </li>
			<li><button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-ok">提交</span></button></li>
            	<li><a href="{U_catid($catid)}" target="_blank" class="btn btn-success"><span class="glyphicon glyphicon-zoom-in"></span>查看文章列表</a></li>
                <li><a href="?action=edit&catid={$catid}&refresh=url" class="btn btn-success"><span class="glyphicon glyphicon-refresh"></span>更新文章URL</a></li>
        
            </ul>
		</form>
	</div>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	<tr>
    <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
	<th width="5%">ID</th>
    <th width="10%" align="left">栏目</th>
	<th width="*" align="left">标题</th>
	<!--if $category.subtype --><th>分类</th><!--/if -->
	<!--if $e_sort --><th>排序</th><!--/if -->
    <!--if $hasclick --><th>点击</th><th>实际点击</th><!--/if -->
    <th width="20%" align="left">添加时间</th>
	<th width="22%">操作</th>
	</tr>
	<!--foreach $list $r -->

            <tr><td width="5%" align="center" style="position:relative"><input type="checkbox" name="id" class="idars form-control" value="{$r.id}">{if $r.is_top}<span style="position:absolute; top:10px; right:10px"><img src="{$skins_admin}/top.png"></span>{/if}</td>
			<td width="5%" align="center">{$r.id}</td>
            <td width="10%" align="center">{eval echo $categorys[$r['catid']]['catname'];}</td>
			<td width="*">{$r.title}</td>
			<!--if $category.subtype --><td width="*" align="center"><?php echo isset($category['subtype'][$r['type']])?$category['subtype'][$r['type']]:''; ?></td><!--/if -->
            <!--if $e_sort --><td width="*" align="center">{$r.weight}</td><!--/if -->
			<!--if $hasclick --><td width="*" align="center">{$r.click}</td><td width="*" align="center">{$r.realclick}</td><!--/if -->
            <td width="20%"><input type="text" data-old="{date("Y-m-d H:i:s",$r.createtime)}" value="{date("Y-m-d H:i:s",$r.createtime)}" class="timeinput" onBlur="set_times(this,'{$r.id}','{$r.catid}')"></td>
			<td width="22%" align="center">
              <div class="btn-group" role="group" aria-label="...">
				<a href="?action=edit&rcatid={$catid}&catid={$r.catid}&id={$r.id}&page={gpc('page')}" class="btn btn-info"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a>
				<a href="?action=del&catid={$r.catid}&id={$r.id}"  class="btn btn-danger hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
                <a href="{U_aid($r['id'],$r['catid'])}?forcelang={LANG}" target="_blank"  class="btn btn-success"><span class="glyphicon glyphicon-forward" aria-hidden="true"></span>浏览</a>
                
                </div>
			</td>
		</tr>
	<!--/foreach -->
      <tr>
         
      <td colspan="9">
  <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-info fanxuan" id="fanxuan"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>反选</button>
  <button type="button" class="btn btn-danger" onClick="delall('{U($m.'/article')}?action=delall&catid={$catid}','删除')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</button>
  <button type="button" class="btn btn-warning" onClick="delall('{U($m.'/article')}?action=settop&catid={$catid}','置顶')"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>置顶</button>
  <button type="button" class="btn btn-info" onClick="delall('{U($m.'/article')}?action=endtop&catid={$catid}','取消置顶')"><span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>取消置顶</button>
  <button type="button" class="btn btn-success" onClick="setCategory('{U($m.'/article')}?action=setcategory&catid={$catid}','移动',0)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>移动</button>

</div>
      </td>   
          </tr>
	</table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>
<script type="text/javascript">
$(function(){

  $(".message_close").click(function(){
	  $("#setcategory").fadeOut();
  });
 });

var catid ='{$catid}';
function setCategory(url,name,type){
	 var ids = $("input[name=id]:checked"); 
	  if(ids.length == 0) {
			window.top.message("您什么都还没有选择!");
			 return false;
	 } 
	 if(type==0){
		 $("#setcategory").show();
		  return false;
	 }else{
		
		var str = "";
			 $.each(ids,function(index,value){
				  str = str +","+$(this).val();
		 });
		 var parentid = $("#parentid").val();
		 if(parentid==catid){
			window.top.message('栏目相同，请重新选择！',500); 
			return false;
		 }
		  if(confirm("您真的要"+name+"吗？")){
			 $.post(url,{handids:str,tocatid:parentid},
				function(data){
					 window.top.message(data.msg,500);
					 setTimeout(function(){window.location.reload();},1000);
			 },"json"); 
		};
	}
}


 </script>   
 <div class="setcategory message_setcate" id="setcategory">
 <div class="message_close">X</div>
   <div class="setcategory-top-select">
 	<select name="parentid" id="parentid" class="form-control" style="width:200px;">
          {eval $str=''; echo fetchZiDianModel(0,0,0,$str,1);}
	</select> 
    </div>
    <div class="setcategory-btn-select">
    <button type="button" class="btn btn-success" onClick="setCategory('{U($m.'/article')}?action=setcategory&catid={$catid}','移动',1)">确定</button>
    </div>
   </div>
<!--elseif $action=="add" -->
	<form method="post" class="system" onSubmit="return loading();">
     <ul class="nav nav-tabs">
        <ol class="breadcrumb">
          <li><i class="glyphicon glyphicon-map-marker" style="margin-right:5px"></i><a href="{U($m.'/admin')}?action=homepage"  target="main">首页</a></li>
          <li><a href="{U($m.'/admin')}?action=sub&catid={$catid}" target="main">{eval echo $categorys[$catid]['catname']}</a></li>
          <li class="active">添加</li>
        </ol>
    </ul>
    <ul class="nav nav-tabs" id="article_nav" style="margin-top:10px;margin-bottom:10px">
     <li id="nav_common" role="presentation" data-id="common" class="active"><a href="javascript:;">常规信息</a></li>
     <li id="nav_content" role="presentation" data-id="content"><a href="javascript:;">详细描述</a></li>
      <li id="nav_seo" role="presentation" data-id="seo"><a href="javascript:;">SEO描述</a></li>
    </ul>
    
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<!--if $category.title -->
		<tr class="common seo">
			<td width="90">标题：</td>
			<td><input name="title" type="text" style="width:300px; display:inline-block" class="form-control"> <font color="#999999">*请输入标题</font></td>
	
		</tr>
        <tr  class="common">
			<td width="90">副标题：</td>
			<td><input name="etitle" type="text" style="width:300px; display:inline-block" class="form-control"> <font color="#999999">*请输入副标题</font></td>
	
		</tr>
		<!--/if -->
        
         <tr  class="common">
			<td width="90">属性：</td>
			<td align="left">
            	{foreach $flags $k $v}<input class="np" type="checkbox" name="flags[]" id="flags{$k}" value="{$k}">{$v}[{$k}] {/if}       </td>
	
		</tr>
        
        <tr  class="common">
			<td width="90">跳转url：</td>
			<td align="left">
            	<input name="out_url" type="text" style="width:300px; display:inline-block" class="form-control"> <font color="#999999">*请输入该文章跳转url，不填不跳转</font>
                </td>
	
		</tr>
        
        <tr  class="common">
			<td>TAGS</td>
			<td><input name="tags" type="text" style="width:300px; float:left" value="" id="tags" class="form-control"><font color="#999999">*请输入TAG</font></td>
		</tr>
        
        <tr  class="common">
			<td>显示模板</td>
			<td><input name="laytpl" type="text" style="width:300px; float:left" value="" id="laytpl" class="form-control"><font color="#999999">*如果独立设计详情页，请输入</font></td>
		</tr>
        
		<!--if $category.subtype -->
		<!--eval $_category = explode("\\r\\n",$category.subtype); -->
		<tr  class="common">
			<td width="90">分类：</td>
			<td>
			<select name="type" class="form-control w400">
				<!--foreach $_category $r -->
					<!--if strpos($r,'|')!==false  -->
						<!--eval $categorys = explode("|",$r) -->
						<option value="{$categorys.0}">{$categorys.1}</option>
					<!--/if -->
				<!--/foreach -->
			</select>
			</td>
		</tr>
		<!--/if -->
		<!--if $category.thumb -->
		<tr  class="common">
			<td>缩略图</td>
			<td><input name="thumb" type="text" _type="file" style="width:300px; display:inline" value="" id="thumb" class="form-control"><button type="button" onClick="usercut()" class="btn btn-default" >自定义尺寸</button>
             <input type="text" name="cutwidth" value="" class="form-control usercut hide" placeholder="长度" style="width:80px; display:inline-block">
             <input type="text" name="cutheight" value=""  class="form-control   usercut hide" placeholder="高度"  style="width:80px; display:inline-block"><span class="usercut hide">PX</span>
            </td>
		</tr>
		<!--/if -->
        
        <!--if $category.thumb -->
		<tr  class="common">
			<td>缩略图alt</td>
			<td><input name="alt" type="text" style="width:300px; float:left" value="" id="alt" class="form-control"><font color="#3366FF">*可以输入图片的alt信息</font></td>
		</tr>
		<!--/if -->
		<!--if $category.keywords -->
		<tr  class="seo hide">
			<td>关键词</td>
			<td><textarea name="keywords" style="width:500px; height:48px; overflow:hidden" class="form-control"></textarea></td>
	
		</tr>
		<!--/if -->
		<!--if $category.description -->
		<tr class="seo hide">
			<td>描述</td>
			<td><textarea name="description" style="width:850px; height:150px;" class="form-control"></textarea></td>
	
		</tr>
		<!--/if -->
		<!--if $category.content -->
         <tr  class="content common">
			<td>下载远程图片？</td>
			<td><input type="checkbox" name="is_yc" id="is_yc" value="1" checked></td>
		</tr>
		<tr class="content common">
			<td>内容</td>
			<td><textarea name="content" style="width:100%;" id="content" _type="editor"></textarea></td>
		</tr>
       
		<!--/if -->
		
		
		<!--if $category.fields -->
			<!--eval $_fields = explode("\\r\\n",$category.fields) -->
			<!--foreach $_fields $_fields_row-->
				<!--if !empty($_fields_row) || substr_count($_fields_row,'|')>=2-->
					<!--eval @list($title,$name,$type,$desc,$value,$default) = explode('|',$_fields_row) -->
					
						<!--if $type=='input' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><input name="data[{$name}]" type="text" style="width:300px;" value="{$value}" class="form-control"> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='textarea' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" style="width:680px; height:80px;" class="form-control">{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='select' -->
						<!--eval $select=explode(',',$default); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<select name="data[{$name}]" class="form-control">
								<!--foreach $select $r -->
									<option value="{$r}" {if $value==$r}selected{/if}>{$r}</option>
								<!--/foreach -->
								</select>
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='radio' -->
						<!--eval $radio=explode(',',$default); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<!--foreach $radio $r -->
									<input name="data[{$name}]" type="radio" value="{$r}"  {if $value==$r}checked{/if}> {$r}&nbsp;&nbsp;
								<!--/foreach -->
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='checkbox' -->
						<!--eval $checkbox=explode(',',$default);$value=explode(',',$value); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<!--foreach $checkbox $r -->
									<input name="data[{$name}][]" type="checkbox" value="{$r}" {if in_array($r,$value)}checked{/if}> {$r} &nbsp;&nbsp;
								<!--/foreach -->
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='file' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><input name="data[{$name}]" type="text" _type="file" style="width:300px;display:inline" value="{$value}" id="{$name}" class="form-control"> <font color="#999999">{$desc}</font></td>
							
						</tr>
						<!--/if -->
						
						<!--if $type=='minieditor' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" style="width:100%;" id="{$name}" _type="minieditor" >{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='editor' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" style="width:780px;" id="{$name}" _type="editor" >{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
					
				<!--/if -->
			<!--/foreach -->
		<!--/if -->
		<!--if $e_sort -->
		<tr  class="common">
			<td>排序</td>
			<td><input type="text" name="weight" value="100" class="form-control" /><font color="#999999">数值越大越靠后</font></td>
	
		</tr>
        <!--/if-->
        <tr  class="common">
			<td>点击量</td>
			<td><input type="text" name="click" value="{$click_sj}" class="form-control" style="width:300px" /><font color="#999999">输入点击量</font></td>
	
		</tr>
		<tr>
			<td></td>
			<td>
            <input type="hidden" name="lang" value="{LANG}">
            <input type="hidden" name="session_id" value="{session_id()}">
          
            </td>
	
		</tr>
	</table>
      <div class="wl_btn_submit"><input name="submit" type="submit" value="提交保存" class="btn btn-success">
            <input name="returnback" type="button" onClick="history.go(-1);" value="返回上一页" class="btn btn-danger">
            </div>
	</form>
<!--elseif $action=="edit" -->

	<form method="post"  class="system" onSubmit="return loading();">
    <ul class="nav nav-tabs">
        <ol class="breadcrumb">
          <li><a href="{U($m.'/admin')}?action=homepage"  target="main">首页</a></li>
          <li><a href="{U($m.'/admin')}?action=sub&catid={$catid}" target="main">{eval echo $categorys[$catid]['catname']}</a></li>
          <li class="active">{$art.title}</li>
           <li class="active">修改</li>
          
        </ol>
    </ul>
     <ul class="nav nav-tabs" id="article_nav" style="margin-top:10px;margin-bottom:10px">
     <li id="nav_common" role="presentation" data-id="common" class="active"><a href="javascript:;">常规信息</a></li>
     <li id="nav_content" role="presentation" data-id="content"><a href="javascript:;">详细描述</a></li>
      <li id="nav_seo" role="presentation" data-id="seo"><a href="javascript:;">SEO描述</a></li>
    </ul>
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<!--if $category.title -->
		<tr class="common seo">
			<td width="90">标题：</td>
			<td><input name="title" type="text" style="width:300px;"  class="form-control w400" value="{$art.title}"></td>
	
		</tr>
        
        <tr class="common">
			<td width="90">副标题：</td>
			<td><input name="etitle" type="text" style="width:300px;"  class="form-control w400" value="{$art.etitle}"><font color="#999999">*请输入副标题</font></td>
	
		</tr>
		<!--/if -->
        
         <tr  class="common">
			<td width="90">属性：</td>
			<td align="left">
            	{foreach $flags $k $v}<input class="np" type="checkbox" name="flags[]" id="flags{$k}" {if in_array($k,$flag_ar)} checked{/if} value="{$k}">{$v}[{$k}] {/if}</td>
	
		</tr>
        
        <tr  class="common">
			<td width="90">跳转url：</td>
			<td align="left">
            	<input name="out_url" type="text" style="width:300px; display:inline-block" class="form-control" value="{$art.out_url}"> <font color="#999999">*请输入该文章跳转url，不填不跳转</font>
                </td>
	
		</tr>
        
         <tr  class="common">
			<td>TAGS</td>
			<td><input name="tags" type="text" style="width:300px; float:left" value="{$art.tags}" id="tags" class="form-control"></td>
		</tr>
         <tr  class="common">
			<td>显示模板</td>
			<td><input name="laytpl" type="text" style="width:300px; float:left" value="{$art.laytpl}" id="laytpl" class="form-control"><font color="#999999">*如果独立设计详情页，请输入</font></td>
		</tr>
		<!--if $category.subtype -->
		<!--eval $_category = explode("\\r\\n",$category.subtype); -->
		<tr  class="common">
			<td width="90">分类：</td>
			<td>
			<select name="type" class="form-control w400">
				<!--foreach $_category $r -->
					<!--if strpos($r,'|')!==false  -->
						<!--eval $categorys = explode("|",$r) -->
						<option value="{$categorys.0}" {if $categorys.0==$art.type}selected{/if}>{$categorys.1}</option>
					<!--/if -->
				<!--/foreach -->
			</select>
			</td>
		</tr>
		<!--/if -->
		<!--if $category.thumb -->
		<tr  class="common">
			<td>缩略图</td>
			<td><input name="thumb" type="text" _type="file" style="width:300px; float:left; " class="form-control w400" value="{$art.thumb}" id="thumb">
            <button type="button" onClick="usercut()" class="btn btn-default" >自定义尺寸</button>
             <input type="text" name="cutwidth" value="" class="form-control usercut hide" placeholder="长度" style="width:80px; display:inline-block">
             <input type="text" name="cutheight" value=""  class="form-control   usercut hide" placeholder="高度"  style="width:80px; display:inline-block"><span class="usercut hide">PX</span>
            </td>
		</tr>
		<!--/if -->
        
           <!--if $category.thumb -->
		<tr  class="common">
			<td>缩略图alt</td>
			<td><input name="alt" type="text" style="width:300px; float:left" value="{$art.alt}" id="alt" class="form-control"></td>
		</tr>
		<!--/if -->
        
		<!--if $category.keywords -->
		<tr  class="seo hide">
			<td>关键词</td>
			<td><textarea name="keywords" class="form-control w400" style="width:500px; height:48px; overflow:hidden">{$art.keywords}</textarea></td>
	
		</tr>
		<!--/if -->
		<!--if $category.description -->
		<tr  class="seo hide">
			<td>描述</td>
			<td><textarea name="description" class="form-control w400" style="width:850px; height:150px;">{$art.description}</textarea></td>
	
		</tr>
		<!--/if -->
		<!--if $category.content -->
         <tr  class="content common ">
			<td>下载远程图片？</td>
			<td><input type="checkbox" name="is_yc" id="is_yc" value="1" checked></td>
		</tr>
		<tr  class="content common">
			<td>内容</td>
			<td><textarea name="content" style="width:100%; " id="content" _type="editor">{$art.content}</textarea></td>
	
		</tr>
		<!--/if -->
		
		
		<!--if $category.fields -->
			<!--eval $_fields = explode("\\r\\n",$category.fields) -->
			<!--foreach $_fields $_fields_row-->
				<!--if !empty($_fields_row) || substr_count($_fields_row,'|')>=2-->
					<!--eval @list($title,$name,$type,$desc,$value,$default) = explode('|',$_fields_row) -->
						<!--eval $value = @$art[$name]; -->
						<!--if $type=='input' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><input name="data[{$name}]" class="form-control w400" type="text" style="width:300px;" value="{$value}"> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='textarea' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" class="form-control w400" style="width:680px; height:80px;">{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='select' -->
						<!--eval $select=explode(',',$default); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<select name="data[{$name}]" class="form-control w400">
								<!--foreach $select $r -->
									<option value="{$r}" {if $value==$r}selected{/if}>{$r}</option>
								<!--/foreach -->
								</select>
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='radio' -->
						<!--eval $radio=explode(',',$default); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<!--foreach $radio $r -->
									<input name="data[{$name}]" type="radio" class="form-control" style="width:30px; border:none; float:left" value="{$r}" {if $value==$r}checked{/if}> <span class="fl" style="line-height:34px; padding:5px 10px;">{$r}&nbsp;&nbsp;</span>
								<!--/foreach -->
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='checkbox' -->
						<!--eval $checkbox=explode(',',$default); -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td>
								<!--foreach $checkbox $r -->
									<input name="data[{$name}][]" type="checkbox" value="{$r}" {if in_array($r,(array)$value)}checked{/if}> {$r} &nbsp;&nbsp;
								<!--/foreach -->
								<font color="#999999">{$desc}</font>
							</td>
						</tr>
						<!--/if -->
						
						<!--if $type=='file' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><input name="data[{$name}]" type="text" class="form-control w400" _type="file" style="width:300px; display:inline" value="{$value}" id="{$name}"> <font color="#999999">{$desc}</font></td>
							
						</tr>
						<!--/if -->
						
						<!--if $type=='minieditor' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" style="width:100%" id="{$name}" _type="minieditor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
						
						<!--if $type=='editor' -->
						<tr  class="common">
							<td width="90px">{$title}</td>
							<td><textarea name="data[{$name}]" style="width:100%;" id="{$name}" _type="editor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
						</tr>
						<!--/if -->
					
				<!--/if -->
			<!--/foreach -->
		<!--/if -->
		<!--if $e_sort -->
        <tr  class="common">
			<td>排序</td>
			<td><input type="text" name="weight" value="{$art.weight}"  class="form-control w400"/><font color="#999999">数值越大越靠后</font></td>
	
		</tr>
        <!--/if-->
           <tr  class="common">
			<td>点击量</td>
			<td><input type="text" name="click" value="{$art.click}" class="form-control" /><font color="#999999">输入点击量</font></td>
	
		</tr>
        <tr>
			<td></td>
			<td>
             <input type="hidden" name="lang" value="{LANG}">
             <input type="hidden" name="session_id" value="{session_id()}">
             <div class="wl_btn_submit"><input name="submit" type="submit" value="提交保存" class="btn btn-success">
              <input name="returnback" type="button" onClick="history.go(-1);" value="返回上一页" class="btn btn-danger">
             </div>
             </td>
		</tr>
	</table>
	</form>
<!--/if -->
<!--if $action=='e_subtype' -->
	<div class="subnav" style="width:100%; padding-left:0">
     <div class="alert alert-info" role="alert"> 修改栏目分类 <a href="{U($m.'/article')}?catid={$catid}">返回栏目</a></div></div>
	<form method="post" style=" padding-left:20px">
		<table border="0" cellspacing="0" cellpadding="0" class="form">
			<tr>
				<th align="left">分类标识</th>
				<th align="left">分类名称</th>
			</tr>
			<!--foreach $subtype $k $r -->
			<tr>
				<td><input name="data[]" type="text" value="{$k}"   class="form-control"  style="width:60px;"></td>
				<td><input name="data[]" type="text" value="{$r}"   class="form-control" style="width:260px;"> <a href="javascript:void(0)" onClick="d(this);">删除</a></td>
			</tr>
			<!--/foreach -->
			<tr class="table_add">
				<td><a href="javascript:void(0)" onClick="add(this)">添加分类</a></td>
				<td></td>
			</tr>
			<tr>
				<td><input name="submit" type="submit" value="提交" class="btn btn-success"></td>
				<td></td>
			</tr>
		</table>
	</form>
<script type="text/javascript">
function d(obj){
	if(confirm("删除？")) $(obj).parent().parent().remove();
}
function add(obj){
	$(".table_add").before('<tr>\
				<td><input name="data[]" type="text" class="form-control"  style="width:60px;"></td>\
				<td><input name="data[]" type="text" class="form-control"  style="width:260px;"> <a href="javascript:void(0)" onClick="d(this);">删除</a></td>\
			</tr>');
}
</script>
<!--/if -->
</body>
</html>

<script type="text/javascript" src="{$skins_admin}/base.js"></script>