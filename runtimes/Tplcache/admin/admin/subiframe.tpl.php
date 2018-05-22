<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>子框架</title>
<link href="<?php echo $skins_admin ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $skins_admin ?>/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $skins_admin ?>/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/base.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/layer/layer.js"></script>
<script type="text/javascript">
var catidcurent = '<?php echo $catid ?>';
$(function(){
	if($(".subframenav li[rel!=hide]").length>0)  $(".subframenav li[rel=hide]").remove();
	if($(".subframenav .current").length==0) {
		$(".subframenav li").eq(0).addClass("current");
	    $(".subframenav li").eq(0).find('a').addClass('btnGreen');
	}else{
	   $(".subframenav .current a").addClass('btnGreen');
	}
    loadIframe();
})
function loadIframe(url){
   	var homepage = url?url:$(".subframenav .current a").first().attr("data-url");
	$("#subiframe").attr("src",homepage);		
}

function layIframe(aobj){

	if(!$(aobj).parent().hasClass('current')){
		
		var url = $(aobj).data('url');
		loadIframe(url);
		$("#category .label").click();
		if($('#category').length == 1){
			var html =$(aobj).html();
			$('#category .text').html(html);
		}
	}else{
	    $('.loading').hide();	
	}
}
</script>
</head>

<body>
<div class="subframenav">
  <div class="panel panel-warning" id="pane_panel_warning">
   <div class="panel-heading  bg-gray-lighter">
    <div class="left_sive" style=" line-height:30px; float:left;"><?php if(count($cat)>=5) { ?>当前栏目<?php } else { ?>子栏目<?php } ?></div>
    <?php if(count($cat)>=5) { ?> <div id="category" class="dropmenu">
    <div class="label plr20"><i class="down glyphicon glyphicon-chevron-down transform" id="icon_fuck"></i><div class="text"><span class="glyphicon <?php echo $categorys[$catid]['icon']?$categorys[$catid]['icon']:'glyphicon-map-marker' ?>" aria-hidden="true" style="float:none"></span><?php echo $categorys[$catid]['catname'] ?></div></div>
  
    <?php } ?>
    <ul style="float:left" class="sive_sive transform" data-height="<?php echo (count($cat)+1)*30 ?>">
      <div class="ulp20">
    <?php Tag::var_protect("IN"); $index=0; if(is_array($cat)) foreach($cat as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>
        <?php if($menugroup!=-1) { ?>
            <?php if($cattype=='article') { ?>
                <li  class="<?php if($catid==$id) { ?>current<?php } ?>" ><a href="javascript:void(0);" target="subiframe" onclick="layIframe(this);return false;" data-url="<?php echo U($m.'/article') ?>?catid=<?php echo $id ?>">
<span class="glyphicon <?php echo $icon?$icon:'glyphicon-map-marker' ?>" aria-hidden="true" style="float:none"></span><?php echo $catname ?></a></li>
            <?php } elseif ($cattype=='page') { ?>
              
                <li <?php if(empty($title) && empty($thumb) && empty($keywords) && empty($description) && empty($content) && empty($fields)) { ?>rel="hide"<?php } ?>  class="<?php if($catid==$id) { ?>current<?php } ?>"><a href="javascript:void(0);" target="subiframe" onclick="layIframe(this);return false;" data-url="<?php echo U($m.'/page') ?>?id=<?php echo $id ?>"><span class="glyphicon <?php echo $icon?$icon:'glyphicon-map-marker' ?>" aria-hidden="true" style="float:none"></span><?php echo $catname ?></a></li>
                
            <?php } else { ?>
                <?php $phpscript=explode('|',$phpscript); $phpscript = $phpscript[0]; ?>
                <li <?php if($phpscript=='') { ?>rel=""<?php } ?>  class="<?php if($catid==$id) { ?>current<?php } ?>"><a href="javascript:void(0);" onclick="layIframe(this);return false;" target="subiframe"  data-url="<?php echo $phpscript ?>"><span class="glyphicon <?php echo $icon?$icon:'glyphicon-map-marker' ?>" aria-hidden="true" style="float:none"></span><?php echo $catname ?></a></li>
            <?php } ?>
           
        <?php } ?>
    <?php };  Tag::var_protect("OUT"); ?> 
     <li style="display:none"><a href="javascript:void(0);" data-last="1" onclick="<?php if($r['parentid']>0) { ?>top.main.location.href='<?php echo U($m.'/admin') ?>?action=sub&catid=<?php echo $r['parentid'] ?>';<?php } else { ?> layer.alert('已经是最顶级了！',{title:'提示',icon:'1'});setTimeout(function(){$('.loading').hide();$(this).removeClass('btnGreen');},500);<?php } ?>" target="subiframe"><span class="glyphicon glyphicon-share-alt" aria-hidden="true" style="float:none"></span>返回上级</a></li>
     <div class="clear"></div>
     </div>
     <div class="clear"></div>
    </ul>
       <?php if(count($cat)>=10) { ?></div><?php } ?>
    
    <div class="clear"></div>
    </div>
   
  <div class="panel-body">
  <div class="subiframe">
	<iframe name="subiframe" id="subiframe" frameborder="0" src="about:blank"></iframe>
  </div>
  <div class="clear"></div>
