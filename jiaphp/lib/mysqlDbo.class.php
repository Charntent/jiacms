<?php

/**
 * CWCMS  SQLDBO，mysql的又一种访问方式
 * ============================================================================
 * * 版权所有 2013-2025 深圳万狼科技有限公司，并保留所有权利。
 * 网站地址: http://www.8888i.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: Charntent $
 * $Id: Sqldbo.class.php 202 2015-12-10 16:29:08Z Charntent $
 */
if (!defined('IN_SYS')) exit('Access Denied');


class mysqlDbo
{
    public $dbhost, $dbname, $dbuser, $dbpassword, $database_prex;
    public $link, $sql, $queryID, $tablename;
    public static $sqls = array();

    public function __construct()
    {
        global $dbhost, $dbuser, $dbpassword, $dbname, $driver, $database_prex;

        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->driver = $driver;
        $this->database_prex = $database_prex;
    }

    public function init()
    {
        try {
            $dsn = $this->driver . ":host=" . $this->dbhost . ";dbname=" . $this->dbname;
            $this->link = new PdoDB($dsn, $this->dbuser, $this->dbpassword, array('PDO_ATTR_PERSISTENT' => true, 'MYSQL_ATTR_USE_BUFFERED_QUERY' => true));
        } catch (PDOException $e) {
            $strMessage = $e->getMessage();
            if (strpos($strMessage, '[2003]') !== false) {
                echo '连接失败: 不能连接到本地的$this->driver数据库！<br />';
            } elseif (strpos($strMessage, '[1049]') !== false) {
                //数据库不存在自动创建,byCharntent at 2014-6-16
                echo '操作失败: 不存在该数据库' . $this->dbname . '！<br />';
            } elseif (strpos($strMessage, '[1045]') !== false) {
                //用户名或密码错误
                echo 'Connection failed: ' . $strMessage . '<br />';
                echo '用户名或密码错误，请将config.php中\'db_name\' => \'\',\'db_user\' => \'\',\'db_password\' => \'\',修改成为正确的设置';
                exit;
            }
        }
        if (!is_object($this->link)) {
            $this->halt('Can not connect to ' . $this->driver . ' server');
            phpinfo();
            exit;
        }
        $this->link->exec('set names utf8');
        $this->link->exec("set sql_mode=''");
        return $this->link;
    }



    public function setquery($sql)
    {
        $this->sql = $sql;
    }

    public function query($sql)
    {
        try {
            if (!is_object($this->link)) {
                $this->init();
            }
            $this->setquery($sql);
            $start = microtime(TRUE);
            $this->queryID = $this->link->query($this->sql) OR $this->halt();
            self::$sqls[] = array('time' => number_format(microtime(TRUE) - $start, 6), 'sql' => $this->sql);
            //file_put_contents(dirname(__FILE__).'/'.date('YmdH').'.log', var_export(self::$sqls, true), FILE_APPEND);
            return $this->queryID;
        } catch (PDOException $e) {
            echo 'Failed: ' . $e->getMessage() . '<br><br>';
        }
    }

    public function fetch($queryID = '')
    {
        try {
            $rs = $this->link->query($this->sql);
            return $rs->fetch();
        } catch (PDOException $e) {
            echo $this->sql . '<br><br>';
            echo 'Failed: ' . $e->getMessage() . '<br><br>';
        }
    }

    public function select($sql, $key = '')
    {
        $this->query($sql);
        $rs = $this->link->query($this->sql);

        $res = $rs->fetchAll();
        if($res && strstr($this->sql,'COLUMNS')) {

           $temp = array();
           foreach ($res as $k=>$v) {
               $temp[$v['Field']] = $v;
           }
            $res = $temp;
            unset($temp);
        }
        return $res;
    }

    public function prepare()
    {
        $rs = $this->link->prepare($this->sql);
        $rs->execute();
        return $rs;
    }

    public function find($sql, $limit = true)
    {
        if ($limit === true) {
            if (stripos($sql, 'limit') === false) $sql = rtrim($sql, ';') . ' limit 1;';
        }
        $this->query($sql);
        $rs = $this->fetch();
        return $rs;
    }

    public function getfield($sql, $limit = true)
    {
        $result = $this->find($sql, $limit);
        return is_array($result) ? array_pop($result) : $result;
    }

