<?php
echo "SAPI: ".php_sapi_name()."\n\n";
echo "func_exists: ".(function_exists('stream_socket_client') ? "yes" : "no")."\n\n";
echo "disable_functions: ".ini_get('disable_functions')."\n\n";
$errno=0; $errstr='';
$s = @stream_socket_client("tcp://google.com:80", $errno, $errstr, 5);
var_dump($s, $errno, $errstr);
?>