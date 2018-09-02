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
        $status = (int)$_REQUEST["status"];
        global $db;
        if ($status == 0)
            $db->query("update account set status=$status, max_money=150000 where account='$account'");
        else
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

    function delete_account()
    {
        $account = $_REQUEST["account"];
        global $db;
        $db->query("delete from account where account='$account'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function add_account()
    {
        $account = $_REQUEST["account"];
        $accountid = $_REQUEST["accountid"];
        $uid = $_REQUEST['uid'];
        global $db;
        $db->query("insert into account (`account`,`accountid`,`uid`) values ('$account', '$accountid','$uid')");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function set_max_money()
    {
        $account = $_REQUEST["account"];
        $max_money = (float)$_REQUEST["max_money"];
        global $db;
        $db->query("update account set max_money=$max_money where account='$account'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function set_account_demo()
    {
        $account = $_REQUEST["account"];
        $demo = $_REQUEST["demo"];
        global $db;
        $db->query("update account set demo='$demo' where account='$account'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function set_rate()
    {
        $uid = $_REQUEST["uid"];
        $rate = $_REQUEST["rate"];
        global $db;
        $db->query("update vendor set rate=$rate where uid='$uid'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    function add_balance()
    {
        $uid = $_REQUEST["uid"];
        $add_value = $_REQUEST["add_value"];
        global $db;
        $db->query("update vendor set balance=balance+$add_value, total_price=total_price+$add_value where uid='$uid'");

        die(json_encode(array('ret'=>0,'msg'=>'OK')));
    }

    $func = $_REQUEST["func"];
    if ($func == "switch_account_status")
        switch_account_status();
    else if ($func == "return_money")
        return_money();
    else if ($func == "charge_exception_count")
        charge_exception_count();
    else if ($func == "delete_account")
        delete_account();
    else if ($func == 'add_account')
        add_account();
    else if ($func == 'set_max_money')
        set_max_money();
    else if ($func == 'set_account_demo')
        set_account_demo();
    else if ($func == 'set_rate')
        set_rate();
    else if ($func == 'add_balance')
        add_balance();
?>