    public function data($data)
    {
        set_gpc($data);
        return $this;
    }

    public function save($table, $id = "", $ar = array())
    {

        if (empty($ar)) {
            $rs = $this->select("SHOW COLUMNS FROM `$table`");
            if ($id == "") {
                foreach ($rs as $r) {
                    if ($r['Key'] == 'PRI') {
                        $primary = $r['Field'];
                        break;
                    }
                }
            } else {
                $primary = $id;
            }

            if (isset($_REQUEST[$primary]) && $this->find("select `$primary` from `$table` where `$primary`='{$_REQUEST[$primary]}' ")) {
                $value = gpc($primary);
                $where = "`$primary`='$value'";
                $data = array();
                foreach ($rs as $row) {
                    $fieldname = $row['Field'];
                    if (isset($_REQUEST[$fieldname])) {
                        $data[] = "`$fieldname` = '" . $_REQUEST[$fieldname] . "'";
                    }
                }
                if (count($data) > 0) {
                    $sql = "update `$table` set " . join(",", $data) . " where " . $where;
                    return $this->query($sql);
                }
            } else {
                $fields = $values = array();
                foreach ($rs as $row) {
                    $fieldname = $row['Field'];
                    if (isset($_REQUEST[$fieldname])) {
                        $fields[] = "`$fieldname`";
                        $values[] = "'" . $_REQUEST[$fieldname] . "'";
                    }
                }
                if (count($fields) > 0) {
                    $sql = "insert into `$table` (" . join(',', $fields) . ") values (" . join(',', $values) . ") ";
                    $this->query($sql);
                    return $this->insert_id();
                }
            }
        }
        if (!empty($ar)) {
            $fields = $values = array();
            $str = array();
            foreach ($ar as $k => $row) {
                $fields[] = "`$k`";
                $values[] = "'" . $row . "'";
                $str[] = "`{$k}`='{$row}'";
            }
            if ($id) {
                $sql = "update `$table` set " . implode(",", $str) . " where `{$id}`=" . $ar[$id];
                return $this->query($sql);
            } else {
                if (count($fields) > 0) {
                    $sql = "insert into `$table` (" . join(',', $fields) . ") values (" . join(',', $values) . ") ";
                    $this->query($sql);
                    return $this->insert_id();
                }
            }
        }
    }

    public function insert_id()
    {
        return $this->link->lastInsertId();
    }

    public function affected_rows()
    {
        //return mysql_affected_rows($this->link);
        return $this->link->rowCount();
    }

    public function version()
    {
        if (!is_object($this->link)) {
            $this->init();
        }
        return mysql_get_server_info($this->link);
    }


    public function errno($msg = '', $query = '', $die = false)
    {

    }

    public function error($msg = '', $query = '', $die = false)
    {
        try {
            $text = "Addr:" . getenv("REMOTE_ADDR");
            $text .= "\r\nData:" . date("Y-m-d H:i:s");
            $text .= "\r\nCode:";
            $text .= "\r\nPage:" . $_SERVER['PHP_SELF'];
            $text .= "\r\nWarning:" . $msg;
            $text .= "\r\nQuery:" . $query . "\r\n\r\n";
            die($text);
        } catch (PDOException $e) {
            echo 'Failed: ' . $e->getMessage() . '<br><br>';
        }
    }

    public function halt($msg = '')
    {
        try {
            $errmsg = '';
            if ($this->sql) $errmsg .= "<b>$this->driver Query : </b> " . $this->sql . " <br>";
            if ($msg) $errmsg .= "<b>Error Message : </b> $msg <br />";
            if (!empty($errmsg)) {
                echo '<div style="padding:10px;border:1px solid #F90;color:#666;font-family:Arial;">' . $errmsg . '</div>';
            }
            exit;
        } catch (PDOException $e) {
            echo 'Failed: ' . $e->getMessage() . '<br><br>';
        }
    }


    public function settable($table)
    {
        $this->tablename = $table;
        $this->sql = "";
        return $this;
    }

    public function t($table)
    {
        $this->sql = '';
        return $this->settable($table);
    }

    public function tb($table)
    {
        $this->sql = '';
        return $this->settable($this->database_prex . $table);
    }

