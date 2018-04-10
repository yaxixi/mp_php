<?php

	error_reporting(E_ALL);
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors','On');

    $order = $_REQUEST['order'];
    if (preg_match('/(.*)\(.*\)/', $order, $matches))
    {
        echo json_encode($matches);
        $order = $matches[1];
    }

    echo "  order:". $order;
?>
