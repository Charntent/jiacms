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
<script type="text/javascript" src="{_STATIC}/artdialog/jquery.artDialog.js?skin=black"></script>
<script type="text/javascript" src="{_STATIC}/artdialog/plugins/iframeTools.source.js"></script>
<script type="text/javascript" src="{_STATIC}/js/common.js"></script>
<script>
function dev_set(){
	var catname = $("input[name=catname]").val();
	var ident = $("input[name=ident]").val();
	var dir =  $("input[name=dir]").val();
	
	if(catname==''){
		dialog({content:'栏目名称不能为空！',time:1});
		$("input[name=catname]").focus();
		return false;
	}
	var reg = /^[a-zA-Z][a-zA-Z0-9]*$/;
	if(!reg.test(ident)){
		dialog({content:'栏目标识，字母开头，可以用字母和数字，不能有空格',time:2});
		$("input[name=ident]").focus();
		return false;
	}
	
	if(!reg.test(dir)){
		dialog({content:'自定义栏目url，字母开头，可以用字母和数字，不能有空格',time:2});
		$("input[name=dir]").focus();
		return false;
	}
	
    return true;
}

function dev_set_pl(){
	var catname = $("textarea[name=catnames]").val();

	if(catname==''){
		dialog({content:'栏目名称不能为空！',time:1});
		$("textarea[name=catnames]").focus();
		return false;
	}
    return true;
}
</script>
</head>

<body>
 
<div style="padding:20px;min-width:1000px;">
<!--if empty($action) -->
	
     <div class="panel panel-warning" id="pane_panel_warning">
   <div class="panel-heading">
    <div class="btn-group" role="group" aria-label="..."> <span class="fl" style="line-height:34px; margin-right:10px">栏目管理-</span><a href="?action=add" class="addicon btn btn-danger"><span class="glyphicon  glyphicon-plus" aria-hidden="true"></span>添加栏目</a><a href="?action=batchadd" class="addicon btn btn-primary"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>批量添加栏目</a></div>
    </div>
    <div class="panel-body">
 	<div class="form">
		<form method="post">
			<table width="100%" border="1" cellspacing="0" cellpadding="0" class="tr_line wlcms_dev">
				<tr>
                	<th width="50"></th>
                    <th width="50"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px"></th>
                    <th width="118">菜单分组</th>
					<th width="50">栏目ID</th>
					<th align="left">栏目名</th>
					<th>栏目类型</th>
					<th>导航显示</th>
                    {if $debug}
					<th>标题</th>
					<th>缩略图</th>
					<th>关键词</th>
					<th>描述</th>
					<th>内容</th>
                    {/if}
					<th width="120">处理模板</th>
                    
					<th width="60">分页大小</th>
					<th>排序</th>
					<th width="255">管理</th>
				</tr>
				{eval echo OutCatgorys(0,0);}
			</table>
			<div style="text-align:right;padding:10px;" >
              <div class="btn-group" role="group" aria-label="...">
              <button type="button" class="btn btn-info fanxuan" id="fanxuan"><span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>反选</button>
               <button name="sub" type="button"  onClick="setDelAll()" class="btn btn-danger"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</button>
   <button type="button" class="btn btn-success" onClick="setGroups()"><span class="glyphicon glyphicon-th" aria-hidden="true"></span>加入分组</button>
    <input type="hidden" name="sub" value="1">
            <button name="sudb" type="submit"  class="btn btn-success"><span class="glyphicon glyphicon glyphicon-repeat" aria-hidden="true"></span>提交</button>
            </div>
            </div>
		</form>
	</div>
    </div></div>
<script type="text/javascript">

