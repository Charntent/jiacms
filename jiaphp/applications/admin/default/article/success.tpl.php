<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>慧名科技</title>
<link href="{$skins_admin}/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="{$skins_admin}/base.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_admin}/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>


</head>
<body>
<div style="margin-left:50px; margin-top:30px">
<div class="alert alert-success" role="alert">{$msg}
</div>
   <div class="alert alert-info" role="alert">  　　请选择你的后续操作：
    <a href="{U($m.'/article')}?action=add&catid={$catid}"><u>继续发布文章</u></a>
    &nbsp;&nbsp;
    <a href="{U_aid($id,$catid)}" target="_blank"><u>查看文章</u></a>
    &nbsp;&nbsp;
    <a href="{U($m.'/article')}?action=edit&catid={$catid}&id={$id}&rcatid={$rcatid}"><u>更改文章</u></a>
    &nbsp;&nbsp;
    <a href="{U($m.'/article')}?catid={$rcatid}&page={$page}"><u>已发布文章管理</u></a>
   
  </div>
    
</div>
</body>
</html>
