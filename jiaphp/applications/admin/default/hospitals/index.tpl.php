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
</head>

<body>
<!--if empty($action) -->

<div class="searchbar" style="margin-left:20px;">
    <form method="post" class="form-wrap" style="padding-top:0; margin-bottom:10px">

        名称：<input name="keyword" type="text" class="form-control" style="width:180px; display:inline" value="{$keyword}"> &nbsp;&nbsp;
        类型：<select name="ptype" type="text" class="form-control" style="width:180px; display:inline">
            <option value="">全部</option>
            <option value="hospital" {if $ptype=='hospital'} selected {/if}>医院产科</option>
            <option value="maternity" {if $ptype=='maternity'} selected {/if}>月子中心</option>
            <option value="babyshop" {if $ptype=='babyshop'} selected {/if}>母婴商超</option>
        </select>&nbsp;&nbsp;
        <button name="sub" type="submit" class="btn btn-success"><span class="glyphicon glyphicon-ok">提交</span></button>
        <a href="?action=add" class="btn btn-success" style="color:#FFF"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加合作方</a>
    </form>

</div>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
    <tr>
        <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px;box-shadow:none"></th>
        <th width="10%">LOGO</th>
        <th width="5%">合作方名称</th>
        <th width="10%">类型</th>
        <th width="10%" align="left">排序</th>
        <th width="10%" align="left">状态</th>
        <th width="12%">操作</th>
    </tr>
    <!--foreach $list $r -->
    <tr><td width="5%" align="center"><input type="checkbox" name="id" class="idars form-control" value="{$r.id}"></td>
        <td width="10%" align="center"><img src=" {getThumb($r.logo)}" width="100" height="100" style="border-radius: 100%"/></td>
        <td width="5%" align="center">{$r.name}</td>
        <td width="5%" align="center">{eval echo $partNers[$r['ptype']]}</td>
        <td width="5%" align="center"><input type="text" class="form-control weight" data-pid="{$r.id}" style="width:100px" value="{$r.weight}"/></td>
        <td width="5%" align="center">{if $r.status == 1}启用{else}禁用{/if}</td>
        <td width="12%" align="center">
            <div class="btn-group" role="group">
                <a href="?action=edit&id={$r.id}&page={$gpcpage}"  class="btn btn-success"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>修改</a>
                <a href="?action=del&id={$r.id}&page={$gpcpage}"   class="btn btn-danger hp-del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</a>
                <a href="{U($m.'/partinfo')}?id={$r.id}"   class="btn btn-info"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>科室/楼层</a>
            </div>
        </td>
    </tr>
    <!--/foreach -->
    <tr>

        <td colspan="8">
            <div class="btn-group" role="group" aria-label="...">
                <button type="button" class="btn btn-info fanxuan" id="fanxuan">反选</button>
                <button type="button" class="btn btn-danger" onClick="delall('{U($m.'/lang')}?action=del','删除')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span>删除</button>
            </div>
        </td>
    </tr>
</table>
<div class="page_box "><ul class="pagination">{$page}</ul></div>
{elseif $action=='add' || $action=='edit'}

<form method="post" class="system">
    <table border="0" cellspacing="0" cellpadding="0" class="form">



        <tr>
            <td width="120">合作方名称：</td>
            <td><input name="data[name]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.name}{/if}"></td>
        </tr>
        <tr>
            <td width="120">代理商UID：</td>
            <td><input name="data[agentid]" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.agentid}{/if}"></td>
        </tr>
        <tr>
            <td width="120">合作方LOGO：</td>
            <td><input name="data[logo]" id="logo" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.logo}{/if}" _type="file"></td>
        </tr>
        <tr>
            <td width="120">合作方类型：</td>
            <td><select name="data[ptype]" type="text" class="form-control">
                    <option value="hospital" {if $action=='edit' && $r.ptype=='hospital'} selected {/if}>医院产科</option>
                    <option value="maternity" {if $action=='edit' && $r.ptype=='maternity'} selected {/if}>月子中心</option>
                    <option value="babyshop" {if $action=='edit' && $r.ptype=='babyshop'} selected {/if}>母婴商超</option>
                </select></td>

        </tr>

        <tr>
            <td width="120">合作方介绍：</td>
            <td><textarea name="data[content]" type="text" class="form-control"  style="width:100%;" id="content" _type="editor">{if $action=='edit'}{$r.content}{/if}</textarea></td>

        </tr>
        <tr>
            <td width="120">排序：</td>
            <td><input name="data[weight]" id="weight" type="text" class="form-control"  style="width:400px;" value="{if $action=='edit'}{$r.weight}{/if}"></td>
        </tr>
        <tr>
            <td width="120">是否禁用：</td>
            <td><input name="data[status]" type="radio" value="1" {if  $action=='add'|| ($action=='edit' && $r.status==1)} checked{/if}>启用
                <input name="data[status]" type="radio" value="0"  {if ($action=='edit' && $r.status==0)} checked{/if}>禁用

        </tr>

        <tr>
            <td></td>
            <td><input type="hidden" name="id" value="{if $action=='edit'}{$r.id}{/if}">
                <input type="hidden" name="page" value="{$gpcpage}">
                <input type="hidden" name="lang" value="{LANG}">
                <input type="hidden" name="session_id" value="{session_id()}">
                <input name="submit" type="submit" value="提交" class="btn btn-success">
                <input name="returnback" type="button" onClick="history.go(-1);" value="返回上一页" class="btn btn-default">
            </td>

        </tr>

    </table>
</form>
{/if}
<script type="text/javascript" src="{$skins_admin}/base.js"></script>
<script>
    $(function() {
        $('.weight').on('blur',function () {
            var value = $(this).val();
            var pid   = $(this).data('pid');
            $.post("{U($m.'/'.$c)}?action=saveWeight",{
                pid:pid,value:value
            },function (res) {
                window.top.message(res.msg,1000);
                setTimeout(function () {
                    location.reload();
                },1000)
            },'json');
        });
    })
</script>
</body>
</html>