function setGroups(){
	 var ids = $("input[name=id]:checked"); 
	
	 if(ids.length == 0) {
		window.top.message("请选择左边的ID!");
		 return false;
	 }
	  var str = "";
		 $.each(ids,function(index,value){
			  str = str +","+$(this).val();
	  });
	 $.post("{U($m.'/dev')}?action=setgroups",{},function(data){
		  var list = art.dialog.list;
				for (var i in list) {
					list[i].close();
		  };
		 art.dialog({content:data,ok:function(){
			  var groupid  = $("#groupid").val();
			  $.post("{U($m.'/dev')}?action=setgroups_do",{doids:str,type:groupid},function(data){
				   var list = art.dialog.list;
				   for (var i in list) {
		   			   list[i].close();
		           };
				   art.dialog({content:data.msg,time:1});
			  },'json');
			 
		 },width:300,cancelVal: '关闭',
		   cancel: true});
	 },'html');  
}
function setDelAll(){
	
	var ids = $("input[name=id]:checked"); 
	
	 if(ids.length == 0) {
		window.top.message("请选择左边的ID!");
		 return false;
	 }
	  var str = "";
		 $.each(ids,function(index,value){
			  str = str +","+$(this).val();
	  });
      art.dialog({content:'你真的要删除吗？删除了就不能恢复！如果你删除了上级，下级不选也会一起删除！',ok:function(){

			  $.post("{U($m.'/dev')}?action=setdelall",{doids:str},function(data){
				   var list = art.dialog.list;
				   for (var i in list) {
		   			   list[i].close();
		           };
				   art.dialog({content:data.msg,time:1});
				   setTimeout(function(){window.location.reload();},1000)
			  },'json');
			 
		 },width:300,cancelVal: '关闭',
		   cancel: true});
	
}
function _Setstatu(str,obj){
	var ar = str.split(',');
	var status = $(obj).data('status');
	var handid = $(obj).data('handid');
	for(var i =0; i<ar.length;i++){
		if(status == 1){
		   $("#tr"+ar[i]).hide();
		   $(obj).data('status',0);
		   $("#tr"+ar[i]).data('status',1);
		 
		}
		else{
		  $("#tr"+ar[i]).show();
		  $(obj).data('status',1);
		  $("#tr"+ar[i]).data('status',0);
		}
	}
	
	if(status == 1){
       set_status_2(handid,0);
	    var allsons = $(obj).data('allsons');
	    if(allsons!=''){
		  allsons = allsons.toString();
		  var allsons_ar = allsons.split(','); 
		  for(var i =0; i<allsons_ar.length;i++){
			  $("#tr"+allsons_ar[i]).hide();
		  }
	   }
	}
	if(status == 0){
       set_status_2(handid,1);
	}
	
}
function set_status_2(name,status){
	if(status == 0){
		  setcookie("c_c_"+name, 0, -1, '/');
	}
	if(status == 1){
		  setcookie("c_c_"+name, 1, 3600*1000, '/');
	}
}
function set_status(name,status){
	var allcc = getcookie('c_c');

	var str = "";

	if(status == 0){

	    //关闭它，就是删除它
		if(allcc!=null && allcc!=0){
			var ar = allcc.split('k');
		
			for(var i in ar){
				
			    if(ar[i]!=name)	{
					if(str == "")
					   str = ar[i].toString();
					else
					   str = str+"k"+ar[i].toString();
				}
				
			}
		  str = str.toString();
		  setcookie("c_c", str, 3600*1000, '/');
		  
		}	
	}else
	if(status ==1){
		//添加上去
		if(allcc!=null && allcc!=0){
			var ar = allcc.split('k');
		
			for(var i in ar){
				
			    if(ar[i]!=name)	{
					if(str == "")
					   str = ar[i].toString();
					else
					   str = str+"k"+ar[i];
				}
			}
			 if(str !="")
			 str = str+"k"+name;
			 else
			 str = name;
	       str = str.toString();
		   setcookie("c_c", str, 3600*1000, '/');	
		  
		}else{
		   setcookie("c_c", name, 3600*1000, '/');	
		}	
	}

}

function getcookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
 
    if(arr=document.cookie.match(reg))
 
        return (arr[2]);
    else
        return null;
}

function setcookie(cookieName, cookieValue, seconds, path, domain, secure) {
	var expires = new Date();
	expires.setTime(expires.getTime() + seconds);
	document.cookie = escape(cookieName) + '=' + escape(cookieValue)
	+ (expires ? '; expires=' + expires.toGMTString() : '')
	+ (path ? '; path=' + path : '/')
	+ (domain ? '; domain=' + domain : '')
	+ (secure ? '; secure' : '');
}
$(function(){
   // setcookie("c_c",0,-1,"/");
	//setInterval(function(){alert(getcookie('c_c'));},3000);
  $(".tr-bg-0").each(function(index, element) {
       var zhankaistr = getcookie('c_c_'+$(this).attr('pid'));
	   if(zhankaistr == 1 ) $("#img_click_"+$(this).attr('pid')).click();
  });
	/*var zhankaistr = getcookie('c_c');
	
	if(zhankaistr !=null && zhankaistr !=0){
		var strar = zhankaistr.split('k');
		for(var j in strar){
			$("#img_click_"+strar[j]).click();
		}
	}
	*/
   	
});

