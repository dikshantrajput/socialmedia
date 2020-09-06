<?php
    session_start();
    include "includes/connection.inc.php";
    
    if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){

    }else{
        ?>
            <script>
                window.location.href="index.php";    
            </script>
        <?php
    }
    
    $id = $_POST["id"];
    $post_id = $_POST["post_id"];
    
    $query = "delete from comments where id=:id and post_id=:post_id and user_id=:user_id";
    $stmt = $connection->prepare($query);
    $result = $stmt->execute(["id"=>$id,"post_id"=>$post_id,"user_id"=>$_SESSION["user_id"]]);
    if($result){
        $updatequery = "update posts set comments=comments-1 where id=:post_id";
        $stmt1 = $connection->prepare($updatequery);
        $stmt1->execute(["post_id"=>$post_id]);
    }else{
        echo "not deleted";
    }  
?>