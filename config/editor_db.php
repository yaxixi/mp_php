<?php
function get_gdb_db()
{
    return array(
        'DB_HOST'   => '127.0.0.1',        // 服务器地址
        'DB_NAME'   => 'hgdb',          // 数据库名
        'DB_USER'   => 'root',         // 用户名
        'DB_PWD'    => '1234',             // 密码
        'DB_PORT'   => 3306,                   // 端口
    );
}

/** 数据库表语句
CREATE TABLE `order_3rd` (
    order_id varchar(64) not null primary key,
    channel varchar(64),
    ccid int not null default 2,
    amount int not null default 0,
    account varchar(64) not null,
    server varchar(64) not null default '0',
    pay_time varchar(64),
    memo varchar(256)
);
*/
?>