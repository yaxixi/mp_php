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
        addLog("precharge", $msg . " " . $ret);
        die($ret);
    }

    $uid = $_REQUEST["uid"];
    $orderid = $_REQUEST["orderid"];
    $randomstr = $_REQUEST["r"];
    $key = $_REQUEST["key"];
    $token = '';

    $db = ezSQL_mysql::get_db("mpay");
    if (!$db)
    {
        $return['msg'] = '数据库连接失败';
        $return['data'] = '';
        $return['code'] = -1;
        $return['url'] = '';
        go_error(json_encode($return), "fail to connect dbasebase");
    }

    $ret = $db->get_row("select status, token from vendor where uid='$uid'");
    if ($ret && (int)$ret['status'] == 0)
    {
        $token = $ret['token'];
    }
    else
    {
        $db->disconnect();
        $return['msg'] = '商户不合法';
        $return['data'] = '';
        $return['code'] = -1;
        $return['url'] = '';
        die(json_encode($return));
    }

    if ($token)
    {
        // 进行验证
        $my_key = strtolower(md5($uid. $orderid. $randomstr . $token));
        if (strcmp($my_key, $key) != 0)
        {
            $db->disconnect();
            $return['msg'] = 'key验证失败';
            $return['data'] = '';
            $return['code'] = -1;
            $return['url'] = '';
            die(json_encode($return));
        }
        else
        {
            // 验证通过，取得信息
            $ret = $db->get_row("select status from charge where orderid='$orderid'");
            if ($ret)
            {
                $return['msg'] = 'OK';
                $return['data'] = array(
                    'orderid'=> $orderid,
                    'status'=>$ret['status'],
                );
                $return['code'] = 1;
                $return['url'] = '';
                die(json_encode($return));
            }
            else
            {
                $db->disconnect();
                $return['msg'] = '订单不存在';
                $return['data'] = '';
                $return['code'] = -1;
                $return['url'] = '';
                die(json_encode($return));
            }
        }
    }
    else
    {
        $db->disconnect();
        $return['msg'] = '商户不合法';
        $return['data'] = '';
        $return['code'] = -1;
        $return['url'] = '';
        die(json_encode($return));
    }
?>
