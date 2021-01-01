<?php
$content = $_REQUEST["msg"];
$time = data(format:'Y-m-d H:i:s', time());
$file = "xss.txt";
$fp = fopen($file, mode:"a+");
fwrite($fp, string:$time . "|" . $content . "\r\n");
fclose($fp)
?>