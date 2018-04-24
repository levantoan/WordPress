<?php
$POST = json_decode(file_get_contents('php://input'), true);

if(isset($_POST) && empty($_POST)){
    $_POST = $POST;
}

$log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
    json_encode($_POST).PHP_EOL.
    json_encode($_GET).PHP_EOL.
    "-------------------------".PHP_EOL;
//Save string to log, use FILE_APPEND to append.
file_put_contents( dirname(__FILE__) . '/logs/log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
