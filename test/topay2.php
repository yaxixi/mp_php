<?php

/*	error_reporting(E_ALL);
    ini_set('display_errors', 'On');
ini_set('display_startup_errors','On');*/
    error_reporting(0);

    $ac = $_REQUEST['ac'];
    $id = $_REQUEST['id'];
    $amount = (float)$_REQUEST['amount'];
    $time = (int)$_REQUEST['t'];
    $is_valid = 1;
    if (time() - $time >= 300)
        $is_valid = 0;

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

        var alipays_url = 'alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=YES&amount='.$amount.'&userId='.$ac.'&memo='.$id;

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
