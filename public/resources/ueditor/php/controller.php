<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/chongqing");
//error_reporting(E_ERROR);
error_reporting(E_ALL);
ini_set('display_errors', '1');
header("Content-Type: text/html; charset=utf-8");

$web_root = substr(dirname(__FILE__),0,-21);

/*require_once $web_root.'WLCMS.php';*/
define("NOCMS",substr(dirname(__FILE__),0,-21));
require NOCMS.'index.php';

defined("TMLSTYLE")  || define("TMLSTYLE",$dqxm);

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);

$CONFIG['imagePathFormat'] =  $CONFIG['catcherPathFormat'] = WL_ROOT.DS.'Uploads/'.TMLSTYLE.'/{yyyy}{mm}{dd}/{time}{rand:6}';
$CONFIG['imageManagerListPath'] = $web_root.'Uploads'.'/'.TMLSTYLE;
$CONFIG['videoPathFormat']  = WL_ROOT.DS.'Uploads/'.TMLSTYLE.'/{yyyy}{mm}{dd}/{time}{rand:6}';


if(IMGADDWEB){
	define("BDBASEURL",BASEURL);
}else{
	if(ERJIMULU!=''){
	    define("BDBASEURL",'/'.ERJIMULU);
	}
	else{
		 define("BDBASEURL",''); 
	}
}




$action = $_GET['action'];

switch ($action) {
    case 'config':
        $result =  json_encode($CONFIG);
        break;

    /* 上传图片 */
    case 'uploadimage':
    /* 上传涂鸦 */
    case 'uploadscrawl':
    /* 上传视频 */
    case 'uploadvideo':
    /* 上传文件 */
    case 'uploadfile':
        $result = include("action_upload.php");
        break;

    /* 列出图片 */
    case 'listimage':
        $result = include("action_list.php");
        break;
    /* 列出文件 */
    case 'listfile':
        $result = include("action_list.php");
        break;

    /* 抓取远程文件 */
    case 'catchimage':
        $result = include("action_crawler.php");
        break;

    default:
        $result = json_encode(array(
            'state'=> '请求地址出错'
        ));
        break;
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $result;
}