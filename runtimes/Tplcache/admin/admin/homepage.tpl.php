<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>美藤科技内容管理系统</title>
<link href="<?php echo $skins_admin ?>/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo $skins_admin ?>/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $skins_admin ?>/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/base.js"></script>
<script type="text/javascript" src="<?php echo $skins_admin ?>/layer/layer.js"></script>
<script type="text/javascript">
$(function(){
	if($.browser.msie && $.browser.version=="6.0"){
		document.execCommand('BackgroundImageCache', false, true);
		$('body').css('backgroundAttachment') !== 'fixed' && $('html').css({
			backgroundImage: 'url(about:blank)',
			backgroundAttachment: 'fixed'
		});
		$("*").each(function(){
			if($(this).css("position")=="fixed"){
				var left = parseInt(this.style.left) - document.documentElement.scrollLeft,
					top = parseInt(this.style.top) - document.documentElement.scrollTop;
				this.style.position = 'absolute';
				this.style.removeExpression('left');
				this.style.removeExpression('top');
				this.style.setExpression('left', 'eval((document.documentElement).scrollLeft + ' + left + ') + "px"');
				this.style.setExpression('top', 'eval((document.documentElement).scrollTop + ' + top + ') + "px"');
			}
		})
	}
	//$(".ext_func").slideDown();
	$(".ext_bottom").click(function(){ 
		$(".ext_func").slideToggle();$(this).toggleClass("cur"); 
	}).hover(function(){
		$(this).toggleClass("cur");
	},function(){
		$(this).toggleClass("cur");
	});
	$(".close").click(function(){
	    $(this).parent().fadeOut();	
	});
})
function closeDebug() {
	 var anims = parseInt(Math.random()*10);
	 layer.confirm("您真的要关闭吗？", {
	  btn: ['确定','取消'] //按钮
	  ,title:'提示'
	  ,icon:2
	  ,shade: [0.6, '#fff']
	  ,shift:anims
	  ,anim:parseInt(Math.random()*10)
	}, function(){
		var index = layer.load(0, {
		  shade: [0.1,'#fff'] //0.1透明度的白色背景
		});
		

		 $.post("<?php echo U($m.'/setting') ?>?action=save",{data:{debug:'否'},ajax:1},
		 function(data){
			 window.top.message(data.msg,500);
			 setTimeout(function(){window.location.reload();},1000);
		 },"json");
	});
}
</script>
<style type="text/css">
.ext {
	position: fixed;
	right: 0px;
	width: 200px;
}
.ext_title {
	height: 25px;
	padding: 6px 0 7px;
	background: url(<?php echo $skins_admin ?>/ext_tbg.jpg) repeat-x;
	text-align: center;
	border-left: solid 1px #dbdbdb;
}
.ext_func {
	border-left: solid 1px #dbdbdb;
	display: none;
	background: #f4f4f4
}
.ext_func li {
	height: 60px;
	line-height: 60px;
}
.ext_func li a.ico1 {
	background: url(<?php echo $skins_admin ?>/ext_ico.jpg) no-repeat 10px 0px;
}
.ext_func li a.ico1:hover, .ext_func li.cur a.ico1 {
	background-position: 10px -300px;
	background-color: #FFF;
	color: #69F;
}
.ext_func li a.ico2 {
	background: url(<?php echo $skins_admin ?>/ext_ico.jpg) no-repeat 10px -60px;
}
.ext_func li a.ico2:hover, .ext_func li.cur a.ico2 {
	background-position: 10px -360px;
	background-color: #FFF;
	color: #69F;
}
.ext_func li a.ico3 {
	background: url(<?php echo $skins_admin ?>/ext_ico.jpg) no-repeat 10px -120px;
}
.ext_func li a.ico3:hover, .ext_func li.cur a.ico3 {
	background-position: 10px -420px;
	background-color: #FFF;
	color: #69F;
}
.ext_func li a.ico4 {
	background: url(<?php echo $skins_admin ?>/ext_ico.jpg) no-repeat 10px -180px;
}
.ext_func li a.ico4:hover, .ext_func li.cur a.ico4 {
	background-position: 10px -480px;
	background-color: #FFF;
	color: #69F;
}
.ext_func li a.ico5 {
	background: url(<?php echo $skins_admin ?>/ext_ico.jpg) no-repeat 10px -240px;
}
.ext_func li a.ico5:hover, .ext_func li.cur a.ico5 {
	background-position: 10px -540px;
	background-color: #FFF;
	color: #69F;
}
.ext_func li a {
	height: 60px;
	line-height: 60px;
	display: block;
	padding-left: 80px;
	color: #666;
	font-size: 14px;
	font-family: Microsoft Yahei;
}
.ext_bottom {
	height: 18px;
	background: url(<?php echo $skins_admin ?>/ext_bottom.jpg) no-repeat center center #dbdbdb;
	cursor: pointer;
}
.ext_bottom.cur {
	background: url(<?php echo $skins_admin ?>/ext_bottom_cur.jpg) no-repeat center center #CACACA;
}
.form {
	float: left
}
.help_btn {
	border: 1px solid #dbdbdb
}
.dashboard-stat.blue {
    background-color: #3598dc;
}
.dashboard-stat.red {
    background-color: #e7505a;
}
.dashboard-stat.purple {
	background-color: #8E44AD;
}

