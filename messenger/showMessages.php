<?php
session_start();
include "../includes/connection.inc.php";
    $id= $_SESSION["user_id"];
    $selectquery = "select * from messages where to_id='$id' and status='0'";
    $output='';
    $stmt = $connection->prepare($selectquery);
    $stmt->execute();
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll();
        foreach($data as $d){
            $id = $d["from_id"];
            $selectquery = "select * from users where id=$id";
            $stmt = $connection->prepare($selectquery);
            $stmt->execute();
            if($stmt->rowCount()>0){
                $user = $stmt->fetch();
                $from_name = $user["name"];
            }
            $output .= "<div class='text-justify' style='overflow-wrap: break-word;'><p class='mr-2'>From ".$from_name.":</p><p class='text-primary'>".$d["message"]."</p></div>";
        }
        echo $output;
    }else{
        echo "no messages yet";
    }
?>