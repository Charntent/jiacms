<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{title}</title>
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>

<script type="text/javascript" src="{$skins_admin}/editor.js" id="cms_editor" use="{S('cms_editor')}" pb="{$skins_index}"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css" />

<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>
<script type="text/javascript">
var aid = '{eval echo @$id}';
</script>
</head>

<body>
<!--if empty($action) -->
	
	<!--if !empty($cfg.searchfield) -->
	<div class="searchbar clearfix">
        <ul class="nav nav-tabs" role="tablist">
       <li>
       <div class="btn-group"  role="group" aria-label="...">
        <!--if $debug -->
        <a href="?table={$table}&action=setting" class="btn btn-default"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>调试模式:【设置】</a>
        <!--/if-->
        <!--if in_array('allowadd',(array)@$cfg.func) -->
	   <a href="?action=add&table={$table}" class="btn btn-danger"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加数据</a>
	<!--/if -->
    </li>
		<li role="presentation">
           <form method="post" class="navbar-form navbar-left">
           <div class="form-group fl">
			<!--foreach $cfg.searchfield $field-->
				<span class="fl">{@$cfg.field.$field.name}：</span>
                   <input type="text" class="form-control" name="where_{$field}" style="width:150px; float:left"  placeholder="{@$cfg.field.$field.name}"  value="<?php echo stripslashes( ${'where_'.$field} ); ?>">
			<!--/foreach --> 
             </div>
			<button type="submit" class="btn btn-default" value="搜索" style=" float:left; margin-left:10px; margin-top:0" /><span class="glyphicon glyphicon-search">搜索</span></button>
		</form>
        </li>
        </ul>
	</div>
	<!--/if -->
		<table width="100%" border="1" align="center" cellspacing="0" cellpadding="0" class="tr_line">
			<tr class="tr_line">
				<!--foreach $fields $field-->
					<th>{@$cfg.field.$field.name}</th>
				<!--/foreach -->
				<th>操作</th>
			</tr>
			<!--foreach $list -->
			<tr>
				<!--foreach $fields $field-->
					<td><?php echo $$field; ?></td>
				<!--/foreach -->
				<td align="center">
                  <div class="btn-group"  role="group" aria-label="...">
					<!--if in_array('allowview',(array)@$cfg.func) -->
					<a href="?action=view&table={$table}&id={$id}" class="btn btn-info"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>查看</a>
					<!--/if -->
					<!--if in_array('allowedit',(array)@$cfg.func) -->
					<a href="?action=edit&table={$table}&id={$id}" class="btn btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>编辑</a>
					<!--/if -->
					<!--if in_array('allowdel',(array)@$cfg.func) -->
					<a href="?action=del&table={$table}&id={$id}" class="btn btn-danger hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
					<!--/if -->
                    
                    {if $table=='guestbook'}<a href="?action=huifu&table={$table}&id={$id}" class="btn btn-success"><span class="glyphicon glyphicon-upload" aria-hidden="true"></span>回复</a>{/if}
                    {if $table=='guestbook'}<a href="?action=tg&table={$table}&id={$id}" class="btn btn-info"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>通过/不通过</a>{/if}
                    </div>
				</td>
			</tr>
			<!--/foreach -->
		</table>
	<div class="page_box"><ul class="pagination">{$page}</ul></div>

