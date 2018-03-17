<?php

    $ac = $_REQUEST['ac'];
    $id = urldecode($_REQUEST['id']);

    $ac = rc4("fdsas#%226", base64_decode($ac));
    $id = rc4("fdsas#%226", base64_decode($id));

    /*
     * rc4加密算法
     * $pwd 密钥
     * $data 要加密的数据
     */
    function rc4 ($pwd, $data)//$pwd密钥 $data需加密字符串
    {
        $key[] ="";
        $box[] ="";

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++)
        {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $data_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;

            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;

            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }

        return $cipher;
    }

    echo <<<HTML
<!Doctype html>
<html xmlns=<a rel="nofollow" href="http://www.w3.org/1999/xhtml>" target="_blank"></a>;
<head>
<meta http-equiv=Content-Type content="text/html;charset=utf-8">
<head>
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<title>直接唤醒demo</title>
</head>
<body>
<style>
#alipay{font-size:40px;}
</style>
<!--
说明：通过h5可换醒app，如访问一个URL就能直接打开应用，如果该应用APP没有安装，那么直接跳转到App Store的APP下载页面
兼容性一般：在手机各大浏览器(360浏览器 uc浏览器 搜狗浏览器 QQ浏览器 百度浏览器 )能唤醒。微信 QQ客户端 新浪微博客户端 腾讯微博客户端无法唤醒。
-->
<p id="alipayqr"></p>
<script type="text/javascript">
function applink(account, orderid){
    window.location = 'alipays://platformapi/startapp?appId=09999988&&actionType=toAccount&&goBack=YES&&amount=100&&memo=' + orderid + '&&userId=' + account;
    /*
        var clickedAt = +new Date;
         setTimeout(function(){
             !window.document.webkitHidden && setTimeout(function(){
                   if (+new Date - clickedAt < 2000){
                       window.location = 'https://itunes.apple.com/us/app/zhe-jiang-yi-dong-shou-ji/id898243566#weixin.qq.com';
                   }
             }, 500);
         }, 1000)
     */

}

applink('$ac', '$id');
</script>
</body>
</html>
HTML;
?>
