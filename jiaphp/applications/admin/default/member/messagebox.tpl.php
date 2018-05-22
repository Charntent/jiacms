<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>{title}</title>
<meta name="keywords" content="{keywords}" />
<meta name="description" content="{description}" />
<script type="text/javascript" src="../static/js/jquery.js"></script>
<script type="text/javascript" src="../static/js/wccms.js"></script>
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css" />
<style>
.left { text-align:left;}
td { text-align:center; margin:5px;}
tr:hover { background:#E6F0FF;}
#mask{width:100%; height:1024px; opacity:0.4; background-color:#000; z-index:1000; position:absolute; top:0; left:0; display:none;}
#msgbox{width:500px;border:5px solid #CCC; background-color:#FFF; z-index:2000; position:absolute; top:20%; left:50%; margin-left:-250px; display:none;}
#replycontent { width:270px;border:1px solid #039;}
</style>
<script type="text/javascript">
function closemsg()
{
	$("#msgbox").hide();
	//$("#mask").hide();
	$("#wnd_title").html("阅读消息");
	$("#replybox").hide();
	$("#msg_content").show();
	$("#sender").show();
	$("#sender_1").hide();
	$("#sender_1").val("");
}
function showmsg(id)
{
	//$("#mask").show();
	$("#wait").show();
	$("#rebtn").show();
	$.getJSON("{U($m.'/member')}?m=read&id="+id,function(result) {
		$("#title").html(result.title);
		$("#sender").html(result.sender);
		$("#msg_content").html(result.content);
		$("#sendtime").html(result.sendtime);
		$("#wait").hide();
		$("#msgbox").slideToggle("fast");
		});
}
function reply()
{
	$("#wnd_title").html("回复:"+$("#sender").html());
	$("#msg_content").hide();$("#rebtn").hide();
	$("#sender").hide();
	$("#sender_1").html($("#sender").html());
	$("#receiver").val($("#sender").html());
	$("#sender_1").show();
	$("#replybox").show();
}
function submitform()
{
	if($("#replycontent").val()=="")
	{
		$("#replycontent").val("内容不能为空");
	}
	else{
		$("#replycontent").val($("#replycontent").val()+"<br/>-------原信件内容-------<br/>"+$("#msg_content").html());
		$("#replyform").submit();
	}
}
</script>
</head>

<body>
	<div id="wait" style="width:150px; height:20px; z-index:3000; display:block; color:#fff; position:absolute;  top:20%; left:50%; margin:-10px 0 0 -75px; display:none;">数据加载中，请稍后</div>
	<div id="mask"></div>
	<div class="form">
		<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tr_line">
			<tr>
				<th width="50%" class="left">标题</th>
                <th width="15%">发件人</th>
				<th width="15%">时间</th>
				<th width="20%">操作</th>
			</tr>
			<!--foreach $list -->
			<tr>
				<td class="left"><a href="javascript:;" onclick="showmsg('{$mid}')"><font{if $isreaded == 0} style="font-weight:bold;"{/if}>{$title}</font></a></td>
                <td width="10%"><a href="{U($m.'/member')}?m=info&id={$sender}">{$sender}</a></td>
				<td>{date("Y-m-d H:i",$sendtime)}</td>
				<td><a href="javascript:;" onclick="showmsg('{$mid}')">查看</a> 
                <a href="{U($m.'/member')}?m=del&id={$mid}" class="hp-del">删除</a></td>
			</tr>
			<!--/foreach -->
		</table>
		<div class="page_box">{$page}</div>
	</div>
    <div id="msgbox">
    <form id="replyform" action="{U($m.'/member')}?m=reply" method="post">
    	<div style="height:30px; background:#155499; padding:0 10px;"><span style="float:left; line-height:30px; color:#fff;" id="wnd_title">阅读消息</span><span style="float:right; line-height:30px; text-align:center; vertical-align:middle"><a href="javascript:;" style="color:#FFF;" onclick="closemsg()">关闭</a></span></div>
        <div style=" padding:10px;">
        	<p style="border-bottom:1px dashed #C6C6C6;"><font style="color:#777">标题：</font><span id="title" style="margin:5px; line-height:20px"></span></p>
            <p style="border-bottom:1px dashed #C6C6C6;"><font style="color:#777">发件人：</font><span style="margin:5px; line-height:20px"><a href="javascript:;" id="sender" onclick="reply()" title="点击回复"></a><span id="sender_1" style="margin:5px; line-height:20px"></span></span></p>
            <p><font style="color:#777">内容：</font><br />
            	<div id="msg_content" style="overflow:auto; word-wrap:break-word; margin:10px;"></div>
                <div id="replybox" style="margin:10px; display:none;"><input id="receiver" type="hidden" name="receiver" /><textarea name="content" id="replycontent" style="width:400px; height:100px;"></textarea><br/><input type="button" value="回复" onclick="submitform()" class="button"/></div>
            </p>
			<p><input type="button" value="回复" onclick="reply()" class="button" id="rebtn"/></p>
            <p><font style="color:#777">发送时间：</font><span id="sendtime" style="margin:5px; line-height:20px"></span></p>
        </div>
    </form>
    </div>
</body>
</html>

