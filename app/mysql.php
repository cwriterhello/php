<?php
$host = 'localhost';
$port = 3306;
$username = 'root';
$password = '1234';
$dbname = 'heimadianping';

$conn = new mysqli($host, $username, $password, $dbname, $port);

if($conn->connect_error){
    die('连接失败:'.$conn->connect_error);
}

echo '连接成功';