    public function where($w)
    {

        if (is_array($w)) {

            $htmlbuild = '';
            foreach ($w as $k => $v) {
                if (is_array($v)) {

                    foreach ($v as $k1 => $v1) {
                        //应该是三个数字！
                        if ($k1 == 0)
                            $htmlbuild .= ' AND `' . $v1 . '`';
                        elseif ($k1 == 2)
                            $htmlbuild .= " '" . $v1 . "' ";
                        else
                            $htmlbuild .= $v1;
                    }
                } else {

                    //应该是三个数字！
                    if ($k == 0)
                        $htmlbuild .= ' AND  ' . $v . '';
                    elseif ($k == 2)
                        $htmlbuild .= "  '" . $v . "'  ";
                    else
                        $htmlbuild .= " " . $v . "  ";
                }
            }
            $this->sql = $this->sql . " WHERE 1 " . $htmlbuild;
        } else {
            $this->sql = $this->sql . " WHERE " . $w;
        }
        return $this;
    }

    public function orwhere($w)
    {
        if (is_array($w)) {
            $htmlbuild = '';
            foreach ($w as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k1 => $v1) {
                        //应该是三个数字！
                        if ($k1 == 0)
                            $htmlbuild .= ' OR (`' . $v1 . '`';
                        elseif ($k1 == 2)
                            $htmlbuild .= "'" . $v1 . "' )";
                        else
                            $htmlbuild .= $v1;
                    }
                } else {
                    //应该是三个数字！
                    if ($k == 0)
                        $htmlbuild .= ' OR `' . $v . '`';
                    elseif ($k == 2)
                        $htmlbuild .= "'" . $v . "'";
                    else
                        $htmlbuild .= $v;
                }
            }
            $this->sql = $this->sql . " WHERE 1 " . $htmlbuild;
        } else {
            $this->sql = $this->sql . " WHERE 1 OR " . $w;
        }
        return $this;
    }

    /*
        ON条件
    */
    public function on($w)
    {
        if (is_array($w)) {
            $htmlbuild = '';
            $jflag = 0;
            foreach ($w as $k => $v) {

                if (is_array($v)) {

                    foreach ($w as $k => $v) {

                        //应该是三个数字！
                        if ($k == 0) {
                            if ($jflag == 0) {
                                $htmlbuild .= ' ON ' . $v;
                                $jflag++;
                            } else {
                                $htmlbuild .= ' AND ' . $v;
                            }
                        } elseif ($k == 2)
                            $htmlbuild .= " '" . $v . "' ";
                        else
                            $htmlbuild .= " " . $v;
                    }
                } else {
                    //应该是三个数字！
                    if ($k == 0)
                        $htmlbuild .= ' ON  ' . $v;
                    elseif ($k == 2) {
                        if (strstr($v, '.'))
                            $htmlbuild .= " " . $v . " ";
                        else {
                            $htmlbuild .= " '" . $v . "' ";
                        }
                    } else
                        $htmlbuild .= $v;
                }
            }
            $this->sql = $this->sql . " " . $htmlbuild;
        } else {
            $this->sql = $this->sql . " ON " . $w;
        }
        return $this;
    }

    public function leftjoin($w)
    {

        $this->sql = $this->sql . " " . implode(' LEFT JOIN ', $w);
        return $this;
    }

    public function rightjoin($w)
    {

        $this->sql = $this->sql . " " . implode(" RIGHT JOIN ", $w);
        return $this;
    }

    public function innerjoin($w)
    {
        $this->sql = $this->sql . " " . implode(" INNER JOIN ", $w);
        return $this;
    }

    public function orderby($by, $xu = "DESC")
    {
        $this->sql = $this->sql . " ORDER BY " . $by . " " . $xu;
        return $this;
    }

    public function limit($limit)
    {
        $this->sql = $this->sql . " LIMIT " . $limit;
        return $this;
    }

    public function all($field = '*')
    {
        return $this->SelectData($field);
    }

    public function get($i = 1, $field = '*')
    {
        if ($i < 1) {
            return false;
        } elseif ($i == 1) {
            return $this->FindData($field);
        }
        return $this->SelectData($field, $i);
    }


    public function field($field = 'id')
    {
       $res = $this->FindData($field);
       return $res?array_pop($res):null;
    }

    public function SelectData($field, $limit = 0)
    {
        if ($limit != 0) {
            $this->limit($limit);
        }

        if ($this->tablename != '') {
            $this->sql = "SELECT  " . $field . " FROM  `" . $this->tablename . "` " . $this->sql;
        } else {
            $this->sql = "SELECT " . $field . " FROM  " . $this->sql;
        }
        $tempsql = $this->sql;
        $this->sql = "";
        $this->tablename = "";
        return $this->select($tempsql);
    }

    public function FindData($field)
    {

        if ($this->tablename != '') {
            $this->sql = "SELECT  " . $field . " FROM  `" . $this->tablename . "` " . $this->sql;
        } else {
            $this->sql = "SELECT " . $field . " FROM  " . $this->sql;
        }
        $tempsql = $this->sql;
        $this->sql = "";
        $this->tablename = "";
        return $this->find($tempsql);
    }

    public function FieldData($field)
    {

        $this->sql = "SELECT " . $field . " FROM " . $this->tablename . $this->sql;
        $tempsql = $this->sql;
        $this->sql = "";
        $this->tablename = "";
        return $this->getfield($tempsql);
    }

    public function UpdateTable($values, $where, $tabler = 'admin', $orderby = array(), $limit = FALSE)
    {
        $table = ($this->tablename == "") ? $tabler : $this->tablename;
        foreach ($values as $key => $val) {
            $valstr[] = "`" . $key . "`" . " = '" . $val . "'";
        }

        $limit = (!$limit) ? '' : ' LIMIT ' . $limit;

        $orderby = (count($orderby) >= 1) ? ' ORDER BY ' . implode(", ", $orderby) : '';

        $sql = "UPDATE `" . $table . "` SET " . implode(', ', $valstr);

        $sql .= ($where != '' AND count($where) >= 1) ? " WHERE " . implode(" ", $where) : '';

        $sql .= $orderby . $limit;

        return $this->query($sql);
    }

    public function AddData($value, $tabler = 'admin', $sqlmap = false)
    {
        $table = ($this->tablename == "") ? $tabler : $this->tablename;
        foreach ($value as $key => $val) {
            $keys[] = "`" . $key . "`";
            $values[] = "'" . $val . "'";
        }
        $sql = "INSERT INTO `" . $table . "` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ")";

        if ($sqlmap == true) {
            $this->topSql = "INSERT INTO `" . $table . "` (" . implode(', ', $keys) . ") VALUES ";
            $this->sql = "(" . implode(', ', $values) . "),";
            return $this;
        }

        if ($this->query($sql))
            return $this->insert_id();
        else
            return false;
    }

    public function add($value, $tabler = 'admin', $sqlmap = false)
    {
        return $this->AddData($value, $tabler,$sqlmap);
    }

    public function DeleteData($value, $pt = 'id', $tabler = '')
    {
        if (empty($this->tablename) and $tabler == '') {
            return false;
        } else {
            if ($tabler != '') {
                $this->tablename = $tabler;
            }

            $sql = "DELETE FROM " . $this->tablename . "  WHERE  `" . $pt . "`=" . $value;
            return $this->query($sql);
        }


    }


    public function GetCount($sql)
    {
        return count($this->select($sql));
    }

    public function getCol($sql, $temp)
    {
        $res = $this->query($sql);

        if ($res !== false) {
            $arr = array();
            while ($row = $this->fetch()) {
                if (isset($row[$temp]))
                    $arr[] = $row[$temp];
                else {
                    break;
                }
            }

            return $arr;
        } else {
            return false;
        }
    }

    public function setField($field, $v)
    {
        if (!$this->tablename) return false;
        $sql = "UPDATE `" . $this->tablename . "` SET `" . $field . "` = '" . $v . "' " . $this->sql;
        return $this->query($sql);
    }

    public function startMap()
    {
        $this->sqlMaps = '';
    }

    public function ready()
    {
        $this->sqlMaps .= $this->sql;
        return $this;
    }

    public function getMap()
    {
        $this->sqlMaps = trim($this->sqlMaps, ',');
        return $this->topSql . $this->sqlMaps;
    }

    public function getTopSql()
    {
        return $this->topSql;
    }





}

?>