</script>
<!--/if -->
<!--if $action=='add' || $action=='edit' -->
<div class="dialog_icon">
</div>
 <div class="bootstrap_icon">
   <div class="bs-glyphicons">
    <ul class="bs-glyphicons-list">
         <li onClick="$('.bootstrap_icon').fadeOut();">
          <img src="{$skins_admin}/close.png">
          <span class="glyphicon-class">关闭</span>
        </li>
        <li>
          <span class="glyphicon glyphicon-asterisk" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-asterisk</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-plus</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-euro" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-euro</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-eur" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-eur</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-minus</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cloud" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cloud</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-envelope</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-pencil</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-glass" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-glass</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-music" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-music</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-search</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-heart</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-star</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-star-empty</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-user</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-film" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-film</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-th-large</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-th</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-th-list</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ok</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-remove</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-zoom-in</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-zoom-out" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-zoom-out</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-off</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-signal" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-signal</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cog</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-trash</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-home</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-file</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-time" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-time</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-road" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-road</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-download-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-download" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-download</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-upload" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-upload</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-inbox</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-play-circle</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-repeat" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-repeat</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-refresh</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-list-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-lock</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-flag</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-headphones" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-headphones</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-volume-off" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-volume-off</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-volume-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-volume-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-volume-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-qrcode" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-qrcode</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-barcode</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tag" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tag</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tags" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tags</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-book</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bookmark" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bookmark</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-print</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-camera" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-camera</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-font</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bold" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bold</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-italic" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-italic</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-text-height" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-text-height</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-text-width" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-text-width</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-align-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-align-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-align-center" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-align-center</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-align-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-align-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-align-justify</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-list" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-list</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-indent-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-indent-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-indent-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-facetime-video" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-facetime-video</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-picture" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-picture</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-map-marker</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-adjust" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-adjust</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tint" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tint</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-edit</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-share" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-share</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-check</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-move" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-move</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-step-backward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-step-backward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-fast-backward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-fast-backward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-backward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-play</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-pause" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-pause</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-stop</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-forward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-fast-forward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-fast-forward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-step-forward" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-step-forward</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-eject" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-eject</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-chevron-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-chevron-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-plus-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-minus-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-remove-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ok-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-question-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-info-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-screenshot" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-screenshot</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-remove-circle</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ok-circle" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ok-circle</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ban-circle</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-arrow-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-arrow-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-arrow-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-arrow-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-share-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-resize-full</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-resize-small</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-exclamation-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-gift" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-gift</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-leaf" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-leaf</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-fire" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-fire</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-eye-open</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-eye-close</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-warning-sign</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-plane" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-plane</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-calendar</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-random" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-random</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-comment</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-magnet" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-magnet</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-chevron-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-chevron-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-retweet</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-shopping-cart</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-folder-close</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-folder-open</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-resize-vertical</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-resize-horizontal</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hdd" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hdd</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bullhorn</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bell</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-certificate" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-certificate</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-thumbs-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-thumbs-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hand-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hand-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hand-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hand-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hand-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hand-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-circle-arrow-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-circle-arrow-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-circle-arrow-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-circle-arrow-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-circle-arrow-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-globe</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-wrench</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tasks</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-filter" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-filter</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-briefcase</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-fullscreen</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-dashboard</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-paperclip</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-heart-empty" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-heart-empty</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-link" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-link</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-phone</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-pushpin</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-usd" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-usd</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-gbp" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-gbp</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-alphabet" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-alphabet</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-alphabet-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-alphabet-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-order" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-order</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-order-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-order-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-attributes" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-attributes</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sort-by-attributes-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sort-by-attributes-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-unchecked</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-expand" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-expand</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-collapse-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-collapse-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-collapse-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-collapse-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-log-in</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-flash" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-flash</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-log-out</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-new-window</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-record" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-record</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-save" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-save</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-open</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-saved</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-import" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-import</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-export" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-export</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-send" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-send</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-floppy-disk</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-floppy-saved" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-floppy-saved</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-floppy-remove" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-floppy-remove</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-floppy-save</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-floppy-open" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-floppy-open</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-credit-card" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-credit-card</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-transfer</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cutlery</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-header" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-header</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-compressed" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-compressed</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-earphone</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-phone-alt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-phone-alt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tower" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tower</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-stats" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-stats</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sd-video" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sd-video</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hd-video" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hd-video</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-subtitles" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-subtitles</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sound-stereo" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sound-stereo</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sound-dolby" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sound-dolby</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sound-5-1" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sound-5-1</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sound-6-1" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sound-6-1</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sound-7-1" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sound-7-1</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-copyright-mark</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-registration-mark" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-registration-mark</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cloud-download" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cloud-download</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cloud-upload</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tree-conifer" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tree-conifer</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tree-deciduous" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tree-deciduous</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-cd" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-cd</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-save-file" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-save-file</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-open-file</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-level-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-level-up</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-copy" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-copy</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-paste" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-paste</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-alert</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-equalizer</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-king" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-king</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-queen" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-queen</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-pawn" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-pawn</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bishop" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bishop</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-knight" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-knight</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-baby-formula" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-baby-formula</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-tent" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-tent</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-blackboard" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-blackboard</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bed" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bed</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-apple" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-apple</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-erase</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-hourglass" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-hourglass</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-lamp" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-lamp</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-duplicate</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-piggy-bank</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-scissors" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-scissors</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-bitcoin" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-bitcoin</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-btc" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-btc</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-xbt" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-xbt</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-yen" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-yen</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-jpy" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-jpy</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ruble" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ruble</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-rub" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-rub</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-scale" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-scale</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ice-lolly" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ice-lolly</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-ice-lolly-tasted" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-ice-lolly-tasted</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-education" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-education</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-option-horizontal" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-option-horizontal</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-option-vertical</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-menu-hamburger</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-modal-window" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-modal-window</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-oil" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-oil</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-grain" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-grain</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-sunglasses" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-sunglasses</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-text-size" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-text-size</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-text-color" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-text-color</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-text-background" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-text-background</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-top" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-top</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-bottom" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-bottom</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-horizontal" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-horizontal</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-vertical" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-vertical</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-object-align-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-object-align-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-triangle-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-triangle-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-triangle-bottom" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-triangle-bottom</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-triangle-top" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-triangle-top</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-console" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-console</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-superscript" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-superscript</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-subscript" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-subscript</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-menu-left" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-menu-left</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-menu-right</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-menu-down</span>
        </li>
      
        <li>
          <span class="glyphicon glyphicon-menu-up" aria-hidden="true"></span>
          <span class="glyphicon-class">glyphicon glyphicon-menu-up</span>
        </li>
      
    </ul>
  </div>
 </div>
 <script>
 
 $(function(){
	 $(".bs-glyphicons-list li").click(function(){
	    var class_name = $(this).find('span').eq(0).attr('class');
		if(class_name !='glyphicon-class') {
			class_name = class_name.replace('glyphicon',""); 
			
			$("#glyphicon_icon").attr('class',$("#glyphicon_icon").attr('class').replace(icon.value,"")+class_name);
			icon.value = class_name;
			$('.bootstrap_icon').fadeOut();
		}
		
		
		
	 });
 })
 </script>
