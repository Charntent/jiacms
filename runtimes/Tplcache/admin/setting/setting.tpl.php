<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>慧名科技内容管理系统</title>
<link href="<?php echo $skins_admin ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $skins_admin ?>/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $skins_admin ?>/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/base.js"></script>

<script type="text/javascript" charset="utf-8" src="<?php echo $skins_index ?>/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="<?php echo $skins_index ?>/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_index ?>/ueditor/lang/zh-cn/zh-cn.js"></script>

<link href="<?php echo $skins_index ?>/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $skins_index ?>/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo $skins_index ?>/swfupload/handlers.js"></script>
<style>
.form tr{ border-bottom:1px dashed #ddd; padding:10px; height:70px;}
</style>
</head>
<body>
<ul class="nav nav-tabs" style="margin-left:10px; margin-top:10px;">
 <?php Tag::var_protect("IN"); if(is_array($set_ars)) foreach($set_ars as $k=> $v) { ?>
  <li role="presentation" <?php if($se==$k) { ?>class="active"<?php } ?>><a href="<?php echo U($m.'/setting') ?>?se=<?php echo $k ?>"><?php echo $v ?></a></li>
 <?php };  Tag::var_protect("OUT"); ?>
</ul>

<?php if(empty($action)) { ?>

	<form action="setting?action=save" method="post" class="system">
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
   
	<?php Tag::var_protect("IN"); $index=0; if(is_array($r)) foreach($r as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>
	
		<?php if($type=='input') { ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td><input name="data[<?php echo $name ?>]" type="text" style="width:400px;" value="<?php echo $value ?>" class="form-control"> <font color="#999999"><?php echo $desc ?></font></td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
                <a href="?action=del&name=<?php echo $name ?>">删除</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='textarea') { ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td><textarea name="data[<?php echo $name ?>]" style="width:450px; height:80px;" class="form-control"><?php echo $value ?></textarea> <font color="#999999"><?php echo $desc ?></font></td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='select') { ?>
		<?php $select=explode(',',$default); ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td>
				<select name="data[<?php echo $name ?>]" class="form-control">
				<?php Tag::var_protect("IN"); if(is_array($select)) foreach($select as $r) { ?>
					<option value="<?php echo $r ?>" <?php if($value==$r) { ?>selected<?php } ?>><?php echo $r ?></option>
				<?php };  Tag::var_protect("OUT"); ?>
				</select>
				<font color="#999999"><?php echo $desc ?></font>
			</td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='radio') { ?>
		<?php $radio=explode(',',$default); ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td>
				<?php Tag::var_protect("IN"); if(is_array($radio)) foreach($radio as $r) { ?>
                <div style="float:left; padding:0 10px; line-height:38px;">
					<input name="data[<?php echo $name ?>]" type="radio" value="<?php echo $r ?>" class="form-control" <?php if($value==$r) { ?>checked<?php } ?> style="width:20px; float:left; background:none; border:none; box-shadow:none;"> <?php echo $r ?>&nbsp;&nbsp;
                    </div>
				<?php };  Tag::var_protect("OUT"); ?>
				<font color="#999999"><?php echo $desc ?></font>
			</td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
            
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='checkbox') { ?>
		<?php $checkbox=explode(',',$default);$value=unserialize($value); ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td>
				<?php Tag::var_protect("IN"); if(is_array($checkbox)) foreach($checkbox as $r) { ?>
					<input name="data[<?php echo $name ?>][]" type="checkbox" class="form-control" value="<?php echo $r ?>" <?php if(in_array($r,(array)$value)) { ?>checked<?php } ?> style="width:50px; float:left"> <?php echo $r ?> &nbsp;&nbsp;
				<?php };  Tag::var_protect("OUT"); ?>
				<font color="#999999"><?php echo $desc ?></font>
			</td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='file') { ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td><input name="data[<?php echo $name ?>]" type="text" class="form-control" _type="file" style="width:400px; float:left" value="<?php echo $value ?>" id="<?php echo $name ?>" > <font color="#999999"><?php echo $desc ?></font></td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                    <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='minieditor') { ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td><textarea name="data[<?php echo $name ?>]" style="width:650px;" id="<?php echo $name ?>" _type="minieditor"><?php echo $value ?></textarea> <font color="#999999"><?php echo $desc ?></font></td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
		<?php if($type=='editor') { ?>
		<tr>
			<td width="160px"><?php echo $title ?></td>
			<td><textarea name="data[<?php echo $name ?>]" style="width:780px;" id="<?php echo $name ?>" _type="editor"><?php echo $value ?></textarea> <font color="#999999"><?php echo $desc ?></font></td>
			<td>
			<?php if($debug) { ?>
				<?php if($issystem) { ?>
                <a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<?php if($status) { ?>
						<a href="?action=setstatus&status=0&name=<?php echo $name ?>">停用</a>
					<?php } else { ?>
						已停用 <a href="?action=setstatus&status=1&name=<?php echo $name ?>"><font color="red">启用</font></a>
					<?php } ?>
                    <a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } else { ?>
					<a href="?action=edit&id=<?php echo $id ?>">修改</a>
					<a href="?action=del&name=<?php echo $name ?>">删除</a>
				<?php } ?>
			<?php } ?>
			</td>
		</tr>
		<?php } ?>
		
	<?php };  Tag::var_protect("OUT"); ?>
	<?php if($debug) { ?>
		<tr>
			<td><a href="?action=add&se=<?php echo $se ?>" class="btn btn-danger"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加项目</a></td>
			<td></td>
		</tr>
	<?php } ?>
		<tr>
			<td></td>
			<td>
            <input type="hidden" name="lang" value="<?php echo LANG; ?>">
            <input type="hidden" name="session_id" value="<?php echo session_id() ?>">
             <div class="wl_btn_submit"> <input name="submit" type="submit" value="提  交" class="btn btn-success">
             </div>
             </td>
		</tr>
	</table>
	</form>
<?php } elseif ($action=='add' || $action=='edit') { ?>
	<form method="post" class="system" style="margin-top:30px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="form">
			<tr>
				<td width="100px">字段中文标题</td>
				<td><input name="title" type="text" value="<?php echo $r['title'] ?>" class="form-control" style=" width:400px;"></td>
			</tr>
            <tr>
				<td width="100px">分组</td>
				<td>
				<select name="issystem" class="form-control"  style=" width:400px;">
                <?php Tag::var_protect("IN"); if(is_array($set_ars)) foreach($set_ars as $k=> $v) { ?>
					<option <?php if($action=='edit' && $r['issystem'] == $k) { ?>selected<?php } ?> <?php if($action=='add' && $se==$k) { ?> selected<?php } ?> value="<?php echo $k ?>"><?php echo $v ?></option>
			    <?php };  Tag::var_protect("OUT"); ?>
				</select>
				</td>
			</tr>
			<tr>
				<td width="100px">字段英文名称</td>
				<td><input name="name" type="text" value="<?php echo $r['name'] ?>" class="form-control"  style=" width:400px;"> <span style="color:#999">不能有重复</span></td>
			</tr>
			<tr>
				<td width="100px">字段类型</td>
				<td>
				<select name="type" class="form-control"  style=" width:400px;">
					<option <?php if($r['type']=='input') { ?>selected<?php } ?>>input</option>
					<option <?php if($r['type']=='textarea') { ?>selected<?php } ?>>textarea</option>
					<option <?php if($r['type']=='file') { ?>selected<?php } ?>>file</option>
					<option <?php if($r['type']=='select') { ?>selected<?php } ?>>select</option>
					<option <?php if($r['type']=='radio') { ?>selected<?php } ?>>radio</option>
					<option <?php if($r['type']=='checkbox') { ?>selected<?php } ?>>checkbox</option>
					<option <?php if($r['type']=='minieditor') { ?>selected<?php } ?>>minieditor</option>
					<option <?php if($r['type']=='editor') { ?>selected<?php } ?>>editor</option>
				</select>
				</td>
			</tr>
			<tr>
				<td width="100px">默认选项列表</td>
				<td><input name="default" type="text" class="form-control" style="width:450px;" value="<?php echo $r['default'] ?>"> <span style="color:#999">用“,”分隔，适用于select radio checkbox的字段类型</span></td>
			</tr>
			<tr>
				<td width="100px">字段描述</td>
				<td><textarea name="desc" class="form-control" style="width:450px; height:50px;"><?php echo $r['desc'] ?></textarea></td>
			</tr>
            <tr>
				<td width="100px">排序</td>
				<td><input type="text" name="weight" class="form-control" style="width:400px;" value="<?php echo $r['weight'] ?>"></td>
			</tr>
			<tr>
				<td></td>
				<td>
                <input type="hidden" name="lang" value="<?php echo LANG; ?>">
        <input type="hidden" name="session_id" value="<?php echo session_id() ?>">
                <input name="submit" type="submit" value="提交保存" class="btn btn-default"></td>
			</tr>
		</table>
	</form>
<?php } ?>
</body>
</html>
