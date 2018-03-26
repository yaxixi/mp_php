<?php

	/*error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors','On');
    ini_set('error_log', dirname(__FILE__) . '/error_log.txt');*/

    error_reporting(0);

    include_once "common/log.php";
    include_once "common/ez_sql_mysql.php";
    require_once('common/SnsNetwork.php');

    function go_error($ret, $msg)
    {
        addLog("gm_oper", $msg . " " . $ret);
        die($ret);
    }

    $db = ezSQL_mysql::get_db("mpay");
    if (!$db)
    {
        $return['msg'] = '数据库连接失败';
        $return['ret'] = -1;
        go_error(json_encode($return), "fail to connect dbasebase");
    }

    function switch_account_status()
    {
        $account = $_REQUEST["account"];
        $status = $_REQUEST["status"];
        global $db;
        $db->query("update account set status=$status where account='$account'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function return_money()
    {
        $id = $_REQUEST['id'];
        global $db;
        $db->query("update charge_exception set status=2 where id=$id and status=0");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function charge_exception_count()
    {
        global $db;
        $clientTime = date("Y-m-d");
        $ret = $db->get_row("select count(*) as num from charge_exception where status=0 and clientTime like '$clientTime%'");
        if ($ret)
        {
            die(json_encode(array('ret'=>0,'count'=>$ret['num'])));
        }
        else
            die(json_encode(array('ret'=>-1,'msg'=>'db fail')));
    }

    $func = $_REQUEST["func"];
    if ($func == "switch_account_status")
        switch_account_status();
    else if ($func == "return_money")
        return_money();
    else if ($func == "charge_exception_count")
        charge_exception_count();
?>
