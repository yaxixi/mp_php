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
        addLog("gathering", $msg . " " . $ret);
        die($ret);
    }

    $orderid = $_REQUEST["orderid"];
    $id = $_REQUEST["id"];
    $uid = $_REQUEST["uid"];
    $price = $_REQUEST['price'];
    $key = $_REQUEST["key"];
    $token = 'A9Y3A00J8001';

    // 进行验证
    $my_key = strtolower(md5($orderid. $uid. $token));
    if (strcmp($my_key, $key) != 0)
    {
        die(json_encode(array('ret'=>-1,'msg'=>'key验证失败')));
    }

    $db = ezSQL_mysql::get_db("mpay");
    if (!$db)
    {
        $return['msg'] = '数据库连接失败';
        $return['ret'] = -1;
        go_error(json_encode($return), "fail to connect dbasebase");
    }

    $salt = '';
    $ret = $db->get_row("select status, salt, token from vendor where uid='$uid'");
    if ($ret)
    {
        if ((int)$ret['status'] != 0)
            die(json_encode(array('ret'=>-1,'msg'=>'uid失效')));

        $salt = $ret['salt'];
    }
    else
    {
        die(json_encode(array('ret'=>-1,'msg'=>'uid无效:'.$uid)));
    }

    $precharge_info = $db->get_row("select * from precharge where orderid='$orderid'");
    if ($precharge_info)
    {
        if ($precharge_info['status'] == 1)
            die(json_encode(array('ret'=>-1,'msg'=>'该订单已支付')));
    }
    else
    {
        die(json_encode(array('ret'=>-1,'msg'=>'orderid无效')));
    }

    function notify_pay($pay_info)
    {
        global $salt;
        global $uid;
        global $id;
        $data_list[] = json_encode($pay_info);
        $data = json_encode($data_list);
        $key = strtolower(md5($data. $salt. $uid));
        $params = array(
            'data'=>$data,
            'uid'=>$uid,
            'key'=>$key,
        );

        $line = SnsNetwork::makeRequest('http://mpay.yituozhifu.com/mpay/pay.php', $params, '', 'post');
        if ($line['result'])
        {
            $result = json_decode($line['msg'], true);
            if ($result['ret'] == 0)
            {
                global $db;
                if ($id != "")
                    // 通知成功，更新 charge_exception 表状态
                    $db->query("update charge_exception set status=1 where id='$id'");
                die(json_encode(array('ret'=>0,'msg'=>$result['msg'])));
            }
            else
            {
                die(json_encode(array('ret'=>-1,'msg'=>'支付通知失败')));
            }
        }
        else
        {
            die(json_encode(array('ret'=>-1,'msg'=>'支付通知失败')));
        }
    }

    if ($id == "")
    {
        if ($price == "")
            die(json_encode(array('ret'=>-1,'msg'=>'必须指定金额')));

        // 直接通过订单号收款
        $pay_info = array(
            'account'=>$precharge_info['account'],
            'money'=>$price,
            'remark'=>$orderid,
            'fromName'=>'system',
            'time'=>date("Y/m/d h/i"),
        );
        notify_pay($pay_info);
    }
    else
    {
        // 先取
        $ret = $db->get_row("select * from charge_exception where id='$id'");
        if ($ret)
        {
            $pay_info = array(
                'account'=>$ret['account'],
                'money'=>$ret['price'],
                'remark'=>$orderid,
                'fromName'=>$ret['userid'],
                'time'=>$ret['clientTime'],
            );
            notify_pay($pay_info);
        }
        else
        {
            die(json_encode(array('ret'=>-1,'msg'=>'id无效')));
        }
    }
?>
