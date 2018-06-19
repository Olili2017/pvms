<?php

$conn = new mysqli("test.pidscrypt.com","root","", "ibanda");

if(!$conn){ 
    echo ("you can not connect");
}

var_dump($conn);

echo $conn->host_info;

?>