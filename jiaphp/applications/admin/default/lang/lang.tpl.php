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
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>

</head>

<body>
<!--if empty($action) -->
	
	<div class="searchbar" style="margin-left:20px;">
		<form method="post" class="form-wrap" style="padding-top:0; margin-bottom:10px">
        
			语言名称：<input name="keyword" type="text" class="form-control" style="width:180px; display:inline" value="{$keyword}"> &nbsp;&nbsp;
			 <button name="sub" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
             <a href="?action=add" class="btn btn-success" style="color:#FFF"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加语言</a>
		</form>
       
	</div>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	<tr>
    <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
	<th width="5%">语言标识</th>
    <th width="10%" align="left">语言名称</th>
	<th width="10%" align="left">语言包</th>
    <th width="10%" align="left">是否默认显示</th>
	<th width="12%">操作</th>
	</tr>
	<!--foreach $list $r -->
		<tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="{$r.langid}"></td>
			<td width="5%" align="center">{$r.langid}</td>
            <td width="5%" align="center">{eval echo str_replace($keyword,"<span style='color:#f30'>".$keyword."</span>",$r.langname)}</td>
           <td width="5%" align="center">{$r.langphp}</td>
           <td width="5%" align="center">{if $r.isdefault}是{else}否{/if}</td>
          
			<td width="12%" align="center">
			 <div class="btn-group" role="group">
				
                
                <a href="?action=edit&id={$r.langid}"  class="btn btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a>
                <a href="?action=del&id={$r.langid}"   class="btn btn-danger hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
                </div> 
			</td>
		</tr>
	<!--/foreach -->
            <tr>
         
      <td colspan="8">
  <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-info fanxuan" id="fanxuan">反选</button>
  <button type="button" class="btn btn-danger" onClick="delall('{U($m.'/lang')}?action=del','删除')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</button>
</div>
      </td>   
          </tr>
	</table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>
{elseif $action=='add' || $action=='edit'}

	<form method="post" class="system">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		
		<tr>
			<td width="90">语言标识：</td>
			<td><input name="data[langid]" type="text" class="form-control" style="width:400px;" value="{if $action=='edit'}{$r.langid}{/if}"></td>
	
		</tr>
      
        <tr>
			<td width="90">语言名称：</td>
			<td><input name="data[langname]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.langname}{/if}"></td>
	
		</tr>
        
        <tr>
			<td width="90">语言包名称：</td>
			<td><input name="data[langphp]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.langphp}{/if}"></td>
	
		</tr>
         <tr>
			<td width="90">默认？：</td>
			<td><input name="data[isdefault]" type="radio" value="1" {if $action=='edit' && $r.isdefault==1} checked{/if}>是
            <input name="data[isdefault]" type="radio" value="0"  {if $action=='add'|| ($action=='edit' && $r.isdefault==0)} checked{/if}>否 
	
		</tr>
        
        <tr>
			<td></td>
			<td><input type="hidden" name="langid" value="{if $action=='edit'}{$r.langid}{/if}"><input name="submit" type="submit" value="提交" class="btn btn-success">
             <input name="returnback" type="button" onClick="history.go(-1);" value="返回上一页" class="btn btn-default">
            </td>
	
		</tr>
        
        </table>
     </form>
{/if}
</body>
</html>