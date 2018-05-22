<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>后台|JIACMS|美藤科技内容管理系统_<?php echo S('sitename') ?></title>

<link href="<?php echo $skins_admin ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $skins_admin ?>/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $skins_admin ?>/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/layer/layer.js"></script>
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

<link rel="alternate icon" type="image/png" href="<?php echo $skins_admin ?>/favicon.ico">
<script type="text/javascript" src="<?php echo $skins_admin ?>/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/base.js"></script>
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

  $.post("<?php echo U($m.'/admin') ?>?action=ui&uival="+objval,'',function(data){
     if(data.status==1) location.reload();
  },'json');
}
function changelang(val,obj){
  var objval = val;
  if(objval == ''){
	  message('请选择语言！');
	  return false;  
  }
  $.post("<?php echo U($m.'/admin') ?>?action=lang&uival="+objval,'',function(data){
	
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
		<div class="logo" style="display:none"><a href="<?php echo U($m.'/admin') ?>" class="logolo"> &nbsp;</a></div>
		<div class="menu fc-white">
      
        <div class="btn-group drop-wl-down">
      <button type="button" class="btn btn-success" id="langBox">默认语言</button>
      <button type="button" class="btn btn-success dropdown-toggle menuToggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
      
      </button>
      <ul class="dropdown-menu" id="langDown">

        <?php Tag::var_protect("IN"); $index=0; if(is_array($langs)) foreach($langs as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>
           <li <?php if($langid==LANG) { ?>class="active"<?php } ?>><a href="javascript:;" <?php if($langid==LANG) { ?>class="active"<?php } ?> onClick="changelang('<?php echo $langid ?>',this)"><?php echo $langname ?></a></li>
        <?php };  Tag::var_protect("OUT"); ?>
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
          
            <li><a href="<?php echo U($m.'/admin') ?>?action=edit&id=<?php echo session('admin_nid') ?>" target="main">你好，<?php echo $_SESSION['admin_username'] ?>！</a></li>
            <li><a href="<?php echo U($m.'/admin') ?>">系统首页</a></li>
            <li><a href="<?php echo BASEURL; ?>" target="_blank">网站首页</a> </li>
         
            <li role="separator" class="divider"></li>
            <li><a href='<?php echo U($m.'/index') ?>?action=out' class="out"><span class="glyphicon glyphicon-off" aria-hidden="true" style="font-size:18px; vertical-align:middle"></span>退出</a></li>
          </ul>
        </div>
       
       </div>

         <div class="tm">
        <a href="<?php echo U($m.'/admin') ?>"><span><img src="<?php echo $skins_admin ?>/logo_ui5_black.png" style="float:left;"></span></a>
    	<ul>

			<?php Tag::var_protect("IN"); if(is_array($group)) foreach($group as $k=> $v) { ?>

             <?php if($v['show']==1) { ?>
        	<li class="nav <?php if($k==0) { ?> curr<?php } ?>">
			<a href="javascript:;" onClick="MenuChange(this,'<?php echo $k ?>')"><span class="top_icon glyphicon <?php echo $v['icon'] ?>" aria-hidden="true"></span><?php echo $v['name'] ?></a></li>
            <?php } ?>
			<?php };  Tag::var_protect("OUT"); ?>
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
            <?php Tag::var_protect("IN"); if(is_array($group)) foreach($group as $k=> $v) { ?>
               <ul class="show<?php echo $k ?> <?php if($v['show']==1 && $k==0) { ?> on<?php } ?>">
                <li class="menulink" rel="group0" >
           <a href="javascript:;"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>所有栏目</a>
         </li>


					<?php Tag::var_protect("IN"); $index=0; if(is_array($category)) foreach($category as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>
						<?php if($menugroup==$v['name']) { ?>
							<li class="menulink" rel="group<?php echo $k ?>" data-cid="<?php echo $id ?>" <?php if($k==0 && $index==1) { ?> style="margin-top:0px"<?php } ?>  data-sons="<?php echo implode(",",get_sonids($id)) ?>" data-allsons="<?php echo implode(",",get_all_sonids_admin($id)) ?>"><a href="<?php echo U($m.'/admin') ?>?action=sub&catid=<?php echo $id ?>" target="main"><span class="glyphicon <?php if($icon) { ?><?php echo $icon ?><?php } else { ?>glyphicon-asterisk<?php } ?>" aria-hidden="true"></span><?php echo $catname ?></a>
                            <ul>
                            <?php getAllsonByDiGui($id,0,0);?>
                            </ul>
                            </li>
						<?php } ?>
					<?php };  Tag::var_protect("OUT"); ?>

                    </ul>
            <?php };  Tag::var_protect("OUT"); ?>

			<!--// 信息管理员 修改密码 -->


			
            <ul class="show-debug">
              <li class="menulink" rel="group0" >
           <a href="javascript:;"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>开发模式</a>
         </li>
			<li class="menulink" rel="debug"><a href="<?php echo U($m.'/dev') ?>" target="main"><span class="glyphicon glyphicon-th-list"></span>栏目设置</a></li>
           <?php if($debug) { ?>
            <li class="menulink" rel="debug"><a href="<?php echo U($m.'/dev') ?>?action=group" target="main"><span class="glyphicon glyphicon-magnet"></span>菜单分组</a></li>
           <?php } ?>
            </ul>
			
		</ul>
	</div>
	<div class="right"><iframe name="main" id="main" frameborder="0" src="admin?action=homepage" scrolling="auto"></iframe></div>
</div>
<div class="message"></div>
<div class="loading" style="padding-left:120px;top:200px;">
<div class="opacity"><img src="<?php echo $skins_admin ?>/loading.gif" width="32" height="32"></div></div>
</body>
</html>
