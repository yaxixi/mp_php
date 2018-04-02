<?php

	error_reporting(E_ALL);
    ini_set('display_errors', 'On');
ini_set('display_startup_errors','On');

    $ac = $_REQUEST['ac'];
    $id = $_REQUEST['id'];
    $amount = (float)$_REQUEST['amount'];

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

    header('Location: alipays://platformapi/startapp?appId=09999988&actionType=toAccount&goBack=YES&amount='.$amount.'&userId='.$ac.'&memo='.$id);

?>
