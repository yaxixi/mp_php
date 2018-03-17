<?php
require "config/db.php";

function qclog($filePath,$msg)
{
    $f = fopen($filePath, "a+");
    fwrite($f, $msg. "\n");
    fclose($f);
}

function connect_db()
{
    $config = get_db_config();
    $conn = mysql_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PWD']);
    if (! $conn) return;

    // 选择数据库
    mysql_select_db($config['DB_NAME'], $conn);

    // 设置连接字符集
    mysql_query("set names 'utf8'", $conn);
    mysql_query("set character_set_client=utf8", $conn);
    mysql_query("set character_set_results=utf8", $conn);

    return $conn;
}

function get_precharge_info($platfrom, $precharge_id)
{
    $conn = connect_db();
    if (!$conn) return false;

    // 查看此单据是否已经存在
    $sql = "SELECT * FROM precharge_3rd WHERE 3rdplatform = '$platfrom' and `precharge_id`='$precharge_id'";

    $rs = mysql_query($sql, $conn);
    if ($rs)
        $row = mysql_fetch_array($rs);

    if ($row)
    {
        // 记录已经存在，就认为成功了
        return $row;
    }
    return false;
}

function check_order($order_id)
{
    $conn = connect_db();
    if (!$conn) return false;

    // 查看此单据是否已经存在
    $sql = "SELECT * FROM `order_3rd` WHERE order_id='$order_id'";
    $rs = mysql_query($sql, $conn);
    if ($rs)
        $row = mysql_fetch_array($rs);
    if ($row)
    {
        // 记录已经存在，就认为成功了
        return true;
    }
    return false;
}

// 插入充值记录到数据库中，成功返回true
function insert_data($order_id, $channel, $amount, $account, $server, $pay_time = null, $memo = "", $platform = "", $price=0, $currency="")
{
    $conn = connect_db();
    if (!$conn) return false;

    if ($platform == "")
    {
        $platform = $channel;
    }

    // 查看此单据是否已经存在
    $sql = "SELECT * FROM `order_3rd` WHERE order_id='$order_id'";
    $rs = mysql_query($sql, $conn);
    if ($rs)
        $row = mysql_fetch_array($rs);
    if ($row)
    {
        // 记录已经存在，就认为成功了
        return true;
    }
    else
    {
        // 插入之
        if (! $pay_time)
            // 设置时间
            $pay_time =  time();

        // 以事务进行插入
        mysql_query("START TRANSACTION");

        $config = get_db_config();
        $cc_id = $config["CCID"];

        // 插入到充值记录表
        $sql = "INSERT INTO `order_3rd` ".
               "(`order_id`, `channel`, `ccid`, `amount`, `account`, `server`, `pay_time`, `memo`, `platform`, `price`, `currency`)".
               " VALUES ('$order_id', '$channel', $cc_id, $amount, '$account', '$server', '$pay_time', '$memo', ".
               " '$platform', $price, '$currency')";

        if (!mysql_query($sql, $conn))
        {
            mysql_query("ROLLBACK");
            mysql_close($conn);
            return false;
        }

        // 通知中控
        $sql = "INSERT INTO `pp_sync` ".
               "(`key`, `value`, `ccid`)".
               " VALUES ('order_3rd', '$order_id', $cc_id)";
        if (!mysql_query($sql, $conn))
        {
            mysql_query("ROLLBACK");
            mysql_close($conn);
            return false;
        }

        // 提交事务
        mysql_query("COMMIT");
    }

    // 关闭连接
    mysql_close($conn);
    return true;
}

// 插入充值记录到数据库中，成功返回true
function insert_data_with_platform($order_id, $channel, $amount, $account, $server, $pay_time = null, $memo = "", $platform = "", $price=0, $currency="")
{
    $conn = connect_db();
    if (!$conn) return false;

    if ($platform == "")
    {
        $platform = $channel;
    }

    // 查看此单据是否已经存在
    $sql = "SELECT * FROM `order_3rd` WHERE order_id='$order_id'";
    $rs = mysql_query($sql, $conn);
    if ($rs)
        $row = mysql_fetch_array($rs);
    if ($row)
    {
        // 记录已经存在，就认为成功了
        return true;
    }
    else
    {
        // 插入之
        if (! $pay_time)
            // 设置时间
            $pay_time =  time();

        // 以事务进行插入
        mysql_query("START TRANSACTION");

        $config = get_db_config();
        $cc_id = $config["CCID"];

        // 插入到充值记录表
        $sql = "INSERT INTO `order_3rd` ".
               "(`order_id`, `channel`, `ccid`, `amount`, `account`, `server`, `pay_time`, `memo`, `platform`, `price`, `currency`)".
               " VALUES ('$order_id', '$channel', $cc_id, $amount, '$account', '$server', '$pay_time', '$memo', '$platform', $price, '$currency')";
        if (!mysql_query($sql, $conn))
        {
            mysql_query("ROLLBACK");
            mysql_close($conn);
            return false;
        }

        // 通知中控
        $sql = "INSERT INTO `pp_sync` ".
               "(`key`, `value`, `ccid`)".
               " VALUES ('order_3rd', '$order_id', $cc_id)";
        if (!mysql_query($sql, $conn))
        {
            mysql_query("ROLLBACK");
            mysql_close($conn);
            return false;
        }

        // 提交事务
        mysql_query("COMMIT");
    }

    // 关闭连接
    mysql_close($conn);
    return true;
}

?>
