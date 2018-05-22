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
</head>

<body>
    <div class="form">
<!--if $action=='list' -->

    <div class="searchbar clearfix">
	<div class="btn-group">
      {if in_array("add",$viewar)}<a href="?action=add"  class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加管理员</a>{/if}</li>
    </div>
     </div>


	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr class="tr_line">
			<th width="5%">ID</th>
			<th width="120">用户名</th>
			<th align="left">用户等级</th>
			<th width="200">操作</th>
		</tr>
		<!--foreach $list -->
		<tr>
			<td align="center">{$id}</td>
			<td align="center">{$username}</td>
			<td><b>{$User_groups.$level}</b></td>
			<td align="center">
            <div class="btn-group" role="group">
				 {if in_array("edit",$viewar)}<a href="?action=edit&id={$id}" class="btn btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>编辑</a>{/if}
				<!--if $id!=1 && $id!=$me['id'] -->
				 {if in_array("del",$viewar)}<a href="?action=del&id={$id}" class="btn btn-danger  hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>{/if}
				<!--/if -->
            </div>
			</td>
		</tr>
		<!--/foreach -->

	</table>

<!--/if -->
<!--if $action=='add' -->
<form name="form1" method="post" class="system">
<table width="90%" border="0" cellspacing="0" cellpadding="0" class="form">
	<tr>
		<td width="15%">用户名</td>
		<td width="85%"><input name="username" type="text" class="form-control w400" /></td>
	</tr>
	<tr>
		<td width="15%">用户等级</td>
		<td width="85%">
			<select name="level" class="form-control w400">

            {foreach $User_groups $K $v}
                {if $K==0 && $me['level']==0}<option value="{$K}" {if $K==1} selected="selected"{/if}>{$v}</option>
                {else}
				<option value="{$K}" {if $K==1} selected="selected"{/if}>{$v}</option>
                {/if}
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td>密码</td>
		<td><input name="password" type="password" id="password" class="form-control w400" ></td>
	</tr>
	<tr>
		<td>再次输入</td>
		<td><input name="password2" type="password" id="password" class="form-control w400"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="提交" class="btn btn-success"></td>
	</tr>
</table>
</form>
<!--/if -->
<!--if $action=='edit' -->
<form name="form1" method="post"  class="system">
<table width="90%" border="0" cellspacing="0" cellpadding="0" class="form">
	<tr>
		<td width="15%">用户名</td>
		<td width="85%"><input name="username" type="text" class="form-control w400" value="{$r.username}" /></td>
	</tr>
	<tr>
		<td width="15%">用户等级</td>
		<td width="85%">
		<!--if $me['level']==1 -->
			<select name="level" class="form-control w400">
			 {foreach $User_groups $k $v}

				<option value="{$k}" {if $r.level==$k} selected="selected"{/if}>{$v}</option>

			{/foreach}
			</select>
		<!--else -->
			<b>{eval echo $User_groups[$r['level']]}</b>
		<!--/if -->
		</td>
	</tr>
	<tr>
		<td>新密码</td>
		<td><input name="password" type="password" id="password" class="form-control w400"></td>
	</tr>
	<tr>
		<td>再次输入</td>
		<td><input name="password2" type="password" id="password" class="form-control w400"></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><input name="submit" type="submit" value="提交" class="btn btn-success"> <a href="javascript:history.back()">不修改，返回</a></td>
	</tr>
</table>
</form>
<!--/if -->
   </div>
</body>
</html>
