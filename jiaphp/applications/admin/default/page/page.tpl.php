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
<script type="text/javascript" src="{$skins_admin}/editor.js" id="cms_editor" use="{S('cms_editor')}" pb="{$skins_index}"></script>

<script type="text/javascript" src="{$skins_admin}/layer/layer.js"></script>
<link href="{$skins_index}/swfupload/swfupload.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="{$skins_index}/swfupload/swfupload.js"></script>
<script type="text/javascript" src="{$skins_index}/swfupload/handlers.js"></script>
<script>
function loading(){
	var title = $("input[name=title]").val();
	
	if(title != undefined && title==''){
		 $("#article_nav li").eq(0).click();
		layer.msg('请输入标题');
		$("input[name=title]").focus();
		return false;
	}
	
	layer.msg('不好！！也许有远程图片需要下载，卡住了，请耐心等待！');
}
</script>
</head>

<body>
<form method="post" class="form-wrap system" onSubmit="return loading()">
 <ul class="nav nav-tabs">
        <ol class="breadcrumb">
          <li><a href="{U($m.'/admin')}?action=homepage"  target="main">首页</a></li>
          <li><a href="{U($m.'/admin')}?action=sub&catid={$id}" target="main">{eval echo $categorys[$id]['catname']}</a></li>
          
        </ol>
    </ul>
  <ul class="nav nav-tabs" id="article_nav" style="margin-top:10px;margin-bottom:10px">
     <li id="nav_common" role="presentation" data-id="common" class="active"><a href="javascript:;">常规信息</a></li>
     <li id="nav_content" role="presentation" data-id="content"><a href="javascript:;">详细描述</a></li>
      <li id="nav_seo" role="presentation" data-id="seo"><a href="javascript:;">SEO描述</a></li>
    </ul>
    
<table border="0" cellspacing="0" cellpadding="0" class="form">
	<!--if $category.title -->
	<tr class="common seo">
		<td width="90">标题：</td>
		<td><input name="title" type="text" style="width:400px;" value="{$art.title}"  class="form-control" ></td>

	</tr>
	<!--/if -->
    <!--if $category.thumb -->
	<tr  class="common">
		<td>缩略图</td>
		<td><input name="thumb" type="text" _type="file" style="width:400px; float:left" value="{$art.thumb}"  class="form-control"  id="thumb"></td>
	</tr>
    <!--/if -->
    
              <!--if $category.thumb -->
		<tr  class="common">
			<td>缩略图alt</td>
			<td><input name="alt" type="text" style="width:400px; float:left" value="{$art.alt}" id="alt" class="form-control"></td>
		</tr>
		<!--/if -->
	<!--if $category.keywords -->
	<tr  class="seo hide">
		<td>关键词</td>
		<td><textarea name="keywords" id="myEditor" style="width:680px; height:48px; overflow:hidden"  class="form-control" >{$art.keywords}</textarea></td>

	</tr>
	<!--/if -->
	<!--if $category.description -->
	<tr  class="seo hide">
		<td>描述</td>
		<td><textarea name="description" id="myEditor" style="width:680px; height:80px;"  class="form-control" >{$art.description}</textarea></td>

	</tr>
	<!--/if -->
	<!--if $category.content -->
	<tr  class="content common">
		<td>内容</td>
		<td><textarea name="content" style="width:100%; height:500px " id="content" _type="editor">{$art.content}</textarea></td>

	</tr>
	<!--/if -->
	<!--if $category.fields -->
		<!--eval $_fields = explode("\\r\\n",$category.fields) -->
		<!--foreach $_fields $_fields_row-->
			<!--if !empty($_fields_row) || substr_count($_fields_row,'|')>=2-->
				<!--eval @list($title,$name,$type,$desc,$value,$default) = explode('|',$_fields_row) -->
					<!--eval $value = @$art[$name]; -->
					<!--if $type=='input' -->
					<tr  class="common">
						<td width="160">{$title}</td>
						<td><input name="data[{$name}]" type="text" style="width:400px;" value="{$value}"  class="form-control" > <font color="#999999">{$desc}</font></td>
					</tr>
					<!--/if -->
					
					<!--if $type=='textarea' -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td><textarea name="data[{$name}]" style="width:680px; height:80px;"  class="form-control" >{$value}</textarea> <font color="#999999">{$desc}</font></td>
					</tr>
					<!--/if -->
					
					<!--if $type=='select' -->
					<!--eval $select=explode(',',$default); -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td>
							<select name="data[{$name}]"  class="form-control" >
							<!--foreach $select $r -->
								<option value="{$r}" {if $value==$r}selected{/if}>{$r}</option>
							<!--/foreach -->
							</select>
							<font color="#999999">{$desc}</font>
						</td>
					</tr>
					<!--/if -->
					
					<!--if $type=='radio' -->
					<!--eval $radio=explode(',',$default); -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td>
							<!--foreach $radio $r -->
								<input name="data[{$name}]" type="radio" value="{$r}" {if $value==$r}checked{/if}> {$r}&nbsp;&nbsp;
							<!--/foreach -->
							<font color="#999999">{$desc}</font>
						</td>
					</tr>
					<!--/if -->
					
					<!--if $type=='checkbox' -->
					<!--eval $checkbox=explode(',',$default); -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td>
							<!--foreach $checkbox $r -->
								<input name="data[{$name}][]" type="checkbox" value="{$r}" {if in_array($r,(array)$value)}checked{/if}> {$r} &nbsp;&nbsp;
							<!--/foreach -->
							<font color="#999999">{$desc}</font>
						</td>
					</tr>
					<!--/if -->
					
					<!--if $type=='file' -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td><input name="data[{$name}]" type="text" _type="file" style="width:400px;"  class="form-control"  value="{$value}" id="{$name}"> <font color="#999999">{$desc}</font></td>
						
					</tr>
					<!--/if -->
					
					<!--if $type=='minieditor' -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td><textarea name="data[{$name}]" style="width:650px;" id="{$name}" _type="minieditor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
					</tr>
					<!--/if -->
					
					<!--if $type=='editor' -->
					<tr  class="common">
						<td width="160px">{$title}</td>
						<td><textarea name="data[{$name}]" style="width:780px;" id="{$name}" _type="editor">{$value}</textarea> <font color="#999999">{$desc}</font></td>
					</tr>
					<!--/if -->
				
			<!--/if -->
		<!--/foreach -->
	<!--/if -->
	
	
	<tr>
		<td></td>
		<td>
      <input type="hidden" name="lang" value="{LANG}">
             <input type="hidden" name="session_id" value="{session_id()}">
        <div class="wl_btn_submit"><input name="submit" type="submit" value="提交保存" class="btn btn-success">
         <input name="returnback" type="button" onClick="history.go(-1);" value="返回上一页" class="btn btn-danger">
        </div>
        </td>

	</tr>
</table>
</form>

</body>
</html>
<script type="text/javascript" src="{$skins_admin}/base.js"></script>