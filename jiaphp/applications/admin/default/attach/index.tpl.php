<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>美藤科技</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>

<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>

<style>
img.filetype{ height:40px}
</style>
</head>

<body>
    <div class="form">
    
<!--if $action=='list' || $action=='' -->
	<div class="searchbar clearfix">
     <ul class="nav nav-tabs" role="tablist" style="border:none">
         <li role="presentation" style="border:none">
            <form id="search" action="" method="post" class="navbar-form navbar-left">
             <div class="form-group" style="float:left; border:none">
             <span class="fl">附件名称：</span>
                 <input type="text" class="form-control" name="keyword" style="width:150px; float:left;"  placeholder="Search" value="{$keyword}">
                 </div>
               <button type="submit" class="btn btn-default" value="搜索" style=" float:left; margin-left:10px; margin-top:0" /><span class="glyphicon glyphicon-ok">搜索</span></button>
            </form>
            </li>
        </ul>
	</div>
 

	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr class="tr_line">
            <th width="70"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
			<th width="70">ID</th>
            <th>类型</th>
			<th>名称</th>
            <th>存储名称</th>
			<th align="left">大小</th>
            <th align="left">上传时间</th>
            <th align="left">状态</th>
         
			<th width="120">操作</th>
		</tr>
		<!--foreach $list -->
		<tr>
            <td align="center"> <input type="checkbox" name="id" class="idars form-control" value="{$id}"></td>
			<td align="center">{$id}</td>
             <th width="120" {if $type=='img'}class="js-magnificPopup"{/if}>
             <a href="{if substr($url, 0, 1)=='/'}{$url}{else}{BASEURL}/{$url}{/if}" data-toggle="tooltip" title="" data-original-title="点击下载" {if $type!='img'}target="_blank"{/if}>
                      
             {if $type=='img'}<img src="{if substr($url, 0, 1)=='/'}{$url}{else}{BASEURL}/{$url}{/if}" class="filetype" title="点击放大图片" alt="{$name}">{else}<img  class="filetype" src="{$skins_index}/images/file.png" title="点击放大图片" alt="{$name}"/>{/if}</a>
             </th>
			<td align="center">{$title}</td>
            <td align="center">{$name}</td>
			<td>{$fsize}KB</td>
            <td>{date("Y-m-d H:i:s",$addtime)}</td>
            <td><div class="">{$status}</div></td>
           
			<td align="center">
             <div class="btn-group" role="group">
				<a class="btn btn-danger hp-del"  href="{U($m.'/attach')}?action=del&delids={$id}&page={$dqpage}"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
		     </div>
			</td>
		</tr>
		<!--/foreach -->
        {if $list}
        <tr>
          <td colspan="9"> <div class="btn-group" role="group">
				<a class="btn btn-danger"  onClick="delall('{U($m.'/attach')}?action=del','删除')"> <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
		     </div></td>
        </tr>
        {/if}
	</table>
 	<div class="page_box "><ul class="pagination">{$page}</ul></div>
<!--/if -->


   </div>
</body>
</html>
<script type="text/javascript" src="{$skins_admin}/jquery.magnific-popup.min.js"></script>
<link href="{$skins_admin}/magnific-popup.css" rel="stylesheet" type="text/css">
<script>
$(document).ready(function() {
    $('.js-magnificPopup a').magnificPopup({type:'image'});
});
</script>