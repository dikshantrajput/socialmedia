<?php
include "../includes/connection.inc.php";
    session_start();
    $from_id = $_SESSION["user_id"];
    $message = $_POST["message"];
    $to_id = $_POST["to_id"];
    
    $insertquery = "insert into messages (from_id,to_id,message) values ('$from_id','$to_id','$message')";
    $stmt = $connection->prepare($insertquery);
    $result = $stmt->execute();
    if($result){
        echo "message sent";
    }else{
        echo "message not sent";
    }


?>