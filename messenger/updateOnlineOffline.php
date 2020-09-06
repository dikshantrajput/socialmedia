<?php
include "../includes/connection.inc.php";
session_start();
$time = time()+10;
$id = $_SESSION["user_id"];
$updatequery = "update users set last_login='$time' where id=$id";
$stmt = $connection->prepare($updatequery);
$stmt->execute();
?>