.dashboard-stat.green {
	background-color: #32c5d2;
}

.dashboard-stat.yellow {
	background-color: #ffb848;
}

.dashboard-stat.meatpink {
	background-color: #ed6b75;
}
.dashboard-stat {
    display: block;
    margin-bottom: 25px;
    overflow: hidden;
    border-radius: 4px;
}
.dashboard-stat .visual {
    width: 80px;
    height: 80px;
    display: block;
    float: left;
    padding-top: 10px;
    padding-left: 15px;
    margin-bottom: 15px;
    font-size: 35px;
    line-height: 35px;
}
.dashboard-stat .details {
    position: absolute;
    right: 15px;
    padding-right: 15px;
    color: #fff;
}
.dashboard-stat .more {
    clear: both;
    display: block;
    padding: 6px 10px;
    position: relative;
    text-transform: uppercase;
    font-weight: 300;
    font-size: 11px;
    opacity: .7;
    filter: alpha(opacity=70);
    color: #FFF;
    background-color: rgba(0, 0, 0, 0.1);
}
.dashboard-stat .details .number {
    padding-top: 25px;
    text-align: right;
    font-size: 34px;
    line-height: 36px;
    letter-spacing: -1px;
    margin-bottom: 0;
    font-weight: 300;
}
.panel-body-297px {
    height: 383px;
}
.dashboard-stat .visual>i {
    color: #FFF;
    opacity: .2;
    filter: alpha(opacity=10);
    margin-left: -40px;
    font-size: 110px;
    line-height: 110px;
}
</style>
</head>
<body style="background:#f7f7f7 url(<?php echo $skins_admin ?>/body-bg.png) repeat;    padding: 25px 20px 10px;">
<div class="row">
  <div class="col-lg-12">
    <ol class="breadcrumb">
      <li> <i class="glyphicon glyphicon-home"></i> <a href="<?php echo U($m.'/admin') ?>?action=homepage">JIACMS后台</a></li>
      <li><a href="<?php echo BASEURL; ?>/" target="_blank">首页</a></li>
    </ol>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
  
     <div class="alert alert-info alert-dismissable">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <?php echo $_SESSION['admin_username'] ?>你好，欢迎来到美藤科技后台管理系统！
      <?php $r = $db->getfield("select * from admin where password='e10adc3949ba59abbe56e057f20f883e'"); ?>
      <?php if($debug || $r) { ?>
        <?php if($debug) { ?>
当前系统为debug模式，网站上线时请关闭调试模式！<a class="btn btn-info" href="javascript:;" onClick="closeDebug()">关闭调试模式</a>
<?php } ?>
 <?php if($r) { ?>
        您的原始密码尚未修改，为了保障系统安全，请您立即修改密码！<a class="btn btn-success" href="<?php echo U($m.'/admin') ?>?action=edit&id=<?php echo session('admin_nid') ?>" target="main">修改密码</a>
        <?php } ?> 
   
     
     <?php } ?>
     </div>  
     
  </div>
</div>
<?php
//数据统计
$cats_nums = $db->getfield("select count(id) as total from category where lang='".LANG."' and id>0 ");
$arc_nums = $db->getfield("select count(id) as total from article  ");
$ly_nums = $db->getfield("select count(id) as total from guestbook where pid=0  ");
?>

