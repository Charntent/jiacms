<?php

/**
 * CWCMS  模板控制和标签控制文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Template.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if(!defined('IN_SYS')) exit('Access Denied');
class Template{
    
    public $tpl,$compile;
    public static $tpls=array();
    public $tagArs = [
        'includeTpl'=>'includeTpl'
    ];
	protected $rules = [
		// template
		'/(<\!--|\{)\s*(template|include|require)\s+([\.\w\/\\\]+)\s*(-->|\})/i' => 'includeTpl',
		// function
		'/(\{|<\!--)(\s*[\w]+\(.*?\)\s*)(-->|\})/i' =>'parseTag',
		// echo 
		'/\{\s*@?\$[\w\.\$]+\s*\}/si' =>'echoTag',
		'/\{(title|keywords|description|TPLSTYLE|BASEURL|WL_PUBLIC|_PUBLIC|_STATIC|LANG|HMCDN)\}/' => 'echoConstant',
		// { to <!--
		'/\{\s*((if|else|elseif|foreach|sql|eval|\/if|\/foreach|\/sql).*?)\}/i' =>'ifTag',

		'/<\!--\s*.*?\s*-->/si' => "parseTag0",
		// sql
		'/<\!--\s*sql\s+(.+?)\s*-->/i' => 'echoForeach',
        '/<\!--\s*\/sql\s*-->/i' =>'endForeachs',
		// if
		'/<\!--\s*if\s+(.+?)\s*-->/i' =>'ifP1',
		'/<\!--\s*else\s*-->/i' => 'elseP1',
		'/<\!--\s*elseif\s+(.+?)\s*-->/i' =>'elseIfP1',
		'/<\!--\s*\/if\s*-->/i' => 'ifEnd',
		// foreach
        '/<\!--\s*foreach\s+(\S+)\s*-->/i' =>'foreachList',
		'/<\!--\s*foreach\s+(\S+)\s+(\S+)\s*-->/i' =>'foreachMid' ,
		'/<\!--\s*foreach\s+(\S+)\s+(\S+)\s+(\S+)\s*-->/i' =>'foreachLast',
		'/<\!--\s*\/foreach\s*-->/i' => 'foreachOut',
		// eval
		'/<\!--\s*eval\s+(.+?)\s*-->/is' =>'evalPhp'
	];


    public function includeTpl(){
        return false;
    }

    
    public function __construct($tpl,$tpldir,$cachedir)
    {
		$this->tpl = str_replace("/",DS,$tpldir.DS.$tpl);
		$this->compile = $cachedir.DS.$tpl;
    }

    public function view()
    {
		global $debug;

        if(!is_file($this->tpl)) exit("<p style=' margin:10px; border:1px solid #eee; text-align:center; padding:20px;'>The System Error：Templates[".$this->tpl."] Is Not Exists</p><p style='margin:10px;border:1px solid #eee; text-align:center; padding:20px;'>Please Look For The Man OF JIACMS PHPCoder(www.mitent.com)!</p>");
        self::$tpls[] = $this->tpl;
		if ($debug==true || !file_exists($this->compile) || @filemtime($this->tpl) > @filemtime($this->compile))
		{
			$this->_compile();
		}
		return $this->compile;
    }
    
    protected function _compile()
    {
    	$data = file_get_contents($this->tpl);
        $data = $this->_parse($data);
		$dir = dirname($this->compile);
		if(!is_dir($dir)){
			@mkdir($dir,0777,true);
		}
    	if(false === @file_put_contents($this->compile, $data)) exit("$this->compile file is not writable");
    	@chmod($this->compile, 0774);
    	return true;
    }
    
   	protected function _parse($string)
	{
		$string = $this->_before($string);
        if(function_exists('preg_replace_callback_array')) {
            $string = preg_replace_callback_array(array_keys($this->rules), $this->rules, $string);
        }elseif(function_exists('preg_replace_callback')){
            $match = '';
            foreach ($this->rules as $k=>$v) {
                $string = preg_replace_callback($k, $v, $string);
            }
        }

		return $this->_after($string);
	}
    
	protected function _before($string){
		return $string;
	}
	
	protected function _after($string){
		if(!defined("IS_ADMIN") || !empty($GLOBALS['is_user_tpl'])){
			$string = str_replace('../static/',$GLOBALS['sitepath'].'/static/',$string);
		}
		return $string;	
	}
	
    public function loaded_tpl(){
        return self::$tpls;
    }
    
}

function includeTpl($matches){
    $param = $matches[3];
    return    '<?php include tpl("'.$param.'"); ?>';
}

function parseTag($matches){
    $param = $matches[2];
    return    Tag::_parse_function($param);
}

function parseTag0($matches){
    $param = $matches[0];
    return   Tag::_parse_tag($param);
}

function echoTag($matches){
    $param = $matches[0];
    return   Tag::_parse_echo($param);
}

function echoConstant($matches){
    $param = $matches[1];
    return   '<?php echo '.$param.'; ?>';
}

function ifTag($matches){
    $param = $matches[1];
    return   "<!--$param -->";
}

function ifEnd($matches){
    return   '<?php } ?>';
}



function echoForeach($matches)
{
    $param1 = $matches[1];
    return   '<!--eval $_sql_result = $db->select("'.$param1.'");$_sql_result = Tag::sql_select($_sql_result); --><!--foreach $_sql_result -->';
}

function endForeachs($matches)
{
    return   '<!--/foreach -->';
}

function ifP1($matches){
    $param1 = $matches[1];
    return  '<?php if('.$param1.') { ?>';
}

function elseP1($matches){
    return  '<?php } else { ?>';
}


function elseIfP1($matches){
    $param1 = $matches[1];
    return '<?php } elseif ('.$param1.') { ?>';
}

function foreachList($matches){
    $param1 = $matches[1];
    return '<?php Tag::var_protect("IN"); $index=0; if(is_array('.$param1.')) foreach('.$param1.' as $__i => $__value) { if(is_array($__value)) { $index++; foreach($__value as $__k=>$__v){ ${$__k}=$__v; } } ?>';
}

function foreachMid($matches){
    $param1 = $matches[1];
    $param2 = $matches[2];
    return '<?php Tag::var_protect("IN"); if(is_array('.$param1.')) foreach('.$param1.' as '.$param2.') { ?>';
}

function foreachLast($matches){
    $param1 = $matches[1];
    $param2 = $matches[2];
    $param3 = $matches[3];
    return '<?php Tag::var_protect("IN"); if(is_array('.$param1.')) foreach('.$param1.' as '.$param2.'=> '.$param3.') { ?>';
}

function foreachOut($matches){

    return '<?php };  Tag::var_protect("OUT"); ?>';
}

function evalPhp($matches){
    $param1 = $matches[1];
    return '<?php '.$param1.' ?>';
}








