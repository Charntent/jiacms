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
<table cellspacing="0" width="100%" class="form tr_line">
	<thead>
	<tr>
	
	<th align="left">方法</th>
	<th align="left"><input type="checkbox" onclick="checkAllMethod(this)">全选</th>
    
	</tr>
	</thead>
	<tbody>
    {foreach $list}
    <tr>
   
    <td width="150" style="text-align:left"> {if isset($lang["func_".$class])}{eval echo $lang["func_".$class]}{/if}</td>
    <td width="400"></td>
  
    </tr>
      {sql select * from purview where parent=".$id." order by listorder asc}
    <tr class="funck">
    
    <td width="150">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;┠{if isset($lang["func_".$class])}{eval echo $lang["func_".$class]}{/if}</td>
    
    <td>
    <?php
	
    if(!empty($method)) {
		?>
    <input type="checkbox" name="{$class}_methods[]" onclick="checkAll(this,'{$class}')">全选
     <?php 
	    $method =explode(",",$method);
		
		foreach($method as $met){
	?>
    <input type="checkbox"  name="{$class}_method[]" value="{$met}" <?php if(isset($purview[$class][$met])){?> checked="checked"<?php }?> class="grantall grantall_{$class}" data-name="{$class}">{if isset($lang["btn_".$met])}{eval echo $lang["btn_".$met]}{/if}
    <?php }
	}
	?>
    </td>
   
    </tr>
    {/sql}
    
    {/foreach}
    <tr>
     <td colspan="2">
       <a href="javascript:;" onClick="GrantAll('?action=grant','{$user_id}');" class="btn btn-success">授权</a>
     </td>
    </tr>
  </tbody>
	</table>
</body>
</html>

<script>
 function checkAllMethod(){

	 var grantall = $(".grantall"); 
	 $.each(grantall,function(index,value){
		   $(this).prop("checked",!$(this).prop("checked"));
	 });
}
function checkAll(obj,target){
	var cv = $(obj).prop("checked");
	if(cv){
	    $(".grantall_"+target).prop("checked",true);	
	}else{
		$(".grantall_"+target).prop("checked",false);
	}
}

function GrantAll(url,id){
	
	//选取权限
	
	var allquan = $(".grantall");
	var name='';
	var chek='';
	var str ='';
	allquan.each(function(index, element) {
       name =  $(this).data("name");
	  // name = name.split('_');
	   chek = $(this).prop("checked");
	   if(chek == true){
		   if(str==''){
			   str = name+";"+ $(this).val()+";1"  ;
		   }else{
			   str = str+"|"+name+";"+ $(this).val()+";1"  ;
		   }
	   }
    });
	if(str==''){
	    layer.msg('没有选择！起码选一项吧？');
		return false;	
	}
    var data = {grantall:str,user_id:id,'do':1};
	$.ajax({
		type: "POST",
		url:url,
		data:data,
		dataType:'json',
	    success: function (data) {
            if(data["error"]==0){
				 layer.msg(data.msg);
			}else{
				 layer.alert(data["msg"]);
				 return false;
			}
	    },
	    error:function(XMLHttpRequest, textStatus, errorThrown){
	    	
		}
	});
}
</script>