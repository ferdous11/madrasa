<?php

$server = ($_SERVER['HTTP_HOST']);
$serverip = ($_SERVER['SERVER_ADDR']);
echo $server;
echo $serverip.'<br/>';
$curdir = dirname($_SERVER['REQUEST_URI']) . "/";
echo $curdir;
?>