<?php

/**
 * @version    $Id: rewrite.class.php 256 2012-10-15 10:01:47Z qinjinpeng $
 */

class Rewrite
{
    public $rules = array();
	
	function __construct(){
		$this->rules = require DATA.DS.'rewrite.php';
	}
	
    function ConvertHtml(&$html)
    {
		global $sitepath,$rewrite,$is_html;
		if(!empty($rewrite)){
			foreach($this->rules as $s=>$t)
			{
				$s = str_replace(array('?','(*)'),array('\?','([\w]*)'),$s);
				$t = $sitepath.$t;
				$html = preg_replace('/"index\.php'.$s.'"/', '"'.$t.'"', $html);
				$html = preg_replace('/\'index\.php'.$s.'\'/', '\''.$t.'\'', $html);
				$html = preg_replace('/"'.$s.'"/', '"'.$t.'"', $html);
				$html = preg_replace('/\''.$s.'\'/', '\''.$t.'\'', $html);
			}
		}
		if(!empty($is_html)){
			$html = preg_replace('/index.php\?(catid|page)=([0-9]{1,})(&start=){0,1}([0-9]{0,})/sie',"convert_catid_to_url('$2','$4')",$html);
			$html = preg_replace('/((src|href)=")([^\/][^"]*)(")/sie',"self::convert_url('$1','$3','$4')",$html);
		}
    }
	
	public static function convert_url($start,$url,$end){
		global $sitepath;
		if(stripos($url,'javascript')!==false || stripos($url,'http://')!==false || stripos($url,'./')!==false ){
			return stripslashes( $start.$url.$end );
		}
		return stripslashes( $start.$sitepath.'/'.$url.$end );
	}
	
    function ParseUrl()
    {
		global $sitepath;
		
		//获取后戳名
	    $prefix = file_prefix();
		
		$l = strlen($prefix);
		
        $request_uri = $_SERVER['REQUEST_URI'];
	
		$prefix_new = substr($request_uri,-$l);
		
		if($prefix_new != $prefix && trim($request_uri,"/") != $sitepath){
			$request_uri = "/".trim($request_uri,"/").$prefix;
		}
        $request_uri = preg_replace( "#^".preg_quote($sitepath)."#",'',$request_uri);
        $url = str_replace('/index.php','',$request_uri);
        $sitepath = !empty($sitepath)?'/'.$sitepath:'';
		if(empty($url) || $url=='/' || $url[0]=='?' || $url[0].$url[1]=='/?' || $url == $sitepath || $url == $sitepath."/") return ;
		
        foreach($this->rules as $s=>$t)
        {
			$t = preg_quote($t);
			
			$t = str_replace('\$','$',$t);
			$t = preg_replace('/\$[0-9]/','([\w]+)',$t);

            $i = 1;
            while(strstr($s,'(*)'))
            {
                $s = preg_replace('/\(\*\)/','\$'.$i,$s,1);
                $i++;
            }
			
            if(preg_match('#^'.$sitepath.$t.'#iU',$url,$ar))
            {  
			    //如果存在?
                if(strpos($url,'?')!==false)
                {
					$url = preg_replace('/\?.*?$/','',$url);
                }
                $url = preg_replace('#^'.$sitepath.$t.'$#iU',$s,$url);
                $urls = parse_url($url);
                break;
            }else{
				$urls = array();
			}
        }
		
        if(!empty($urls['query']))
        {
			parse_str($urls['query'],$data);
			set_gpc($data);
        }else{
			if(!empty($urls['path']) &&  $urls['path']= 'index.php'){
			    return;
			}
			else{
				header('HTTP/1.1 404 Not Found'); 
				header("status: 404 Not Found"); 
				exit(404);
			}
		}
    }

}
?>