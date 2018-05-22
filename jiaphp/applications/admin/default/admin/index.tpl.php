<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>后台|JIACMS|美藤科技内容管理系统_{S('sitename')}</title>

<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
<style>
.frame .top{ height:70px;}
.frame .right{ top:70px;}
.tm {
height: 70px;
clear: both;
}
.tm li{ height:70px; line-height:36px;}
.tm>a{ display:block; width:230px; height:70px; float:left; position:relative; background:#353847;}
.tm>a span{ display:block; width:230px; height:70px;background-color: rgba(255,255,255,0.075);}
.tm>a img{ position:absolute; top:15px ; left:10px;}
.tm li a span.top_icon{ display:block; font-size:24px;padding-top:10px;}
.frame .top .menu{ top:10px;}
.drop-wl-down:hover ul{
	display:block
}
</style>

<link rel="alternate icon" type="image/png" href="{$skins_admin}/favicon.ico">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script type="text/javascript">
$(function(){
	$(".menugroup").click(function(){
		$(this).toggleClass("on");
		var rel = $(this).attr("rel");
		$("li[rel="+rel+"]:gt(0)").slideToggle('fast');
	})
	
	//$('.menuToggle').click(function(){
		//$(this).parent().find('ul').slideToggle();
	//});
	
	var langTitle = $('#langDown li.active a').html();
	$('#langBox').html(langTitle);
})
function MenuChange(obj,id){

	$('.left ul').hide();
	$('.show'+id).show();
	$('.show'+id+" li").eq(0).css("margin-top","0px");
	$(".tm ul li").removeClass("curr");
	$(obj).parent().addClass("curr");
	$('.show'+id+" li").removeClass('current');
	$('.show'+id+" li").eq(1).addClass('current');
	$('.show'+id+" li").eq(1).find('a').eq(0).click();
	
	var href = $('.show'+id+" li").eq(1).find('a').eq(0).attr('href');

	if(href!=undefined)
	  top.main.location.href=href;
	

}

function changeui(obj){
  var objval = $(obj).val();

  $.post("{U($m.'/admin')}?action=ui&uival="+objval,'',function(data){
     if(data.status==1) location.reload();
  },'json');
}
function changelang(val,obj){
  var objval = val;
  if(objval == ''){
	  message('请选择语言！');
	  return false;  
  }
  $.post("{U($m.'/admin')}?action=lang&uival="+objval,'',function(data){
	
     if(data.status==1) window.location.href = window.location.href;
	 else {
	    layer.msg(data.msg,{icon:2});	 
	 }
  },'json');
}
</script>
</head>

<body style="overflow:hidden; background:#f6f5f7" scroll="no">
<div class="frame">
	<div class="top">
		<div class="logo" style="display:none"><a href="{U($m.'/admin')}" class="logolo"> &nbsp;</a></div>
		<div class="menu fc-white">
      
        <div class="btn-group drop-wl-down">
      <button type="button" class="btn btn-success" id="langBox">默认语言</button>
      <button type="button" class="btn btn-success dropdown-toggle menuToggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
      
      </button>
      <ul class="dropdown-menu" id="langDown">

        {foreach $langs}
           <li {if $langid==LANG}class="active"{/if}><a href="javascript:;" {if $langid==LANG}class="active"{/if} onClick="changelang('{$langid}',this)">{$langname}</a></li>
        {/foreach}
      </ul>
      </ul>
    </div>
        <div class="btn-group drop-wl-down" style="margin-right: 10px;">
          <button type="button" class="btn btn-danger">菜单</button>
          <button type="button" class="btn btn-danger dropdown-toggle menuToggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
          </button>
          <ul class="dropdown-menu">
          
            <li><a href="{U($m.'/admin')}?action=edit&id={session('admin_nid')}" target="main">你好，{$_SESSION.admin_username}！</a></li>
            <li><a href="{U($m.'/admin')}">系统首页</a></li>
            <li><a href="{BASEURL}" target="_blank">网站首页</a> </li>
         
            <li role="separator" class="divider"></li>
            <li><a href='{U($m.'/index')}?action=out' class="out"><span class="glyphicon glyphicon-off" aria-hidden="true" style="font-size:18px; vertical-align:middle"></span>退出</a></li>
          </ul>
        </div>
       
       </div>

         <div class="tm">
        <a href="{U($m.'/admin')}"><span><img src="{$skins_admin}/logo_ui5_black.png" style="float:left;"></span></a>
    	<ul>

			<!--foreach $group $k $v -->

             {if $v.show==1}
        	<li class="nav {if $k==0} curr{/if}">
			<a href="javascript:;" onClick="MenuChange(this,'{$k}')"><span class="top_icon glyphicon {$v.icon}" aria-hidden="true"></span>{$v.name}</a></li>
            {/if}
			<!--/foreach -->
            <li class="nav"><a href="javascript:;" onClick="MenuChange(this,'-debug')"><span class="top_icon glyphicon glyphicon-th-large" aria-hidden="true"></span><b >栏目管理</b></a></li>
            
        </ul>
    </div>
     <div class="topnav">
    	<p><a href="javascript:;" id="togglemenu">隐藏菜单</a>
        <span class="fr"><a href="javascript:;">官方论坛</a> <a href="javascript:;">在线帮助</a></span>
        </p>
    </div>

	</div>

	<div class="left">
            <!--foreach $group $k $v -->
               <ul class="show{$k} {if $v.show==1 && $k==0} on{/if}">
                <li class="menulink" rel="group0" >
           <a href="javascript:;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>所有栏目</a>
         </li>


					<!--foreach $category -->
						<!--if $menugroup==$v.name -->
							<li class="menulink" rel="group{$k}" data-cid="{$id}" {if $k==0 && $index==1} style="margin-top:0px"{/if}  data-sons="{implode(",",get_sonids($id))}" data-allsons="{implode(",",get_all_sonids_admin($id))}"><a href="{U($m.'/admin')}?action=sub&catid={$id}" target="main"><span class="glyphicon {if $icon}{$icon}{else}glyphicon-asterisk{/if}" aria-hidden="true"></span>{$catname}</a>
                            <ul>
                            <?php getAllsonByDiGui($id,0,0);?>
                            </ul>
                            </li>
						<!--/if -->
					<!--/foreach -->

                    </ul>
            <!--/foreach -->

			<!--// 信息管理员 修改密码 -->


			
            <ul class="show-debug">
              <li class="menulink" rel="group0" >
           <a href="javascript:;"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>开发模式</a>
         </li>
			<li class="menulink" rel="debug"><a href="{U($m.'/dev')}" target="main"><span class="glyphicon glyphicon-th-list"></span>栏目设置</a></li>
           {if $debug}
            <li class="menulink" rel="debug"><a href="{U($m.'/dev')}?action=group" target="main"><span class="glyphicon glyphicon-magnet"></span>菜单分组</a></li>
           {/if}
            </ul>
			
		</ul>
	</div>
	<div class="right"><iframe name="main" id="main" frameborder="0" src="admin?action=homepage" scrolling="auto"></iframe></div>
</div>
<div class="message"></div>
<div class="loading" style="padding-left:120px;top:200px;">
<div class="opacity"><img src="{$skins_admin}/loading.gif" width="32" height="32"></div></div>
</body>
</html>
