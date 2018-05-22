<?php

/**
 * CWCMS  备份文件
 * ============================================================================
 * * 版权所有 2013-2025 深圳慧名科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Backup.php 202 2015-12-10 16:29:08Z Charntent $
 */

ini_set('memory_limit','128M');
set_time_limit(180);

require 'admin.inc.php';

$action = gpc('action');
$func = 'backup';
$Purview = new Purview();

if(!$Purview->checkpurview($func,$action)){
	message("没有权限",'',1000,1,'nopurview');
}
$viewar = $Purview->getpurviewar($func);

switch($action){
	
	case "export":
		$tablearr = $db->select("SHOW TABLE STATUS","Name");
		$tables = array_keys($tablearr);
		$dumpfile = WL_DATA.DS.'Backup'.DS.date("Ymd_His").'.sql';
		$dbversion = $db->version();
		
		$fast = gpc("fast");
		if(empty($fast)){
			$i = gpc("i");
			$sqlstr = '';
			if($i === null){
				foreach($tablearr as $t => $r){
					$sqlstr .= "DROP TABLE IF EXISTS `$t`;\r\n\r\n";
					$row = $db->find("SHOW CREATE TABLE `$t`",false);
					$tableStruct = $row['Create Table'];
					$tableStruct = preg_replace("/AUTO_INCREMENT=([0-9]{1,})[ \r\n\t]{1,}/i","",$tableStruct);
					$sqlstr .= $tableStruct.";\r\n\r\n";
				}
				$_SESSION['dumpfile'] = $dumpfile;
				file_put_contents($dumpfile,$sqlstr);
				alert("数据结构备份完成，正在备份数据..","?action=export&fast={$fast}&i=0");
			}else{
				$table = $tables[$i];
				$dumpfile = $_SESSION['dumpfile'];
				$row = $db->select("Select * From `$table` ");
				foreach($row as $rs){
					$sqlstr .= "INSERT INTO `$table` VALUES(";
					$line = '';
					foreach($rs as $f){
						$f = addslashes($f);
						$f = str_replace("\r","\\r",$f);
						$f = str_replace("\n","\\n",$f);
						$line .= "'".$f."',";
					}
					$sqlstr .= rtrim($line,',').");\r\n\r\n";
				}
				file_put_contents($dumpfile,$sqlstr,FILE_APPEND);
				if(count($tables)==$i+1){
					alert("全部数据备份完成","?action=recover");
				}else{
					$next = $i+1;
					alert("表".$table."备份完成，请稍后..","?action=export&fast={$fast}&i={$next}");
				}
			}
		}else{
			$tablesstr = join(" ",$tables);
			
			$mysql_base = $db->getfield("SHOW VARIABLES LIKE 'basedir'",false);
			$mysqlbin = $mysql_base == '/' ? '' : addslashes($mysql_base).'bin/';

			@shell_exec('"'.$mysqlbin.'mysqldump" --force --quick --default-character-set=utf8 '.($dbversion > 4.1 ? '--skip-opt --create-options' : '-all').' --add-drop-table --extended-insert --host='.$dbhost.' --port=3306 --user='.$dbuser.' --password='.$dbpassword.' '.$dbname.' '.$tablesstr.' > '.$dumpfile);
			alert("备份成功！","?action=recover");
		}
	break;
	
	case "import":
		$dumpfile = gpc('dumpfile');
		$dumpfile = WL_DATA.DS.'Backup'.DS.$dumpfile;
		
		$sqlstr = file_get_contents($dumpfile);
		if(preg_match("/^\-\- MySQL dump/i",$sqlstr)){
			$mysql_base = $db->getfield("SHOW VARIABLES LIKE 'basedir'",false);
			$mysqlbin = $mysql_base == '/' ? '' : addslashes($mysql_base).'bin/';
			@shell_exec('"'.$mysqlbin.'mysql" --default-character-set=utf8 -h '.$dbhost.' -P3306 -u'.$dbuser.(empty($dbpassword)?'':' -p'.$dbpassword).' '.$dbname.' < '.$dumpfile);
		}else{
			$sqlstr = str_replace("\r","\n",$sqlstr);
			$sqls = explode(";\n",$sqlstr);
			foreach($sqls as $line){
				$line = trim($line);
				if(!empty($line)){
					$db->query( $line );
				}
			}
		}
		alert("数据库还原成功！");
	break;
	
	case "del":
		$dumpfile = gpc('dumpfile');
		$dumpfile = WL_DATA.DS.'Backup'.DS.$dumpfile;
		@unlink($dumpfile);
		message("删除完成！",'',1000,0);
	break;
	
	case "down":
		$file = gpc('dumpfile');
		$dumpfile = WL_DATA.DS.'Backup'.DS.$file;
		header("Content-type: application/octet-stream");
		header("Accept-Length: ".filesize($dumpfile));
		header("Content-Disposition: attachment; filename=" . $file);
		echo file_get_contents($dumpfile);
	break;
	case "zip";
		$file = gpc('dumpfile');
		$dumpfile = WL_DATA.DS.'Backup'.DS.$file;
		$zipfile = $dumpfile.'.zip';
		@unlink($zipfile);
		if (@function_exists('gzcompress')){
			$zip = new Zip();
			$zip ->CompileZipFile($dumpfile,$zipfile,'file',$file);
			header("Location: ".BASEURL."/Runtimes/Data/Backup/".$file.'.zip');
		}else{
			header("Location: ".BASEURL."/Runtimes/Data/Backup/".$file);
		}
		
	break;
	case "recover";
		$dir = WL_DATA.DS.'Backup';
		$fp = dir($dir);
		$bklist = array();
	
		while(false !== ($entry = $fp->read())){
			$backupfile = $dir.DS.$entry;
			if(is_file($backupfile) && preg_match("/\.sql$/",$backupfile)){
				$filesize = filesize($backupfile);
				$bklist[] = array(
					'name' => $entry,
					'size' => formatsize($filesize),
					'time' => substr($entry,0,4).'-'.substr($entry,4,2).'-'.substr($entry,6,2).' '.substr($entry,9,2).':'.substr($entry,11,2).':'.substr($entry,13,2),
				);
			}
		}
		require tpl("backup/backup");
	break;
	case "repair":
		$table = gpc("table");
		$db->query("REPAIR TABLE $table");
		alert("修复{$table}完成！");
	break;
	case "optimize":
		$table = gpc("table");
		$db->query("OPTIMIZE TABLE $table");
		alert("优化{$table}完成！");
	break;
	default:
		$tables = $db->select("SHOW TABLE STATUS","Name");
        $totalsize = 0;
        $allrow = 0;
        $allfreesize = 0;
		foreach($tables as $k=>$r){
            $totalsize += $r['Data_length']+$r['Index_length']+$r['Data_free'];
            $allrow += $r['Rows'];
            $allfreesize += $r['Data_free'];
		}
		require tpl("backup/backup");
}
