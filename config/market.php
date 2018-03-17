<?php

// 商品美元价格表
$goods_price_map = array(
    10001 => '0.99',
    10002 => '9.99',
    10003 => '16.99',
    10004 => '16.99',
    10005 => '19.68',
    10006 => '64.68',
    10007 => '2.99',
    5001  => '60',
);

// 获取商品价格
function get_goods_price($goods_id)
{
    $price = 0;
    global $goods_price_map;
    if (array_key_exists($goods_id, $goods_price_map))
    {
        $price = $goods_price_map[$goods_id];
    }

    return $price;
}

?>