<!--/if-->
 <!--if $action=='add'-->
	<form method="post" onSubmit="return dev_set()">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
         <tr>
			<td width="90"></td>
			<td>
				<div style="color:#f00; text-align:center; font-weight:bold; font-size:20px; margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:20px;">栏目添加说明：不管在哪个语言版本下添加栏目，如果不勾选语言独有，不会自动生成其他语言对应的栏目</div>
			</td>
		</tr>	
		<tr>
			<td width="90">上级栏目：</td>
			<td>
				<select name="parentid" class="form-control" style="width:200px;">
				<option value="0">顶级栏目</option>
                {eval echo fetchZiDianModel(0,0,$pid);}
				</select>
			</td>
		</tr>	
		<tr>
			<td width="90" >栏目名称：</td>
			<td><input name="catname" type="text" style="width:200px;" class="form-control"></td>
		</tr>
        <tr style="display:none">
			<td width="90">栏目标识</td>
			<td><input name="ident" type="text" class="form-control" style="width:200px;display:inline-block" value="{$ident_auto}">
             <div style="display:inline-block; color:#f00; font-weight:bold; padding:10px 0">
            （字母开头，只能输入字母和数字，用于多版本的通信,必填！而且不能有相同！必填！）
            </div>
            </td>
		</tr>
        {if $debug}
          <tr>
			<td width="90">栏目备注</td>
			<td><input name="transname" type="text" value="" class="form-control" style="width:200px;display:inline-block">
            <div style="display:inline-block; padding:10px 0">
            （其他语言看不懂时可以看这个，选填）
            </div>
            </td>
		</tr>
        {/if}
         <tr>
			<td width="90">自定义栏目链接</td>
			<td><input name="dir" type="text" value="" class="form-control" style="width:200px;display:inline-block">
            
             <div style=" display:inline-block;padding:10px 0; font-weight:bold; color:#f30">
               (只能用英文字母、数字作为链接，用于优化seo，而且同语言不能相同！）
             </div>
             <label><input name="addpare" type="checkbox" value="1" > （勾选表示url会续上上级栏目，比如上级是news,填news1表示该栏目的url为news/news1，如果不勾选，那么该栏目的url为news1）</label>
            </td>
		</tr>
          
        <tr>
			<td width="90">左边的图标</td>
			<td><input name="icon" type="text" value=" glyphicon-th" class="form-control fl" style="width:200px;" id="icon">
              <span class="glyphicon glyphicon-th" style="display:block; line-height:34px; float:left; padding-left:10px; font-size:20px;" id="glyphicon_icon"></span>
            <a href="javascript:void(0)" onClick="$('.bootstrap_icon').show();" class="fl" style="line-height:34px; padding-left:10px;">更换图标</a>
            </td>
		</tr>

		<tr>
			<td width="90">栏目类型：</td>
			<td>
				<select name="cattype" onChange="changetype(this.value);" class="form-control" style="width:200px;">
					<option value="0">请选择</option>
					<option value="article">文章栏目</option>
					<option value="page">独立页面</option>
					<option value="diypage">自定义处理文件</option>
				</select>
			</td>
		</tr>
        <tr style="display:none" class="article_tr">
			<td width="90">排序</td>
			<td><label><input name="e_sort" type="checkbox" value="1" > 是否允许自定义排序</label></td>
		</tr>
        {if $debug}
		<tr style="display:none" class="article_tr">
			<td width="90">小分类：</td>
			<td><textarea name="subtype" style="width:450px; height:120px;" class="form-control"></textarea> 分类标识|分类名称，多个换行<br><label><input name="e_subtype" type="checkbox" value="1">允许编辑分类</label></td>
		</tr>
		<tr style="display:none" class="article_tr page_tr">
			<td width="90">字段设置：</td>
			<td><div style="border:dashed 1px #CCC">字段设置举例：<br>input输入框|price|input|请输入xx|￥100<br>
textarea输入框|score|textarea<br>
select选择|sex|select|请输入性别|女|男,女<br>
radio选择|sex2|radio|请输入性别|女|男,女<br>
checkbox多选|sex3|checkbox|请输入性别|男,女|男,女<br>
文件上传|upfile|file<br>
迷你编辑器|mini_id|minieditor<br>
全功能编辑器|editor_id|editor<br>
添加到主字段|field1|input|主字段field1~field5可作为查询条件</div>
			<textarea name="fields" style="width:695px; height:160px;" class="form-control"></textarea></td>
		</tr>
        {/if}
		<tr style="display:none" class="article_tr page_tr">
			<td width="90">模板文件：</td>
			<td><input name="template" type="text" id="template" style="width:400px;" class="form-control" value=""> 注：文章栏目设置为 栏目模板名|文章模板名，不含 .tpl.php</td>
		</tr>
		<tr style="display:none" class="diypage_tr">
			<td width="90">处理程序：</td>
			<td>
				后台 <input name="phpscript[0]" type="text" style="width:180px;" value="datalist?table=表名" class="form-control">
				前台 <input name="phpscript[1]" type="text" style="width:180px;" class="form-control">
			</td>
		</tr>
        
          {if $debug}  
        <tr style="display:none" class="article_tr">
			<td width="90">是否是封面：</td>
			<td>
			<label><input name="isend" type="checkbox" value="1">封面不能添加文章</label></td>
		</tr>
		
		 <tr>
			<td width="90">栏目分组：</td>
			<td>
				<select name="catgroup"  class="form-control" style="width:200px;">
					<option value="">不分组</option>
				    {foreach $cats_groups $k $v}
                    <option value="{$k}">{$v}</option>
                    {/foreach}
				</select>
			</td>
		</tr>
        {/if}
         <tr>
			<td width="90">是否同步到其他语言？</td>
			<td><label><input name="langsingle" type="checkbox" value="1" > （勾选的话同步该栏目到其他语言）</label></td>
		</tr>
		<tr>
			<td></td>
			<td>
            <input type="hidden" name="lang" value="{LANG}">
             <input type="hidden" name="sub" value="1">
            <button   name="sudb" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
            </td>
		</tr>
	</table>
	</form>
<script type="text/javascript">
function changetype(id){
	$(".article_tr,.page_tr,.diypage_tr").hide();
	$("."+id+"_tr").show();
	if(id=='article'){
		$("#template").val('article|show');
	}else if(id=='page'){
		$("#template").val('page');
	}
}
</script>
<!--/if -->




 <!--if $action=='batchadd'-->
	<form method="post" onSubmit="return dev_set_pl()">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
         <tr>
			<td width="90"></td>
			<td>
				<div style="color:#f00; text-align:center; font-weight:bold; font-size:20px; margin-bottom:20px; border-bottom:1px solid #eee; padding-bottom:20px;">栏目添加说明：不管在哪个语言版本下添加栏目，如果不勾选语言独有，会自动生成其他语言对应的栏目，不用再次添加！</div>
			</td>
		</tr>	
		<tr>
			<td width="90">上级栏目：</td>
			<td>
				<select name="parentid" class="form-control" style="width:200px;">
				<option value="0">顶级栏目</option>
                {eval echo fetchZiDianModel(0,0,$pid);}
				</select>
			</td>
		</tr>	
		<tr>
			<td width="90" >栏目名称：</td>
			<td><textarea name="catnames" style="width: 583px; margin: 0px; height: 110px;" class="form-control"></textarea>
            <div style=" display:block;padding:10px 0; font-weight:bold; color:#f30">
               批量输入栏目名称,用英文的,隔开；例如输入:新闻资讯,国内新闻
             </div>
            </td>
		</tr>
         <tr>
			<td width="90">自定义栏目链接</td>
			<td><textarea name="dirs" style="width: 583px; margin: 0px; height: 110px;" class="form-control"></textarea>
            
             <div style=" display:block;padding:10px 0; font-weight:bold; color:#f30">
               ( 批量输入对应栏目的链接,用英文的,隔开；例如输入:news,china）
             </div>
           
            </td>
		</tr>
          
      

		<tr>
			<td width="90">栏目类型：</td>
			<td>
				<select name="cattype" onChange="changetype(this.value);" class="form-control" style="width:200px;">
					<option value="0">请选择</option>
					<option value="article">文章栏目</option>
					<option value="page">独立页面</option>
					<option value="diypage">自定义处理文件</option>
				</select>
			</td>
		</tr>
        <tr style="display:none" class="article_tr">
			<td width="90">排序</td>
			<td><label><input name="e_sort" type="checkbox" value="1" > 是否允许自定义排序</label></td>
		</tr>
		 {if $debug}
		<tr style="display:none" class="article_tr">
			<td width="90">小分类：</td>
			<td><textarea name="subtype" style="width:450px; height:120px;" class="form-control"></textarea> 分类标识|分类名称，多个换行<br><label><input name="e_subtype" type="checkbox" value="1">允许编辑分类</label></td>
		</tr>
		<tr style="display:none" class="article_tr page_tr">
			<td width="90">字段设置：</td>
			<td><div style="border:dashed 1px #CCC">字段设置举例：<br>input输入框|price|input|请输入xx|￥100<br>
textarea输入框|score|textarea<br>
select选择|sex|select|请输入性别|女|男,女<br>
radio选择|sex2|radio|请输入性别|女|男,女<br>
checkbox多选|sex3|checkbox|请输入性别|男,女|男,女<br>
文件上传|upfile|file<br>
迷你编辑器|mini_id|minieditor<br>
全功能编辑器|editor_id|editor<br>
添加到主字段|field1|input|主字段field1~field5可作为查询条件</div>
			<textarea name="fields" style="width:695px; height:160px;" class="form-control"></textarea></td>
		</tr>
        {/if}
		<tr style="display:none" class="article_tr page_tr">
			<td width="90">模板文件：</td>
			<td><input name="template" type="text" id="template" style="width:400px;" class="form-control" value=""> 注：文章栏目设置为 栏目模板名|文章模板名，不含 .tpl.php</td>
		</tr>
		<tr style="display:none" class="diypage_tr">
			<td width="90">处理程序：</td>
			<td>
				后台 <input name="phpscript[0]" type="text" style="width:180px;" value="datalist?table=表名" class="form-control">
				前台 <input name="phpscript[1]" type="text" style="width:180px;" class="form-control">
			</td>
		</tr>
         {if $debug}
           
        <tr style="display:none" class="article_tr">
			<td width="90">是否是封面：</td>
			<td>
			<label><input name="isend" type="checkbox" value="1">封面不能添加文章</label></td>
		</tr>
		
		 {/if}
          <tr>
			<td width="90">是否同步到其他语言？</td>
			<td><label><input name="langsingle" type="checkbox" value="1" > （勾选的话同步该栏目到其他语言）</label></td>
		</tr>
		<tr>
			<td></td>
			<td>
            <input type="hidden" name="lang" value="{LANG}">
             <input type="hidden" name="sub" value="1">
            <button   name="sudb" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
            </td>
		</tr>
	</table>
	</form>
<script type="text/javascript">
function changetype(id){
	$(".article_tr,.page_tr,.diypage_tr").hide();
	$("."+id+"_tr").show();
	if(id=='article'){
		$("#template").val('article|show');
	}else if(id=='page'){
		$("#template").val('page');
	}
}
</script>
<!--/if -->


<!--if $action=='edit' -->
	<form method="post" onSubmit="return getgroupset()">
	<table border="0" cellspacing="0" cellpadding="0" class="form">
		<tr>
			<td width="90">上级栏目：</td>
			<td>
			<!--if $r.parentid==0 -->
			顶级栏目（顶级栏目禁止转移）
			<!--else -->
				<select name="parentid" onChange="return false; "  class="form-control" style="width:200px">
					<option value="0">顶级栏目</option>
					{eval echo fetchZiDianModel(0,0,$r["parentid"]);}
				</select>
			<!--/if -->
			</td>
		</tr>	
		<tr>
			<td width="90">栏目名称：</td>
			<td><input name="catname" type="text" style="width:400px;" value="{$r.catname}"  class="form-control"></td>
		</tr>
        <tr style="display:none">
			<td width="90">栏目标识（用于多版本的通信）</td>
			<td><input name="ident" type="text" value="{$r.ident}" disabled class="form-control" style="width:400px;"></td>
		</tr>
          {if $debug}
          <tr>
			<td width="90">栏目备注（其他语言看不懂时可以看这个）</td>
			<td><input name="transname" type="text" value="{$r.transname}" class="form-control" style="width:400px;"></td>
		</tr>
       {/if}
        <tr>
			<td width="90">自定义栏目url（不能相同）</td>
			<td><input name="dir" type="text" value="{$r.dir}"  class="form-control" style="width:200px; display:inline-block">
            <div style=" display:inline-block;padding:10px 0; font-weight:bold; color:#f30">
               (只能用英文字母、数字作为url，用于优化seo，而且同语言不能相同！）
             </div>
             <label><input name="addpare" type="checkbox" value="1" {if $r.addpare==1} checked{/if}> （勾选表示url会续上上级栏目，比如上级是news,填news1表示该栏目的url为news/news1，如果不勾选，那么该栏目的url为news1）</label>
            </td>
		</tr>
         <tr>
			<td width="90">左边的图标</td>
			<td><input name="icon" type="text" value="{$r.icon}" class="form-control" style="width:200px; float:left" id="icon">
              <span class="glyphicon {$r.icon}" style="display:block; line-height:34px; float:left; padding-left:10px; font-size:20px;" id="glyphicon_icon"></span>
            <a href="javascript:void(0)" onClick="$('.bootstrap_icon').show();" class="fl" style="line-height:34px; padding-left:10px;">更换图标</a>
            </td>
		</tr>
        
      
      
        
        
		<tr>
			<td width="90">栏目类型：</td>
			<td>
				<select name="cattype" onChange="changetype(this.value);"  class="form-control" style="width:200px;">
					<option value="0">请选择</option>
					<option value="article" {if $r.cattype=='article'}selected{/if}>文章栏目</option>
					<option value="page" {if $r.cattype=='page'}selected{/if}>独立页面</option>
					<option value="diypage" {if $r.cattype=='diypage'}selected{/if}>自定义处理文件</option>
				</select>
			</td>
		</tr>
        
          <tr style="display:none" class="article_tr page_tr">
			<td width="90">模板文件：</td>
			<td><input name="template" type="text" style="width:400px;"   class="form-control" value="{$r.template}">  注：文章栏目设置为 栏目模板名|文章模板名，不含 .tpl.php</td>
		</tr>
        <tr style="display:none" class="diypage_tr">
			<td width="90">处理程序：</td>
			<td> 
				后台 <input name="phpscript[0]" type="text" style="width:180px;" value="{@$phpscript.0}"  class="form-control">
				前台 <input name="phpscript[1]" type="text" style="width:180px;" value="{@$phpscript.1}"  class="form-control">
			</td>
		</tr>
        
        {if $debug} 
       
		<tr style="display:none" class="article_tr page_tr">
			<td width="90">字段设置：</td>
			<td>
			<div style="border:dashed 1px #CCC">字段设置举例：<br>input输入框|price|input|请输入xx|￥100<br>
textarea输入框|score|textarea<br>
select选择|sex|select|请输入性别|女|男,女<br>
radio选择|sex2|radio|请输入性别|女|男,女<br>
checkbox多选|sex3|checkbox|请输入性别|男,女|男,女<br>
文件上传|upfile|file<br>
迷你编辑器|mini_id|minieditor<br>
全功能编辑器|editor_id|editor<br>
添加到主字段|field1|input|主字段field1~field5可作为查询条件</div>
			<textarea name="fields" style="width:695px; height:160px;"  class="form-control">{$r.fields}</textarea> </td>
		</tr>
        {/if}
         <tr style="display:none" class="article_tr">
			<td width="90">排序</td>
			<td>
            <input name="e_sort" type="hidden" value="0">
            <label><input name="e_sort" type="checkbox" value="1" {if $r.e_sort=='1'}checked{/if}> 是否允许自定义排序</label></td>
		</tr>
		 {if $debug}
		<tr style="display:none" class="article_tr">
			<td width="90">小分类：</td>
			<td><textarea name="subtype" style="width:450px; height:120px;"  class="form-control">{$r.subtype}</textarea> 分类标识|分类名称，多个换行<br>
			<input name="e_subtype" type="hidden" value="0">
			<label><input name="e_subtype" type="checkbox" value="1" {if $r.e_subtype=='1'}checked{/if}>允许编辑分类</label></td>
		</tr>
        
        
        <tr style="display:none" class="article_tr">
			<td width="90">是否是封面：</td>
			<td>
			<label><input name="isend" type="checkbox" value="1" {if $r.isend=='1'}checked{/if}>封面不能添加文章</label></td>
		</tr>
        
        
		
		
        
        
		  <tr>
			<td width="90">栏目分组：</td>
			<td>
				<select name="catgroup"  class="form-control" id="catgroup"  onChange="changegroup(this.value);" style="width:200px;">
					<option value="">不分组</option>
				    {foreach $cats_groups $k $v}
                    <option value="{$k}" {if $r.catgroup==$k} selected{/if}>{$v}</option>
                    {/foreach}
				</select>
                <div style="border:dashed 1px #CCC">
                将相同的栏目属性归组后，可以选择同步"<span style="color:#f00; font-size:16px; font-weight:bold">栏目类型、小分类、字段设置以及模板</span>"！
                </div>
			</td>
		</tr>
        
         <tr {if !$r.catgroup}style="display:none"{/if} class="istongbu_tr" id="istongbu_tr">
			<td width="90">是否同步组里的其他栏目？</td>
			<td>
            
              <ul class="ui4_radio">
                <li data-type="yes">是</li>
                 <li class="active" data-type="no">否</li>
              </ul>
              <input type="hidden" name="istb" id="istb" value="0">
              <input type="hidden" name="tbids" id="tbids" value="">
            </td>
		</tr>
		{/if}
		<tr>
			<td></td>
			<td>
             <input type="hidden" name="lang" value="{LANG}">
            <input   name="sub" type="submit" value="提交" class="btn btn-success"></td>
		</tr>
	</table>
	</form>
<script type="text/javascript">
function changetype(id){
	$(".article_tr,.page_tr,.diypage_tr").hide();
	$("."+id+"_tr").show();
}
changetype('{$r.cattype}');
function changegroup(id){
	if(id!=''){
		$("#istongbu_tr").fadeIn();
		if($(".ui4_radio .active").data('type')=='yes'){
			$(".ui4_radio .active").click();
		}
	}else{
		$("#istongbu_tr").fadeOut();
		$(".aui_close").click();
	}
}

$(function(){
   $(".ui4_radio li").click(function(){
	     $(".ui4_radio li").removeClass("active");
		 $(this).addClass('active');
		 
		 if($(this).data('type')=='yes'){
			 $("#istb").val(1);
			 var catgroup = $("#catgroup").val();
			 
			
			 $.post("{U($m.'/dev')}",{action:'getroups',catgroupid:catgroup},function(data){
				var list = art.dialog.list;
				for (var i in list) {
					list[i].close();
				};
			    art.dialog({content:data,title:'结果',left:'620px',top:0,width:370});
			 },'html');
		 }else{
			var list = art.dialog.list;
			for (var i in list) {
				list[i].close();
			};
			 $("#istb").val(0);
		 }
   });
})

function getgroupset(){

	var istb = $("#istb").val();
	
	if(istb==1){
		var l = $(".tbid_check:checked").length;
		var str = '';
		$(".tbid_check:checked").each(function(index, element) {
            str = str+(str==''?$(element).val():','+$(element).val());
        });
		$("#tbids").val(str);
	}
	return dev_set();
}


</script>
<!--/if -->
<!--if $action=='group' -->
	<div class="subnav"><span>菜单分组</span></div>
	<form method="post">
		<table border="0" cellspacing="0" cellpadding="0" class="form" >
			<tr>
				<th align="left">分组名称</th>
				<th align="left">展开</th>
			</tr>
			<!--foreach $data $k $r -->
			<tr>
				<td width="180"><input name="data[]" type="text" value="{$r.name}" style="width:120px;"  class="form-control"></td>
				<td width="300"><select name="data[]"  class="form-control" ><option value="1" {if $r.show==1}selected{/if}>默认展开</option><option value="0" {if $r.show==0}selected{/if}>默认隐藏</option></select>
              </td>
               <td><input name="data[]" type="text" value="{$r.icon}" style="display:inline; width:150px"  class="form-control"> <a href="javascript:void(0)" onClick="d(this);" class="btn btn-danger">删除</a></td>
			</tr>
			<!--/foreach -->
			<tr class="table_add">
				<td><a href="javascript:void(0)" onClick="add(this)"  class="btn btn-info">添加分组</a></td>
				<td></td>
			</tr>
			<tr>
				<td><input name="submit" type="submit" value="提交" class="btn btn-block  btn-success"></td>
				<td></td>
                <td></td>
			</tr>
		</table>
	</form>
<script type="text/javascript">
function d(obj){
	if(confirm("删除？")) $(obj).parent().parent().remove();
}
function add(obj){
	$(".table_add").before('<tr>\
				<td><input name="data[]" type="text" style="width:120px;"  class="form-control"></td>\
				<td><select name="data[]"  class="form-control"><option value="1">默认展开</option><option value="0">默认隐藏</option></select></td>\
				<td><input name="data[]" type="text"  style="display:inline; width:150px"  class="form-control"> <a href="javascript:void(0)" onClick="d(this);" class="btn btn-danger">删除</a></td>\
			</tr>');
}
</script>
<!--/if -->
</div>
</body>
</html>