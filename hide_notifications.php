<?php
include "includes/connection.inc.php";
    session_start();
    $updatequery = "update notifications set status='1' where reciever=:user_id";
    $stmt = $connection->prepare($updatequery);
    $result = $stmt->execute(["user_id"=>$_SESSION["user_id"]]);
?>