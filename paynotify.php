<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 *
 * 此页就是您之前传给pay.qpayapi.com的notify_url页的网址
 * 支付成功，平台会根据您之前传入的网址，回调此页URL，post回参数
 *
 * --------------------------------------------------------------
 */

include_once "common/log.php";
addLog("paynotify", json_encode($_REQUEST));
    echo "OK";
?>
