<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>美藤科技内容管理系统</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>

<script type="text/javascript" charset="utf-8" src="{$skins_index}/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{$skins_index}/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="{$skins_index}/ueditor/lang/zh-cn/zh-cn.js"></script>

<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>
<style>
.form tr{ border-bottom:1px dashed #ddd; padding:10px; height:70px;}
</style>
</head>
<body>
<ul class="nav nav-tabs" style="margin-left:10px; margin-top:10px;">
 {foreach $set_ars $k $v}
  <li role="presentation" {if $se==$k}class="active"{/if}><a href="{U($m.'/setting')}?se={$k}">{$v}</a></li>
 {/foreach}
</ul>

<!--if empty($action) -->

	<form action="setting?action=save" method="post" class="system">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
   
	<!--foreach $r -->
	
		<!--if $type=='input' -->
		<tr>
			<td width="160px">{$title}</td>
			<td><input name="data[{$name}]" type="text" style="width:400px;" value="{$value}" class="form-control"> <font color="#999999">{$desc}</font></td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
                <a href="?action=del&name={$name}">删除</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='textarea' -->
		<tr>
			<td width="160px">{$title}</td>
			<td><textarea name="data[{$name}]" style="width:450px; height:80px;" class="form-control">{$value}</textarea> <font color="#999999">{$desc}</font></td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='select' -->
		<!--eval $select=explode(',',$default); -->
		<tr>
			<td width="160px">{$title}</td>
			<td>
				<select name="data[{$name}]" class="form-control">
				<!--foreach $select $r -->
					<option value="{$r}" {if $value==$r}selected{/if}>{$r}</option>
				<!--/foreach -->
				</select>
				<font color="#999999">{$desc}</font>
			</td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='radio' -->
		<!--eval $radio=explode(',',$default); -->
		<tr>
			<td width="160px">{$title}</td>
			<td>
				<!--foreach $radio $r -->
                <div style="float:left; padding:0 10px; line-height:38px;">
					<input name="data[{$name}]" type="radio" value="{$r}" class="form-control" {if $value==$r}checked{/if} style="width:20px; float:left; background:none; border:none; box-shadow:none;"> {$r}&nbsp;&nbsp;
                    </div>
				<!--/foreach -->
				<font color="#999999">{$desc}</font>
			</td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
            
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='checkbox' -->
		<!--eval $checkbox=explode(',',$default);$value=unserialize($value); -->
		<tr>
			<td width="160px">{$title}</td>
			<td>
				<!--foreach $checkbox $r -->
					<input name="data[{$name}][]" type="checkbox" class="form-control" value="{$r}" {if in_array($r,(array)$value)}checked{/if} style="width:50px; float:left"> {$r} &nbsp;&nbsp;
				<!--/foreach -->
				<font color="#999999">{$desc}</font>
			</td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='file' -->
		<tr>
			<td width="160px">{$title}</td>
			<td><input name="data[{$name}]" type="text" class="form-control" _type="file" style="width:400px; float:left" value="{$value}" id="{$name}" > <font color="#999999">{$desc}</font></td>
			<td>
			<!--if $debug -->
				{if $issystem}
                    <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='minieditor' -->
		<tr>
			<td width="160px">{$title}</td>
			<td><textarea name="data[{$name}]" style="width:650px;" id="{$name}" _type="minieditor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
		<!--if $type=='editor' -->
		<tr>
			<td width="160px">{$title}</td>
			<td><textarea name="data[{$name}]" style="width:780px;" id="{$name}" _type="editor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
			<td>
			<!--if $debug -->
				{if $issystem}
                <a href="?action=edit&id={$id}">修改</a>
					<!--if $status -->
						<a href="?action=setstatus&status=0&name={$name}">停用</a>
					<!--else -->
						已停用 <a href="?action=setstatus&status=1&name={$name}"><font color="red">启用</font></a>
					<!--/if -->
                    <a href="?action=del&name={$name}">删除</a>
				{else}
					<a href="?action=edit&id={$id}">修改</a>
					<a href="?action=del&name={$name}">删除</a>
				{/if}
			<!--/if -->
			</td>
		</tr>
		<!--/if -->
		
	<!--/foreach -->
	<!--if $debug -->
		<tr>
			<td><a href="?action=add&se={$se}" class="btn btn-danger"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加项目</a></td>
			<td></td>
		</tr>
	<!--/if -->
		<tr>
			<td></td>
			<td>
            <input type="hidden" name="lang" value="{LANG}">
            <input type="hidden" name="session_id" value="{session_id()}">
             <div class="wl_btn_submit"> <input name="submit" type="submit" value="提  交" class="btn btn-success">
             </div>
             </td>
		</tr>
	</table>
	</form>
<!--elseif $action=='add' || $action=='edit' -->
	<form method="post" class="system" style="margin-top:30px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
			<tr>
				<td width="100px">字段中文标题</td>
				<td><input name="title" type="text" value="{$r.title}" class="form-control" style=" width:400px;"></td>
			</tr>
            <tr>
				<td width="100px">分组</td>
				<td>
				<select name="issystem" class="form-control"  style=" width:400px;">
                {foreach $set_ars $k $v}
					<option {if $action=='edit' && $r.issystem == $k}selected{/if} {if $action=='add' && $se==$k} selected{/if} value="{$k}">{$v}</option>
			    {/foreach}
				</select>
				</td>
			</tr>
			<tr>
				<td width="100px">字段英文名称</td>
				<td><input name="name" type="text" value="{$r.name}" class="form-control"  style=" width:400px;"> <span style="color:#999">不能有重复</span></td>
			</tr>
			<tr>
				<td width="100px">字段类型</td>
				<td>
				<select name="type" class="form-control"  style=" width:400px;">
					<option {if $r.type=='input'}selected{/if}>input</option>
					<option {if $r.type=='textarea'}selected{/if}>textarea</option>
					<option {if $r.type=='file'}selected{/if}>file</option>
					<option {if $r.type=='select'}selected{/if}>select</option>
					<option {if $r.type=='radio'}selected{/if}>radio</option>
					<option {if $r.type=='checkbox'}selected{/if}>checkbox</option>
					<option {if $r.type=='minieditor'}selected{/if}>minieditor</option>
					<option {if $r.type=='editor'}selected{/if}>editor</option>
				</select>
				</td>
			</tr>
			<tr>
				<td width="100px">默认选项列表</td>
				<td><input name="default" type="text" class="form-control" style="width:450px;" value="{$r.default}"> <span style="color:#999">用“,”分隔，适用于select radio checkbox的字段类型</span></td>
			</tr>
			<tr>
				<td width="100px">字段描述</td>
				<td><textarea name="desc" class="form-control" style="width:450px; height:50px;">{$r.desc}</textarea></td>
			</tr>
            <tr>
				<td width="100px">排序</td>
				<td><input type="text" name="weight" class="form-control" style="width:400px;" value="{$r.weight}"></td>
			</tr>
			<tr>
				<td></td>
				<td>
                <input type="hidden" name="lang" value="{LANG}">
        <input type="hidden" name="session_id" value="{session_id()}">
                <input name="submit" type="submit" value="提交保存" class="btn btn-default"></td>
			</tr>
		</table>
	</form>
<!--/if -->
</body>
</html>