<!--elseif $action=='setting' -->
<form method="post" class="form-wrap" class="system">
	<div style="margin-bottom:10px; border:solid 1px #CCC; padding:10px;">
		<h3>列表显示字段</h3>
		<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr>
		<!--foreach $fields $field-->
			<td><label><input name="listfield[]" type="checkbox" value="{$field}" {if in_array($field,(array)@$cfg.listfield)}checked{/if}/> {$field} &nbsp;&nbsp;</label></td>
		<!--/foreach -->
		</tr>
		</table>
	</div>
	<div style="margin-bottom:10px; border:solid 1px #CCC; padding:10px;">
		<h3>功能选项</h3>
		<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr>
			<td>
				<label><input name="func[]" type="checkbox" value="allowadd" {if in_array('allowadd',(array)@$cfg.func)}checked{/if}/> 允许添加 &nbsp;&nbsp;</label>
				<label><input name="func[]" type="checkbox" value="allowview" {if in_array('allowview',(array)@$cfg.func)}checked{/if}/> 允许查看 &nbsp;&nbsp;</label>
				<label><input name="func[]" type="checkbox" value="allowedit" {if in_array('allowedit',(array)@$cfg.func)}checked{/if}/> 允许编辑 &nbsp;&nbsp;</label>
				<label><input name="func[]" type="checkbox" value="allowdel" {if in_array('allowdel',(array)@$cfg.func)}checked{/if}/> 允许删除 &nbsp;&nbsp;</label>
			</td>
		</tr>
		</table>
	</div>
	<div style="margin-bottom:10px; border:solid 1px #CCC; padding:10px;">
		<h3>可见添加/编辑字段</h3>
		<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr>
		<!--foreach $fields $field-->
			<td><label><input name="editfield[]" type="checkbox" value="{$field}" {if in_array($field,(array)@$cfg.editfield)}checked{/if}/> {$field} &nbsp;&nbsp;</label></td>
		<!--/foreach -->
		</tr>
		</table>
	</div>
	<div style="margin-bottom:10px; border:solid 1px #CCC; padding:10px;">
		<h3>允许搜索</h3>
		<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr>
		<!--foreach $fields $field-->
			<td><label><input name="searchfield[]" type="checkbox" value="{$field}" {if in_array($field,(array)@$cfg.searchfield)}checked{/if}/> {$field} &nbsp;&nbsp;</label></td>
		<!--/foreach -->
		</tr>
		</table>
	</div>
	<div style="margin-bottom:10px; border:solid 1px #CCC; padding:10px; overflow-x:scroll;">
		<h3>字段设置</h3>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<tr>
			<th>字段</th>
			<th>名字</th>
			<th>输入类型</th>
			<th>说明</th>
			<th>默认值</th>
			<th>多选选项</th>
			<th>输入处理</th>
			<th>输出处理</th>
		</tr>
		<!--foreach $fields $field-->
		<?php
			if(!in_array($field,(array)@$cfg['listfield']) && !in_array($field,(array)@$cfg['editfield']) && !in_array($field,(array)@$cfg['searchfield']) ) continue;
		?>
			<tr>
				<td>{$field}</td>
				<td><input name="field[{$field}][name]" type="text" value="<?php echo @$cfg['field'][$field]['name']; ?>" style="width:100px;" class="form-control" /></td>
				<td>
					<select name="field[{$field}][type]" class="form-control" style="width:90px">
						<option value="input" <?php if(@$cfg['field'][$field]['type']=='input') echo "selected";?>>input</option>
						<option value="textarea" <?php if(@$cfg['field'][$field]['type']=='textarea') echo "selected";?>>textarea</option>
						<option value="select" <?php if(@$cfg['field'][$field]['type']=='select') echo "selected";?>>select</option>
						<option value="radio" <?php if(@$cfg['field'][$field]['type']=='radio') echo "selected";?>>radio</option>
						<option value="checkbox" <?php if(@$cfg['field'][$field]['type']=='checkbox') echo "selected";?>>checkbox</option>
						<option value="file" <?php if(@$cfg['field'][$field]['type']=='file') echo "selected";?>>file</option>
						<option value="minieditor" <?php if(@$cfg['field'][$field]['type']=='minieditor') echo "selected";?>>minieditor</option>
						<option value="editor" <?php if(@$cfg['field'][$field]['type']=='editor') echo "selected";?>>editor</option>
					</select>
				</td>
				<td><textarea name="field[{$field}][desc]" style="width:150px; height:45px;" class="form-control"><?php echo htmlspecialchars (@$cfg['field'][$field]['desc']); ?></textarea></td>
				<td><input name="field[{$field}][default]" type="text" class="form-control" value="<?php echo htmlspecialchars (@$cfg['field'][$field]['default']); ?>"  style="width:100px;" /></td>
				<td><input name="field[{$field}][option]" type="text" class="form-control" value="<?php echo htmlspecialchars (@$cfg['field'][$field]['option']); ?>" /></td>
				<td><textarea name="field[{$field}][inputexec]" style="width:150px; height:45px;" class="form-control"><?php echo htmlspecialchars (@$cfg['field'][$field]['inputexec']); ?></textarea></td>
				<td><textarea name="field[{$field}][outputexec]" style="width:150px; height:45px;" class="form-control"><?php echo htmlspecialchars (@$cfg['field'][$field]['outputexec']); ?></textarea></td>
			</tr>
		<!--/foreach -->
		</table>
	</div>
	<div style="margin-bottom:10px; padding:10px;">
		<table border="0" cellspacing="0" cellpadding="0" class="form">
			<tr>
				<td>
                 <input type="hidden" name="lang" value="{LANG}">
                 <input type="hidden" name="session_id" value="{session_id()}">
                 <input name="submit" type="submit" value="提交" class="btn btn-default">
                 <input name="history" onclick="window.history.back()" type="button" value="返回上一页" class="btn btn-default">
                </td>
			</tr>
		</table>
	</div>
