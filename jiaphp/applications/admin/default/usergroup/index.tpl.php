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
<script type="text/javascript" src="{$skins_admin}/base.js"></script>

<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>


<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>


</head>

<body>
<!--if empty($action) -->

	<div class="searchbar">
		<form method="post" class="form-wrap">
			{if in_array("add",$viewar)} <a href="?action=add" class="btn btn-info" style="color:#FFF; margin-right:10px"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加用户组</a>{/if}用户组名称：<input name="keyword" type="text" class="form-control" style="width:180px; display:inline" value="{$keyword}"> &nbsp;&nbsp;
			<input name="submit" type="submit" value="提交" class="btn btn-success">

		</form>
	</div>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	<tr>
    <th width="5%">选</th>
    <th width="5%">id</th>
	<th width="20%">用户组</th>
    <th width="10%" align="left">状态</th>
	<th width="12%">操作</th>
	</tr>
	<!--foreach $list $r -->
		<tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="{$r.id}"></td>
			<td width="5%" align="center">{$r.id}</td>
            <td width="5%" align="center">{eval echo str_replace($keyword,"<span style='color:#f30'>".$keyword."</span>",$r.varname)}</td>
           <td width="5%" align="center">{$r.status}</td>

			<td width="12%" align="center">
			 <div class="btn-group" role="group">
				{if in_array("del",$viewar)}<a href="?action=del&delids={$r.id}"  class="btn btn-danger hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>{/if}

                {if in_array("edit",$viewar)}
                <a href="?action=edit&id={$r.id}" class="btn btn-info"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a>
                {/if}

                {if in_array("grant",$viewar)}
                {if $r.id==0} {if  session('admin_level')==1}<a href="?action=grant&id={$r.id}" class="btn btn-success"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>权限</a>{/if} {else}<a href="?action=grant&id={$r.id}" class="btn btn-success"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>权限</a> {/if}

                {/if}

                </div>

			</td>
		</tr>
	<!--/foreach -->
            <tr>

      <td colspan="8">
  <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-info fanxuan" id="fanxuan"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>反选</button>
   <a href="javascript:;" onClick="delall('usergroup?action=del','删除')" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
</div>
      </td>
          </tr>
	</table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>
{elseif $action=='add' || $action=='edit'}

	<form method="post" class="system">
	<table border="0" cellspacing="0" cellpadding="0" class="form">

		<tr>
			<td width="90">用户组名称：</td>
			<td><input name="data[varname]" type="text" class="form-control" style="width:400px;" value="{if $action=='edit'}{$r.varname}{/if}"></td>

		</tr>

        <tr>
			<td width="90">状态：</td>
			<td>
            <input type="radio" name="data[status]" value="1" {if $action=='add' ||($action =='edit' && $r.status==1)} checked{/if}>启用
            <input type="radio" name="data[status]" value="0" {if $action=='edit' && $r.status==0} checked{/if}>禁用
            </td>

		</tr>

        <tr>
			<td width="90">排序：</td>
			<td><input name="data[listorder]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.listorder}{/if}"></td>

		</tr>
        <tr>
			<td></td>
			<td><input type="hidden" name="id" value="{if $action=='edit'}{$r.id}{/if}"><input name="submit" type="submit" value="提交" class="btn"></td>

		</tr>

        </table>
     </form>
{/if}
</body>
</html>