</div>
<div class="clear"></div>
</div>



<div class="loading" style="top:100px;">
   <div class="opacity">
    <img src="<?php echo $skins_admin ?>/loading.gif" width="32" height="32">
    </div>
</div>
</body>
</html>

<style>
#category {
    min-height: 40px;
    margin-bottom: 5px;
    position: relative;
	padding-left:200px;
	padding-top:15px;
	cursor:pointer
}

#category ul {
    height: 0;
    overflow: hidden;
    position: absolute;
    z-index: 1;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    -webkit-box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
	background:#fff;
	width:100%
}
#category ul .ulp20{ padding:20px}	
.transform {
    transition: all 0.4s cubic-bezier(0.4, 0.01, 0.165, 0.99);
    -webkit-transition: all 0.4s cubic-bezier(0.4, 0.01, 0.165, 0.99);
}
#category .label {
    line-height: 40px;
    height: 40px;
    color: #333;
    font-size: 2rem;
	display:block;
	position:relative
}

.plr20 {
    padding: 0 20px;
}

.dropmenu.open .label .down {
    transform: rotate(180deg);
}

.dropmenu .label .down {
	position:absolute;
    font-size: 2.4rem;
    margin-top: 8px;
	left:50%;
	width:24px;
	top:29px;
	margin-left:-6px;
}

.dropmenu ul li {
    min-width:100px;
	margin:10px;
	display:inline-block;
	margin-bottom:0;
	height:auto;
	line-height:normal
}
.dropmenu ul li a{
    padding:10px;
    color: #969595;
    display: block;
    background: #fff;
    font-size: 1.3rem;
	height:auto;
	text-align:center;
	max-width: 104px;
    max-height: 96px;
	text-overflow:ellipsis;
	overflow:hidden;
	white-space:nowrap;
}
.dropmenu ul li a:hover{
    background-color: #ff5b45;
    color: #FFF;
    border-radius: 3px;
}
.dropmenu ul li span{
	display:block; width:40px; height:40px; font-size:40px;
	margin:0 auto
}
.loading .opacity{ left:0}
</style>
<script>
$(function(){
   $("#category .label").click(function(){
	   var h = $("#category").find('ul').data('height') ;
	   if($("#category").hasClass('open')){
		   $("#category").removeClass('open');
		   $("#category").find('ul').height(0);
	  }else{
		 $("#category").addClass('open');
		 //$("#category").find('ul').height(h);
		 initCW();
	  }
	  
   });	
   
   $(window).resize(function(){
	   initCW();
   });
})

function initCW() {
   var ulW = $('#category').width();
   $("#category").find('ul').width(ulW);
   setTimeout(function(){
	   var ulp20H = $('.ulp20').height()+50;	
  	   $("#category").find('ul').height(ulp20H)
   },200);
   
}

</script>