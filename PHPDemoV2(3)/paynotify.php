<?php
/**
 * ---------------------通知异步回调接收页-------------------------------
 *

 *
 * --------------------------------------------------------------
 */

    $platform_trade_no = $_POST["platform_trade_no"];
    $orderid = $_POST["orderid"];
    $price = $_POST["price"];
    $orderuid = $_POST["orderuid"];
    $key = $_POST["key"];

    //校验传入的参数是否格式正确，略

    $token = "此处填写商户token";

    $temps = md5($orderid . $orderuid . $platform_trade_no . $price . $token);

    if ($temps != $key){
        return jsonError("key值不匹配");
    }else{
        //校验key成功，是自己人。执行自己的业务逻辑：加余额，订单付款成功，装备购买成功等等。

    }

    //返回错误
    function jsonError($message = '',$url=null)
    {
        $return['msg'] = $message;
        $return['data'] = '';
        $return['code'] = -1;
        $return['url'] = $url;
        return json_encode($return);
    }

    //返回正确
    function jsonSuccess($message = '',$data = '',$url=null)
    {
        $return['msg']  = $message;
        $return['data'] = $data;
        $return['code'] = 1;
        $return['url'] = $url;
        return json_encode($return);
    }



?>
