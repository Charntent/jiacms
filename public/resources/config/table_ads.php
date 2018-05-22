<?php return array (
  'listfield' => 
  array (
    0 => 'title',
    1 => 'type',
    2 => 'abc',
    3 => 'adtype',
    4 => 'weight',
  ),
  'editfield' => 
  array (
    0 => 'title',
    1 => 'type',
    2 => 'abc',
    3 => 'adtype',
    4 => 'pic',
    5 => 'flash',
    6 => 'width',
    7 => 'height',
    8 => 'url',
    9 => 'txt',
    10 => 'weight',
  ),
  'searchfield' => 
  array (
    0 => 'title',
  ),
  'func' => 
  array (
    0 => 'allowadd',
    1 => 'allowedit',
    2 => 'allowdel',
  ),
  'field' => 
  array (
    'title' => 
    array (
      'name' => '广告位置',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'type' => 
    array (
      'name' => '使用端口',
      'type' => 'radio',
      'desc' => '',
      'default' => 'PC',
      'option' => 'PC,mobile',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'abc' => 
    array (
      'name' => '广告标识',
      'type' => 'input',
      'desc' => '给同一组广告同一标识，便于查询，可以不设置',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => 'if($action==\'\') $abc = $abc." &nbsp;&nbsp; <font color=gray>调用标签:&nbsp;".htmlspecialchars("<!--ad(".$arr[\'id\'].")-->")."<font>";',
    ),
    'adtype' => 
    array (
      'name' => '广告类型',
      'type' => 'radio',
      'desc' => '<script>
function formload(v){
	if(v==\'图片广告\'){
		$("input[name=pic]").closest("tr").show();
		$("input[name=flash]").val("").closest("tr").hide();
		$("input[name=width]").closest("tr").show();
		$("input[name=height]").closest("tr").show();
		$("input[name=url]").closest("tr").show();
		$("#txt").closest("tr").hide();
	}else if(v==\'flash广告\'){
		$("input[name=pic]").val("").closest("tr").hide();
		$("input[name=flash]").closest("tr").show();
		$("input[name=width]").closest("tr").show();
		$("input[name=height]").closest("tr").show();
		$("input[name=url]").closest("tr").hide();
		$("#txt").closest("tr").hide();
	}else{
		$("input[name=pic]").val("").closest("tr").hide();
		$("input[name=flash]").val("").closest("tr").hide();
		$("input[name=width]").closest("tr").hide();
		$("input[name=height]").closest("tr").hide();
		$("input[name=url]").closest("tr").hide();
		$("#txt").closest("tr").show();
	}	
}
$("input[name=adtype]").click(function(){
	formload($(this).val());
})
$(function(){
	formload($("input[name=adtype]:checked").val());
})
if(aid!=\'\') $("input[name=adtype]").closest("tr").hide();
</script>',
      'default' => '图片广告',
      'option' => '图片广告,flash广告,文字广告',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'pic' => 
    array (
      'name' => '图片',
      'type' => 'file',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => 'if(!empty($pic)) set_gpc(\'content\',\'<a href="\'.$url.\'" target="_blank"><img src="\'.$pic.\'" width="\'.$width.\'" height="\'.$height.\'"/></a>\');',
      'outputexec' => '',
    ),
    'flash' => 
    array (
      'name' => 'flash',
      'type' => 'file',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => 'if(!empty($flash)) set_gpc(\'content\',\'<embed src="\'.$flash.\'" type="application/x-shockwave-flash" width="\'.$width.\'" height="\'.$height.\'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>\');',
      'outputexec' => '',
    ),
    'width' => 
    array (
      'name' => '宽度',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'height' => 
    array (
      'name' => '高度',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
    'url' => 
    array (
      'name' => '链接地址',
      'type' => 'input',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => 'if(empty($pic)) $url="";',
      'outputexec' => '',
    ),
    'txt' => 
    array (
      'name' => '文本广告',
      'type' => 'minieditor',
      'desc' => '',
      'default' => '',
      'option' => '',
      'inputexec' => 'if(empty($pic) && empty($flash)) {
	set_gpc(\'content\',$txt);
	set_gpc("width",0);
	set_gpc("height",0);
}else{
	$txt = "";
}',
      'outputexec' => '',
    ),
    'weight' => 
    array (
      'name' => '排序',
      'type' => 'input',
      'desc' => '越小排越前面',
      'default' => '100',
      'option' => '',
      'inputexec' => '',
      'outputexec' => '',
    ),
  ),
); ?>