<?php

    include_once "common/log.php";
    include_once "common/ez_sql_mysql.php";

    $err = (int)$_REQUEST['e'];
    $code = $_REQUEST['c'];
    $time = (int)$_REQUEST['t'];
    $is_valid = 1;
    if (time() - $time >= 300)
        $is_valid = 0;

    if ($is_valid == 0)
    {
        die('该链接已失效');
    }

    if ($err == 1)
    {
        die('通道维护中');
    }

    function go_error($ret, $msg)
    {
        addLog("topay", $msg . " " . $ret);
        die($ret);
    }

    $db = ezSQL_mysql::get_db("mpay");
    if (!$db)
    {
        go_error('地址跳转异常', "fail to connect dbasebase");
    }

    $code = $db->escape($code);
    $ret = $db->get_row("select alipay_para, time from paycode where code='$code'");
    $db->disconnect();
    $alipay_para = '';
    if ($ret)
    {
        if (time() - (int)$ret['time'] >= 300)
            $is_valid = 0;
        $alipay_para = $ret['alipay_para'];
    }
    else
    {
        die('跳转异常');
    }

    echo <<<HTML
<html class="normal ">
<head>
    <meta charset="UTF-8">
    <title>打开支付宝</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="email=no">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0">
</head>
<body>
    <script>
        function addLoadEvent(func) {
          var oldonload = window.onload;
          if (typeof window.onload != 'function') {
            window.onload = func;
          } else {
            window.onload = function() {
              if (oldonload) {
                oldonload();
              }
              func();
            }
          }
        }

        var alipays_url = 'alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=YES&$alipay_para';

        addLoadEvent(function() {
            if ($is_valid == 0)
            {
                var tip = document.getElementById('tip');
                if (tip) {
                    tip.innerHTML = '该链接已失效';
                }
            }
            else
                window.location = alipays_url;
        });
    </script>

    <div>
        <p id='tip'>若无法跳转到支付宝，请在浏览器中打开</p>
    </div>

</body>
</html>
HTML

?>
