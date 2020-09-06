<?php

$server = 'localhost';
$username = 'root';
$password = '';
$db = 'messengertype';

$connection = new PDO("mysql:host=$server;dbname=$db",$username,$password);

?>