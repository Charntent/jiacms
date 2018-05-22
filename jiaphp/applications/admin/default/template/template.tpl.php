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
<script type="text/javascript" src="{$skins_admin}/base.js"></script>

<script type="text/javascript" charset="utf-8" src="{$skins_index}/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="{$skins_index}/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript" src="{$skins_index}/ueditor/lang/zh-cn/zh-cn.js"></script>



<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>

</head>
<style>
.temp p{ height:50px;}
.temp input{ margin-left:10px;}
</style>
<body>
<form  action="{U($m.'/template')}?action=save" method="post" class="system">
	<table width="100%" border="1" cellspacing="0" cellpadding="0" class="form tr_line">
	
	<!--foreach $langs $r -->
		<tr>
        
        <td width="20%" align="center">
        {$r.langname}版
        </td>
        
         <td align="center" class="temp">
          <p>PC网站域名<input type="text" class="form-control" name="data[pcurl][{$r.langid}]" style="width:400px; display:inline" value="{if isset($values['pcurl'][$r['langid']])}{eval echo $values['pcurl'][$r['langid']];}{/if}"></p>
           <p>PC网站模板<input type="text" class="form-control" name="data[pctemp][{$r.langid}]" style="width:400px; display:inline"  value="{if isset($values['pcurl'][$r['langid']])}{eval echo $values['pctemp'][$r['langid']];}{/if}"></p>
            <p>手机网站域名<input type="text" class="form-control" name="data[mburl][{$r.langid}]" style="width:400px; display:inline"  value="{if isset($values['pcurl'][$r['langid']])}{eval echo $values['mburl'][$r['langid']];}{/if}"></p>
             <p>手机网站模板<input type="text" class="form-control" name="data[mbtemp][{$r.langid}]" style="width:400px; display:inline"  value="{if isset($values['pcurl'][$r['langid']])}{eval echo $values['mbtemp'][$r['langid']];}{/if}"></p>
        </td>
			
		</tr>
	<!--/foreach -->
            <tr>
         
      <td colspan="8">
 <input name="submit" type="submit" value="提交" class="btn">
      </td>   
          </tr>
	</table>
    </form>
</body>
</html>