<div class="row">
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="<?php echo U($m.'/dev') ?>">
    <div class="dashboard-stat blue">
      <div class="visual"> <i class="glyphicon glyphicon-th"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="1349"><?php echo $cats_nums ?></span> </div>
        <div class="desc"> 栏目</div>
      </div>
      <span class="more"> 查看更多 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="javascript:;">
    <div class="dashboard-stat red">
      <div class="visual"> <i class="glyphicon glyphicon-list-alt"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="12,5"><?php echo $arc_nums ?></span> </div>
        <div class="desc"> 文章 </div>
      </div>
      <span class="more"> 查看文章 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="<?php echo U($m.'/lang') ?>">
    <div class="dashboard-stat green" >
      <div class="visual"> <i class="glyphicon glyphicon-leaf"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="549"><?php echo count($langs) ?></span> </div>
        <div class="desc"> 语言版本 </div>
      </div>
      <span class="more"> 查看语言 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="<?php echo U($m.'/datalist') ?>?table=guestbook">
    <div class="dashboard-stat meatpink">
      <div class="visual"> <i class="glyphicon glyphicon-play-circle"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="89"><?php echo $ly_nums ?></span> </div>
        <div class="desc"> 留言 </div>
      </div>
      <span class="more"> 查看更多 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="http://www.huimingcn.com/" target="_blank">
    <div class="dashboard-stat purple">
      <div class="visual"> <i class="glyphicon glyphicon-camera"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="89">1.5亿</span> </div>
        <div class="desc"> 智能AI </div>
      </div>
      <span class="more"> 查看更多 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
  <div class="col-lg-2 col-md-2 col-sm-6 col-xs-6"> <a class="nolink" href="http://www.huimingcn.com/" target="_blank">
    <div class="dashboard-stat yellow">
      <div class="visual"> <i class="glyphicon glyphicon-tint"></i> </div>
      <div class="details">
        <div class="number"> <span data-counter="counterup" data-value="89">藤系设计</span> </div>
        <div class="desc"> JIASHOP美藤商城 </div>
      </div>
      <span class="more"> 查看更多 <i class="fa fa-arrow-circle-right pull-right"></i> </span> </div>
    </a> </div>
