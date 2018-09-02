<?php
function get_db_config($db_name)
{
    $db_map = array(
        'mpay' => array(
            'DB_HOST'   => 'localhost:3306',
            'DB_NAME'   => 'mpay',
            'DB_USER'   => 'yaxixi',
            'DB_PWD'    => '1234rewq',
        ),
    );
    return $db_map[$db_name];
}
?>