</form>
<!--elseif $action=='add' -->
	<form method="post" class="form-wrap system">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<!--foreach $cfg.editfield $field-->
			<tr>
				<td width="120">{@$cfg.field.$field.name}</td>
				<td>
				<!--if $cfg['field'][$field]['type']=='input' -->
					<input name="{$field}" type="text" style="width:400px;" value="{eval echo $cfg['field'][$field]['default']}" class="form-control" placeholder="{@$cfg.field.$field.name}" />
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='textarea' -->
					<textarea name="{$field}" style="width:680px; height:80px;" class="form-control"  placeholder="{@$cfg.field.$field.name}">{eval echo $cfg['field'][$field]['default']}</textarea>
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='select' -->
					<!--eval $option=explode(',',$cfg['field'][$field]['option']); -->
					<select name="{$field}" class="form-control">
					<!--foreach $option $r -->
						<option value="{$r}" {if $cfg['field'][$field]['default']==$r}selected{/if}>{$r}</option>
					<!--/foreach -->
					</select>
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='radio' -->
					<!--eval $radio=explode(',',$cfg['field'][$field]['option']); -->
					<!--foreach $radio $r -->
						<label><input name="{$field}" type="radio" value="{$r}" {if $cfg['field'][$field]['default']==$r}checked{/if}> {$r}&nbsp;&nbsp;</label>
					<!--/foreach -->
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='checkbox' -->
					<!--eval $checkbox=explode(',',$cfg['field'][$field]['option']); -->
					<!--foreach $checkbox $r -->
						<label><input name="{$field}[]" type="checkbox" value="{$r}" {if in_array( $r, explode(',', $cfg['field'][$field]['default'] ))}checked{/if}> {$r} &nbsp;&nbsp;</label>
					<!--/foreach -->
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='file' -->
					<input name="{$field}" id="{$field}" _type="file" type="text" style="width:400px; display:inline" value="{eval echo $cfg['field'][$field]['default']}"   placeholder="{@$cfg.field.$field.name}"  class="form-control"/>
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='minieditor' -->
					<textarea name="{$field}" style="width:650px; height:300px;" id="{$field}" _type="minieditor" >{eval echo $cfg['field'][$field]['default']}</textarea>
				<!--/if -->	
				<!--if $cfg['field'][$field]['type']=='editor' -->
					<textarea name="{$field}" style="width:780px; height:300px;" id="{$field}" _type="editor">{eval echo $cfg['field'][$field]['default']}</textarea>
				<!--/if -->	
				<font color="#999999">{eval echo $cfg['field'][$field]['desc']}</font>
				</td>
			</tr>
		<!--/foreach -->
        
	</table>
	<table border="0" cellspacing="0" cellpadding="0" class="form" style="clear:both">
		<tr><td width="120"></td><td>
         <input type="hidden" name="lang" value="{LANG}">
                 <input type="hidden" name="session_id" value="{session_id()}">
        <input name="submit" type="submit" value="提交" class="btn btn-success">
           <input name="history" onclick="window.history.back()" type="button" value="返回上一页" class="btn btn-default">
        </td></tr>
	</table>
	</form>
