<?php
include "../includes/connection.inc.php";
    session_start();
    $to_id = $_SESSION["user_id"];
    
    $updatequery = "update messages set status='1' where to_id='$to_id'";
    $stmt = $connection->prepare($updatequery);
    $result = $stmt->execute();
    if($result){
        echo "message sent";
    }else{
        echo "message not sent";
    }


?>