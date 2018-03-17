<?php

	/*error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors','On');
    ini_set('error_log', dirname(__FILE__) . '/error_log.txt');*/

    error_reporting(0);

    include_once "common/log.php";
    include_once "common/ez_sql_mysql.php";

    function go_error($ret, $msg)
    {
        addLog("auth", $msg . " " . $ret);
        die($ret);
    }

    $uid = $_REQUEST['uid'];
    $db = ezSQL_mysql::get_db("mpay");
    if (!$db)
        go_error(json_encode(array('ret'=>0)), "fail to connect dbasebase");

    $ret = $db->get_row("select status, url from vendor where uid='$uid'");
    $db->disconnect();
    if ($ret)
    {
        echo json_encode(array('ret'=>(int)$ret['status'], 'url'=>$ret['url']));
    }
    else
    {
        echo json_encode(array('ret'=>1));
    }
?>
