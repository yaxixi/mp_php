<?php

date_default_timezone_set("Asia/Shanghai");

// 生成一条日志记录，日志位于 logs/$category 目录下，使用当前日期做为日志文件名，
// 这样每天会生成一个新的日志文件，避免同一个日志文件过大
function addLog($category, $content)
{
    $rootDir = 'logs';

    if (!is_dir($rootDir))
        mkdir($rootDir, 0777, true);

    $dir = sprintf("logs/%s", $category);
    if (!is_dir($dir))
        mkdir($dir, 0777, true);

    $filePath = sprintf("%s/%s.log", $dir, date("Ymd"));
    $f = fopen($filePath, "a+");
    $curTime = date("Y-m-d H:i:s", time())." ";
    $content = $curTime . " " . $content . "\n";
    fwrite($f, $content);
    fclose($f);
}

?>