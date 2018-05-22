<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>慧名科技</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>


<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>


</head>

<body>
    <div class="form">
    
<!--if $action=='list' -->
	<div class="searchbar clearfix">
     <ul class="nav nav-tabs" role="tablist" style="border:none">
         <li role="presentation" style="border:none">
            <form id="search" action="" method="post" class="navbar-form navbar-left">
             <div class="form-group" style="float:left; border:none">
             <span class="fl">栏目名称：</span>
                 <input type="text" class="form-control" name="keyword" style="width:150px; float:left;"  placeholder="Search" value="{$keyword}">
                 </div>
               <button type="submit" class="btn btn-default" value="搜索" style=" float:left; margin-left:10px; margin-top:0" /><span class="glyphicon glyphicon-ok">搜索</span></button>
            </form>
            </li>
        </ul>
	</div>
 

	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr class="tr_line">
			<th width="20">ID</th>
			<th width="120">栏目名称</th>
			<th align="left">SEO优化标题</th>
            <th align="left">SEO关键词</th>
            <th align="left">SEO描述</th>
            <th align="left">栏目缩略图</th>
			<th width="120">操作</th>
		</tr>
		<!--foreach $list -->
		<tr>
			<td align="center">{$id}</td>
			<td align="center">{$catname}</td>
			<td>{$cattitle}</td>
            <td>{$keywords1}</td>
            <td><div class="">{$description1}</div></td>
            <td>{if $pic}<img src="{BASEURL}/{$pic}" width="100" height="100">{/if}</td>
			<td align="center">
             <div class="btn-group" role="group">
				<a class="btn btn-success" href="?action=edit&id={$id}&page={$dqpage}"> <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>编辑</a>
		     </div>
			</td>
		</tr>
		<!--/foreach -->
        
	</table>
 	<div class="page_box "><ul class="pagination">{$page}</ul></div>
<!--/if -->

<!--if $action=='edit' -->
<form name="form1" method="post" class="system">
<table width="90%" border="0" cellspacing="0" cellpadding="0" class="form">
	<tr>
		<td width="15%">栏目名</td>
		<td width="85%"><input name="catname" type="text" style="width:480px; float:left;" value="{$r.catname}" class="form-control" > &nbsp;&nbsp;</td>
	</tr>
     <tr>
		<td width="15%">栏目(英文名)</td>
		<td width="85%"><input name="ename" type="text" style="width:480px; float:left;" value="{$r.ename}" class="form-control" > &nbsp;&nbsp;</td>
	</tr>
	<tr>
		<td width="15%">电脑版SEO标题</td>
		<td width="85%">
		<input name="cattitle" type="text" style="width:480px; float:left;" value="{$r.cattitle}"  class="form-control" >&nbsp;&nbsp;
		</td>
	</tr>
    
<tr>
		<td width="15%">电脑版SEO关键词</td>
		<td width="85%">
		<textarea name="keywords1" type="text" style="width:480px; float:left;"  class="form-control" >{$r.keywords1}</textarea>
		</td>
	</tr>
    <tr>
		<td width="15%">电脑版SEO描述</td>
		<td width="85%">
		<textarea name="description1" type="text" style="width:480px; float:left;" class="form-control" >{$r.description1}</textarea> &nbsp;&nbsp;
		</td>
	</tr>
    
    <tr>
		<td width="15%">手机版SEO标题</td>
		<td width="85%">
		<input name="mbtitle" type="text" style="width:480px; float:left;" value="{$r.mbtitle}"  class="form-control" >&nbsp;&nbsp;
		<span>如果不设置，会跟电脑版的一样</span></td>
	</tr>
    
<tr>
		<td width="15%">手机版SEO关键词</td>
		<td width="85%">
		<textarea name="mbkeywords" type="text" style="width:480px; float:left;"  class="form-control" >{$r.mbkeywords}</textarea>&nbsp;&nbsp;<span>如果不设置，会跟电脑版的一样</span>
		</td>
	</tr>
    <tr>
		<td width="15%">手机版SEO描述</td>
		<td width="85%">
		<textarea name="mbdescription" type="text" style="width:480px; float:left;" class="form-control" >{$r.mbdescription}</textarea> &nbsp;&nbsp;<span>如果不设置，会跟电脑版的一样</span>
		</td>
	</tr>
    
    
    <tr>
		<td width="15%">PC栏目缩略图</td>
		<td width="85%">
		<input name="pic" type="text" _type="file" style="width:480px; float:left;" value="{$r.pic}" class="form-control" > &nbsp;&nbsp;
		</td>
	</tr>
      <tr>
		<td width="15%">手机栏目缩略图</td>
		<td width="85%">
		<input name="mbpic" type="text" _type="file" style="width:480px; float:left;" value="{$r.mbpic}" class="form-control" > &nbsp;&nbsp;
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
        <input type="hidden" name="id" value="{$r.id}">
        <input type="hidden" name="page" value="{$dqpage}">
        <input type="hidden" name="lang" value="{LANG}">
        <input type="hidden" name="session_id" value="{session_id()}">
        <input name="submit" type="submit" value="提交" class="btn btn-success"> <a href="javascript:history.back()">不修改，返回</a></td>
	</tr>
</table>
</form>
<!--/if -->
   </div>
</body>
</html>