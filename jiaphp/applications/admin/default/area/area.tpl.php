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

</head>

<body>
<!--if empty($action) -->
	<div class="subnav"><a href="?action=add" class="btn btn-danger" style="color:#fff">+添加区域</a></div>
	<div class="searchbar">
		<form method="post" class="form-wrap">
			地区名称：<input name="keyword" type="text" style="width:180px;" class="form-control" value="{$keyword}"> &nbsp;&nbsp;
			<input name="submit" type="submit" value="提交" class="btn btn-info">
		</form>
	</div>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	<tr>
    <th width="5%">选</th>
	<th width="5%">ID</th>
    <th width="10%" align="left">地区名称</th>
	<th width="10%" align="left">别名</th>
	<th width="12%">操作</th>
	</tr>
	<!--foreach $list $r -->
		<tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="{$r.id}"></td>
			<td width="5%" align="center">{$r.id}</td>
            <td width="5%" align="center">{eval echo str_replace($keyword,"<span style='color:#f30'>".$keyword."</span>",$r.name)}</td>
           <td width="5%" align="center">{$r.ename}</td>
          
			<td width="12%" align="center">
			
				<a href="?action=del&id={$r.id}" class="hp-del">删除</a>|<a href="?action=edit&id={$r.id}">修改</a>|
               
			</td>
		</tr>
	<!--/foreach -->
            <tr>
         
      <td colspan="8">
  <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-info fanxuan" id="fanxuan">反选</button>
 
</div>
      </td>   
          </tr>
	</table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>
{elseif $action=='add' || $action=='edit'}

	<form method="post" class="system">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		
		<tr>
			<td width="90">地区名称：</td>
			<td><input name="data[name]" type="text" class="form-control" style="width:400px;" value="{if $action=='edit'}{$r.name}{/if}"></td>
	
		</tr>
        <tr>
			<td width="90">上级地区：</td>
			<td>
            <select name="data[parentid]" class="iptext form-control">
            <option value="0">根地区</option>
            {eval echo fetcharea(0,0,$action=='edit'?$r['parentid']:0);}
            </select>
            </td>
	
		</tr>
        <tr>
			<td width="90">别名：</td>
			<td><input name="data[ename]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.ename}{/if}"></td>
	
		</tr>
        <tr>
			<td></td>
			<td><input type="hidden" name="id" value="{if $action=='edit'}{$r.id}{/if}"><input name="submit" type="submit" value="提交" class="btn btn-success"><a href="javascript:history.go(-1);" class="btn btn-default" style="margin-left:20px">返回</a></td>
	
		</tr>
        
        </table>
     </form>
{/if}
</body>
</html>