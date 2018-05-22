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
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>
<style>
.edit{ margin-left:10px}
</style>
</head>

<body>

<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
<tr>
<th width="25%">PC网站</th>
<th width="25%">手机网站</th>
<th width="25%" align="left">微信网站</th>
<th width="25%" align="left">APP</th>
</tr>
<tr>
<td><a href="{U_aid($info_pc['id'],$info_pc['catid'])}" target="_blank">{$info_pc.title}</a>
  </td>

<td>{if $info_mobile}<a href="{U_aid($info_mobile['id'],$info_mobile['catid'])}" target="_blank">{$info_mobile.title}</a>
  {if $catid != 139}<a href="javascript:;"  class="bing" data-pcid="{$aid}"   data-catid="139">绑定</a>{/if}
{else}<a href="javascript:;"   class="bing" data-pcid="{$aid}"   data-catid="139">绑定</a>{/if}</td>

<td>{if $info_weixin}<a href="{U_aid($info_weixin['id'],$info_weixin['catid'])}" target="_blank">{$info_weixin.title}</a>
  {if $catid != 522}<a href="javascript:;"  class="bing" data-pcid="{$aid}"   data-catid="522">修改</a>{/if}
{else}<a href="javascript:;"  class="bing" data-pcid="{$aid}"   data-catid="522">绑定</a>{/if}</td>


<td>{if $info_app}<a href="{U_aid($info_app['id'],$info_app['catid'])}" target="_blank">{$info_app.title}</a>  {if $catid!=138}<a href="javascript:;"  class="bing" data-pcid="{$aid}"   data-catid="138">绑定</a>{/if}{else}<a href="javascript:;" class="bing" data-pcid="{$aid}"   data-catid="138">添加</a>{/if}</td>
</tr>
</table>
<div style="padding:20px; clear:both; display:none" id="datasourse">
<select name="source_select1" id="source_select1" class="form-control-good" size="20" style="width:100%" ondblclick="addItem(this.value)" multiple="true">
</select>
<p style="line-height:30px; font-size:16px; color:#f30">温馨提示:请双击框里选项绑定数据！</p>
</div>
<script>
 var pcatid = '';
  var pcaid = '{$aid}';
	var catid = '{$catid}';  
$(function(){
   
	$('.bing').on('click',function(){
	
		var pcid =$(this).data('pcid');
		pcatid =$(this).data('catid');
		
		var loading = layer.msg('正在加载，请等候...', {icon: 16,time: 999999, shade: [0.3, '#000']});
		$.post("{U($m.'/bind/getArc')}",{pcatid:pcatid,pcid:pcid,catid:catid,pcaid:pcaid},function(data){
		    layer.close(loading);	
			if(data.tourl!=''){
				$('#source_select1').empty().append(data.tourl);
				$('#datasourse').fadeIn();
			}else{
				$('#source_select1').empty();
				layer.msg('没有找到可以绑定的数据！', {icon: 1});	
			}
		},'json');
	});
		
})

function addItem(value){
	var maid = value;
	layer.confirm('真的要绑定吗？', {
	  btn: ['确定','取消'] //按钮
	  ,title:'温馨提示'
	}, function(){
		var loading = layer.msg('正在提交，请等候...', {icon: 16,time: 999999, shade: [0.3, '#000']});
		$.post("{U($m.'/bind/bindok')}",{pcatid:pcatid,pcaid:pcaid,catid:catid,maid:maid},function(data){
		    layer.close(loading);
			layer.msg('绑定成功', {icon: 1});	
			setTimeout(function(){
			     location.href = location.href;	
			},1000);
		},'json');
	    
	});
}
</script>
</body>
</html>