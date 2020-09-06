<?php
    session_start();
    include "includes/connection.inc.php";

    $id = $_POST["id"];
    
    $query = "select * from likes where post_id=:post_id and user_id=:user_id";
    $stmt = $connection->prepare($query);
    $stmt->execute(["post_id"=>$id,"user_id"=>$_SESSION["user_id"]]);   
    if($stmt->rowCount()>0){
        echo "Like";   
    }else{
        echo "Unlike";   
    }
    
?>