</div>
<div class="row">
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-user fa-fw"></i> WLCMS配置</h3>
      </div>
      <div class="panel-body panel-body-297px">
        <ul class="list-group">
          <li class="list-group-item">HI ，<span style="color: #cc0000;"><?php echo $_SESSION['admin_username'] ?></span> ，欢迎您登陆美藤科技后台管理系统。 </li>
          <li class="list-group-item">用户ID：<?php echo $_SESSION['admin_username'] ?></li>
          <li class="list-group-item">用户级别：管理员<span class="pull-right"><a href="<?php echo U($m.'/admin') ?>?action=edit&id=<?php echo session('admin_nid') ?>" class="more"><span>修改信息</span><i class="fa fa-arrow-circle-right"></i></a></span></li>
          <li class="list-group-item"> 当前密码 　　[<?php if($r) { ?>原始密码<?php } else { ?>好密码<?php } ?>] <a href="<?php echo U($m.'/admin') ?>?action=edit&id=<?php echo session('admin_nid') ?>" class="recharge more pull-right"> <span>修改密码</span><i class="fa fa-arrow-circle-right pull-right"></i> </a> </li>
          <li class="list-group-item">主程开发： 　　[<a href="http://www.jiacms.com" target="_blank">美藤CMS团队</a>]</li>
          <li class="list-group-item">当前版本 　　[<a href="http://cms.jiacms.com" target="_blank"><?php echo VERSION; ?></a>]<span class="pull-right"><a href="http://cms.jiacms.com" class="more" target="_blank"><span>查看更多</span><i class="fa fa-arrow-circle-right"></i></a></span></li>
          
           <li class="list-group-item">美藤模板 　　[<a href="http://mb.jiacms.com" target="_blank">极客建站</a>]<span class="pull-right"><a href="http://mb.jiacms.com" class="more" target="_blank"><span>查看更多</span><i class="fa fa-arrow-circle-right"></i></a></span></li>
           
            <li class="list-group-item">美藤官网 　　[<a href="http://www.jiacms.com" target="_blank">http://www.jiacms.com</a>]<span class="pull-right"><a href="http://www.jiacms.com" class="more" target="_blank"><span>查看更多</span><i class="fa fa-arrow-circle-right"></i></a></span></li>
             <li class="list-group-item">美藤商城 　　[<a href="http://cms.jiacms.com" target="_blank">微信、PC、手机三合一</a>]<span class="pull-right"><a href="http://cms.jiacms.com" class="more" target="_blank"><span>查看更多</span><i class="fa fa-arrow-circle-right"></i></a></span></li>
          
          
        </ul>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-clock-o fa-fw"></i> 服务器资料</h3>
      </div>
      <div class="panel-body panel-body-297px">
        <div class="list-group"> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo PHP_OS ?></span> <i class="iconfont icon-dingdan"></i> 服务器操作系统 </a> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo $_SERVER['SERVER_SOFTWARE'] ?></span> <i class="iconfont icon-dingdan"></i> 服务器软件 </a> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo PHP_VERSION ?></span> <i class="iconfont icon-chanpinlinian"></i> PHP版本 </a> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php if(function_exists("gd_info")){
				$gd = gd_info();
				echo $gd["GD Version"];
			}else{
				echo"<span style='color:red'>Unknow</span>";
			} ?></span> <i class="iconfont icon-chanpinlinian"></i> GD版本 </a> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo ini_get("max_execution_time")." 秒"; ?></span> <i class="iconfont icon-jizhang"></i> 最大执行时间 </a> <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo ini_get("post_max_size") ?></span> <i class="iconfont icon-youxiang"></i> 最大POST数据 </a>
             <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo @zend_version(); ?></span> <i class="iconfont icon-youxiang"></i> zend 版本 </a>
             
             <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo get_cfg_var("memory_limit") ? get_cfg_var("memory_limit") : "无"; ?></span> <i class="iconfont icon-youxiang"></i> 允许内存大小 </a>
             
              <a href="javascript:;" class="list-group-item"> <span class="badge"><?php echo date("Y-m-d H:i:s"); ?></span> <i class="iconfont icon-youxiang"></i> 服务器时间 </a>
             
              </div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-users fa-fw"></i>美藤动态</h3>
      </div>
      <div class="panel-body-297px">
      

      
       </div>
    </div>
  </div>
</div>

<!--未付款订单-->
<div class="row">
  <div class="col-lg-12">
    <div class="panel panel-default responsive-tables">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list-ul fa-fw"></i>最新文章<a class="btn btn-circle btn-default pull-right btn-circle-more" href="javascript:;"  style="padding:0 5px; border:0"><i class="glyphicon glyphicon-share-alt"></i></a></h3>
      </div>
      <div class="panel-body">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>编号</th>
              <th>缩略图</th>
              <th>栏目</th>
              <th>名称</th>
              <th>小类型</th>
              <th>点击</th>
              <th>添加时间</th>
              <th>是否推荐</th>
             
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
      <?php $articles = $db->t('article')->orderby('createtime')->limit(10)->all() ?>
      <?php if($articles) { ?>
      <?php Tag::var_protect("IN"); $index=0; if(is_array($articles)) foreach($articles as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>
        <tr>
        <td><?php echo $id ?></td>
        <td><?php if($thumb) { ?><img src="<?php echo BASEURL; ?>/<?php echo $thumb ?>" title="<?php echo $title ?>" alt="<?php echo $title ?>" width="50" height="27"><?php } else { ?>暂无图片<?php } ?></td>
        <td><?php echo $categorys[$catid]['catname'] ?></td>
        <td><?php echo $title ?></td>
        <td><?php echo isset($categorys[$catid]['subtype'][$r['type']])?$categorys[$catid]['subtype'][$r['type']]['typename']:''; ?></td>
        <td><?php echo $click ?></td>
        <td><?php echo date("Y-m-d H:i:s",$createtime) ?></td>
        <td><?php if(strpos($flag,'c')) { ?>推荐<?php } else { ?>无<?php } ?></td>
        <td><a href="<?php echo U($m.'/article') ?>?action=edit&id=<?php echo $id ?>&catid=<?php echo $catid ?>">修改</a>|<a href="<?php echo U_aid($id,$catid) ?>" target="_blank">浏览</a></td>
        </tr>
       <?php };  Tag::var_protect("OUT"); ?>
    <?php } else { ?>
            <tr>
              <td align="center" colspan="9" style="color: red;">暂无记录</td>
            </tr>
        <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--订单结束--> 



<div style="margin:10px 30px 0px; padding-bottom:20px;">深圳美藤科技有限公司（<a href="http://www.ziyouteng.com" target="_blank">www.ziyouteng.com</a>）版权所有 &copy; 2014-<?php echo date("Y") ?></div>
<!-- 右侧扩展功能 -->
<div class="ext" style="top:0px;">
  <div class="ext_title"><img src="<?php echo $skins_admin ?>/ext_t.jpg" width="88" height="25"></div>
  <div class="ext_func">
    <ul>
      <li><a class="ico1" href="http://www.ziyouteng.com/services.html" target="_blank">网站推广</a></li>
      <li class="cur"><a class="ico2" href="https://web.umeng.com/main.php?c=user&a=login" target="_blank">流量统计</a></li>
      <li><a class="ico3" href="javascript:;">在线客服</a></li>
      <li><a class="ico4" href="javascript:;">手机网站</a></li>
      <li><a class="ico5" href="http://www.ziyouteng.com" target="_blank">网站续费</a></li>
      <li><a class="ico5" href="http://www.kancloud.cn/wlcms/doc/297950" target="_blank">操作指示</a></li>
    </ul>
  </div>
  <div class="ext_bottom"></div>
</div>
</body>
</html>
