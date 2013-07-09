<?php

require("torSocket.php");

$ip = "94.142.241.111";
$port = 23;

$torSock = new TorSocket($ip, $port);
$socket = $torSock->handler;

while(!feof($socket)) {
    echo fread($socket,1);
}

?>
