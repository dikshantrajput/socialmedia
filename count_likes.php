<?php
    session_start();
    include "includes/connection.inc.php";

    $id = $_POST["id"];
    
    $selectquery = "select * from likes where post_id=:post_id";
    $stmt = $connection->prepare($selectquery);
    $result = $stmt->execute(["post_id"=>$id]);
    echo $stmt->rowCount();

?>