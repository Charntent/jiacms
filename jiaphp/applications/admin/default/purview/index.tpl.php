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
    <a href="?action=add" class="btn btn-success fl" style="color:#FFF; margin-left:10px"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>权限菜单</a>
    
              <div class="btn-group fl" role="group" style="margin-left:50px">
                
                 <a  href="javascript:;" class="btn btn-info fanxuan" id="fanxuan"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>反选</a>
                <a href="javascript:;" onClick="order('purview?action=order')" class="btn btn-info" style="color:#FFF"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>排序</a>
                <a href="javascript:;" onClick="delall('purview?action=del','删除')" class="btn btn-danger" style="color:#FFF"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
           
            </div>
		<form method="post" class="form-wrap">
			权限菜单名称(顶级)：<input name="keyword" type="text" class="form-control" style="width:180px; display:inline" value="{$keyword}"> &nbsp;&nbsp;
			   <button name="sub" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
            
		</form>
	</div>
	 <div id="fileList" class="file-list-box clearfix file-tab" data-tab="file-tab" style="">
        <div id="listHolder" class="clearfix">
           <table width="100%" cellpadding="0" cellspacing="0" class="form tr_line">
            <thead>
             <tr  height="38" style="line-height:38px;">
                 <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
                 <th>ID</th>
                 <th>标题</th>
                 <th>控制器</th>
                 <th>权限</th>
                 <th>状态</th>
                 <th>操作</th>
             </tr>
     </thead>
     <tbody>
             {foreach $list}
             <tr height="25" style="line-height:33px;">
                 <td><input type="checkbox" name="id" class="idars form-control" value="{$id}"></td>
                 <td>{$id}</td>
                 <td ><input type="text" name="listorder[]" class="form-control" size="3" value="{$listorder}"  alt="{$id}" pattern="{$listorder}" style="display:inline-block; width:50px;margin-right:5px;">{eval echo lang("func_".$class);}</td>
                 <td>{$class}</td>
                 <td>{if $method}{$method}{else}---{/if}</td>
                
                 <td>{eval echo  status($status);}</td>
                 <td> <div class="btn-group" role="group"><?php if(in_array("edit",$viewar)){?><a href='?action=edit&id={$id}' title="编辑" class="btn btn-success" style="color:#FFF"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>编辑</a><?php }?><?php if(in_array("del",$viewar)){?><a href='javascript:submitTo("{U($m.'/purview')}","del","{$id}")' title="删除本项" class="btn btn-danger" style="color:#FFF"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a><?php }?></div> </td>
             </tr>
             {sql select * from purview where parent=".$id." order by listorder asc}
              <tr height="25" style="line-height:33px;">
                 <td><input type="checkbox" name="id" class="idars form-control" value="{$id}"></td>
                 <td>{$id}</td>
                 <td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="listorder[]" class="form-control" size="3" value="{$listorder}" alt="{$id}" pattern="{$listorder}" style="display:inline-block; width:50px; margin-right:5px;">{eval echo lang("func_".$class);}</td>
                 <td>{$class}</td>
                 <td>{if $method}{$method}{else}---{/if}</td>
               
                 <td>{eval echo status($status);}</td>
                
                 <td>
                 <div class="btn-group" role="group">
				 <?php if(in_array("edit",$viewar)){?><a href='?action=edit&id={$id}'  title="编辑" class="btn btn-success" style="color:#FFF"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a><?php }?><?php if(in_array("del",$viewar)){?><a href='javascript:submitTo("{U($m.'/purview')}","del","{$id}")' title="删除本项"  class="btn btn-danger" style="color:#FFF"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a><?php }?>
                 </div>
                 </td>
             </tr>
             {/sql}
             
             {/foreach}
    
        <tr>
        <td colspan="7">
        
        <div class="btn-group" role="group">
               
                 <a  href="javascript:;" class="btn btn-info fanxuan" id="fanxuan"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>反选</a>
                <a href="javascript:;"   onClick="order('{U($m.'/purview')}?action=order')" class="btn btn-info" style="color:#FFF"><span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>排序</a>
                <a href="javascript:;" onClick="delall('{U($m.'/purview')}?action=del','删除')" class="btn btn-danger" style="color:#FFF"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
            </div></td>
        </tr>
        </tbody>
        </table>
	<div class="page_box "><ul class="pagination">{$page}</ul></div>
{elseif $action=='add' || $action=='edit'}

	<form method="post" class="system">
	<table cellspacing="0" width="100%"  class="form">
	       <tbody><tr><td width="100">上级</td><td>
            <select name="data[parent]" class="form-control" style="width:200px">
                 <option value="0">顶级菜单</option>
                     {foreach $list_top}
                        <option value="{$id}" {if $action=='edit' && $r.parent==$id} selected{/if}>{eval echo lang("func_".$class);}</option>
                       {/if}
                   
                             </select>
         </td></tr>
	<tr><td>方法</td><td><input type="text" name="data[class]" id="class"  class="form-control" style="width:200px" validtip="required" value="{if $action=='edit'}{$r.class}{/if}"></td></tr>
	<tr><td>权限</td><td><textarea rows="3" cols="40" name="data[method]" id="method"  class="form-control" style="width:500px; height:95px">{if $action=='edit'}{$r.method}{/if}</textarea></td></tr>
	<tr><td>状态</td><td>启用<input type="radio" name="data[status]" value="1" {if $action=='add' || ($action=='edit' && $r.status==1)} checked{/if}><font color="red"><font color="red">禁用</font></font><input type="radio" name="data[status]" {if $action=='edit' && $r.status==0} checked{/if} value="0"></td></tr>
	<tr><td>排序</td><td><input type="text" name="listorder" id="listorder" value="1"  class="form-control" style="width:200px"></td></tr>
    
    
       <tr>
			<td></td>
			<td><input type="hidden" name="id" value="{if $action=='edit'}{$r.id}{/if}"><input name="submit" type="submit" value="提交" class="btn"></td>
	
		</tr>
    
	</tbody></table>
    
    
     
        
        </table>
     </form>
{/if}
</body>
</html>

<script>
function order(url){
	var data ='';
	$("input[name='listorder[]']").each(function(i, m) {
		if($(this).attr("pattern") !=$(this).val()){
			if(data==''){
				data = $(this).attr("alt")+"_"+$(this).val();	
			}else{
				data = data+"|"+$(this).attr("alt")+"_"+$(this).val();	
			}
		}
    });
	if(data==''){
		window.top.message('您并没有修改排序！');
		return false;
	}
	$.ajax({
		type: "POST",
		url:url,
		data:{para:data},
		dataType:'json',
	    success: function (data) {
			 window.top.message(data.msg);
            if(data["error"]==0){
				 setTimeout(function(){
					  location.href = location.href;
				 },1000);
			}
	    },
	    error:function(XMLHttpRequest, textStatus, errorThrown){
	    	
		}
	});
}
</script>