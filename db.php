<?php

// INIT DB CONNECTION
$db_hostname = 'localhost';
$db_username = '1wire';
$db_password = 'c5r3d5pRSWTzfTXV';
$db_name     = '1wire';
$link        = mysqli_connect($db_hostname, $db_username, $db_password, $db_name) or die("Error " . mysqli_error($link));

?>