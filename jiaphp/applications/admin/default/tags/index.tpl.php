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
<script type="text/javascript" src="{$skins_admin}/editor.js" id="cms_editor" use="{S('cms_editor')}" pb="{$skins_index}"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>

<style>
input.timeinput{ border:0; background:none; height:28px; line-height:28px; padding:4px}
input.timeinput:hover,.form input.iptext:hover{ border:1px solid #46B8DA}
.form input.iptext, .form textarea.iptext{ border:0}

</style>
<script>
function set_times(obj,id){
	var old = $(obj).data('old');
	var v = $(obj).val();
	if(old!=v){
		$.post("{U($m.'/tags/edit')}",{action:'edit',target:'order',id:id,neworder:v},function(data){
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
	
	<div class="searchbar" style="margin-left:20px;">
	<form method="post" class="form-wrap" style="padding-top:0; margin-bottom:10px">
       
			排序：<select name="order" class="form-control w400" style="float:none;width:200px;">
                    <option value="" {if $order==''}selected{/if}>默认</option>
            	 
					<option value="1" {if $order=='1'}selected{/if}>排序高到低</option>
                    <option value="2" {if $order=='2'}selected{/if}>排序低到高</option>
					<option value="3" {if $order=='3'}selected{/if}>按数量高低</option>
					 <option value="4" {if $order=='4'}selected{/if}>按数量低高</option>
				</select> 
			 <button name="sub" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
            
		</form>
       
	</div>
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	<tr>
    <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
	<th width="5%">id</th>
    <th width="10%" align="left">名称</th>
	<th width="10%" align="left">排序</th>
    <th width="10%" align="left"><a href="?order={if $order==0||$order==4}3{else}4{/if}&page={gpc('page')}">出现次数</a></th>
	<th width="12%" align="left">操作</th>
	</tr>
	<!--foreach $list $r -->
		<tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="{$r.id}"></td>
			<td width="5%" align="center">{$r.id}</td>
            <td width="5%" align="center">{eval echo str_replace($keyword,"<span style='color:#f30'>".$keyword."</span>",$r.title)}</td>
           <td width="5%" align="center"><input type="text" data-old="{$r.listorder}" value="{$r.listorder}" class="timeinput" onBlur="set_times(this,'{$r.id}')"></td>
           <td width="5%" align="center">{$r.nums}</td>
           <td width="12%" align="center">
              <div class="btn-group" role="group">
                <a href="{U('content/tag/index/tagid/'.$r.id)}" class="btn btn-info" target="_blank">
                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>查看</a>
		        <a href="?action=del&id={$r.id}"  class="btn btn-danger hp-del">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>删除</a>
            </div>
		   </td>
			
		</tr>
	<!--/foreach -->
          
	</table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>

{/if}
</body>
</html>