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

<div class="searchbar" style="margin-left:20px; padding-right: 10px">
    <span style="float: left">{$r.name}-{$typeName}</span>
    <a href="?action=add&partid={$id}" class="btn btn-success" style="color:#FFF"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加{$typeName}</a>
</div>
<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
    <tr>
        <th width="5%"><input type="checkbox" name="checkall" id="checkall" class="form-control fanxuan" style="height:18px; border:0; margin-left:4px;box-shadow:none"></th>
        <th width="5%">ID</th>
        <th width="5%">{$typeName}</th>
        <th width="10%" align="left">排序</th>
        <th width="10%" align="left">状态</th>
        <th width="12%">操作</th>
    </tr>
    {eval echo menuget(0,0,$data)}
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
            <td width="120">父级菜单：</td>
            <td><select name="data[parent_id]" type="text" class="form-control">
                      <option value="0">顶级</option>
                      {foreach $list $k $v}
                      <option value="{$v.id}">{$v.name}</option>
                      {/foreach}
                </select>
            </td>

        </tr>

        <tr>
            <td width="120">{$typeName}名称：</td>
            <td><textarea name="data[allname]" type="text" class="form-control"  style="width:100%; height: 180px" >{if $action=='edit'}{$r.name}{/if}</textarea><p style="padding: 10px; color: #f30">批量添加时回车</p>
            </td>
        </tr>
        <tr>
            <td width="120">排序（越小越前面）：</td>
            <td><input name="data[weight]" type="text" class="form-control" value="{if $action=='edit'}{$r.weight}{else}100{/if}"></td>
        </tr>

        <tr>
            <td width="120">是否禁用：</td>
            <td><input name="data[status]" type="radio" value="1" {if ($action=='edit' && $r.status==1) || $action=='add'} checked{/if}>启用
                <input name="data[status]" type="radio" value="0"  {if  ($action=='edit' && $r.status==0)} checked{/if}>禁用

        </tr>

        <tr>
            <td></td>
            <td><input type="hidden" name="id" value="{if $action=='edit'}{$r.id}{/if}">
                <input type="hidden" name="page" value="{$gpcpage}">
                <input type="hidden" name="data[partid]" value="{$partid}">
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
            },1000);
        },'json');
    });
})
</script>
</body>
</html>