<!--elseif $action=='view' -->
<table width="98%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
		<!--foreach $cfg.editfield $field-->
			<tr>
				<td width="120">{@$cfg.field.$field.name}</td>
				<td>
					{$r.$field}
				</td>
			</tr>
		<!--/foreach -->	
	</table>
	<div style="padding:10px;" class="form"><input name="goback" type="button" class="btn" value="返回列表" onclick="history.back()" /></div>
<!--elseif $action=='edit' -->
	<form method="post" class="form-wrap system">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<!--foreach $cfg.editfield $field-->
			<tr>
				<td width="120">{@$cfg.field.$field.name}</td>
				<td>
				<!--if $cfg['field'][$field]['type']=='input' -->
					<input name="{$field}" type="text" style="width:400px;"  class="form-control" value="{$r.$field}"   placeholder="{@$cfg.field.$field.name}" />
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='textarea' -->
					<textarea name="{$field}" style="width:680px; height:80px;"  class="form-control"   placeholder="{@$cfg.field.$field.name}">{$r.$field}</textarea>
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='select' -->
					<!--eval $option=explode(',',$cfg['field'][$field]['option']); -->
					<select name="{$field}"  class="form-control">
					<!--foreach $option $v -->
						<option value="{$v}" {if $r[$field]==$v}selected{/if}>{$v}</option>
					<!--/foreach -->
					</select>
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='radio' -->
					<!--eval $radio=explode(',',$cfg['field'][$field]['option']); -->
					<!--foreach $radio $v -->
						<label><input name="{$field}" type="radio" value="{$v}"   {if $r[$field]==$v}checked{/if}> {$v}&nbsp;&nbsp;</label>
					<!--/foreach -->
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='checkbox' -->
					<!--eval $checkbox=explode(',',$cfg['field'][$field]['option']); -->
					<!--foreach $checkbox $v -->
						<label><input name="{$field}[]" type="checkbox" value="{$v}" {if in_array( $v, explode(',', $r[$field] ))}checked{/if}> {$v} &nbsp;&nbsp;</label>
					<!--/foreach -->
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='file' -->
					<input name="{$field}" id="{$field}" _type="file"  class="form-control" type="text" style="width:400px; display:inline" value="{$r.$field}"   placeholder="{@$cfg.field.$field.name}" />
				<!--/if -->
				<!--if $cfg['field'][$field]['type']=='minieditor' -->
					<textarea name="{$field}" style="width:650px;" id="{$field}" _type="minieditor">{$r.$field}</textarea>
				<!--/if -->	
				<!--if $cfg['field'][$field]['type']=='editor' -->
					<textarea name="{$field}" style="width:780px;" id="{$field}" _type="editor">{$r.$field}</textarea>
				<!--/if -->	
				<font color="#999999">{@$cfg.field.$field.desc}</font>
				</td>
			</tr>
		<!--/foreach -->
	</table>
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr><td width="120"></td><td>
         <input type="hidden" name="lang" value="{LANG}">
         <input type="hidden" name="session_id" value="{session_id()}">
        
        <input name="submit" type="submit" value="提交" class="btn btn-default">
           <input name="history" onclick="window.history.back()" type="button" value="返回上一页" class="btn btn-default">
        </td></tr>
	</table>
	</form>
<!--/if -->
</body>
</html>
