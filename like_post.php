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
    
    $query = "select * from likes where post_id=:post_id and user_id=:user_id";
    $stmt = $connection->prepare($query);
    $stmt->execute(["post_id"=>$id,"user_id"=>$_SESSION["user_id"]]);   
    if($stmt->rowCount()>0){
        $insertquery = "delete from likes where post_id=:post_id and user_id = :user_id";
        $updatequery = "update posts set likes=likes-1 where id=:post_id";
        $stmt1 = $connection->prepare($updatequery);
        $stmt = $connection->prepare($insertquery);
        $stmt->execute(["post_id"=>$id,"user_id"=>$_SESSION["user_id"]]);
        $stmt1->execute(["post_id"=>$id]);
        echo "Like";   
    }else{
        $insertquery = "insert into likes(user_id,post_id) values(:user_id,:post_id)";
        $updatequery = "update posts set likes=likes+1 where id=:post_id";
        $stmt = $connection->prepare($insertquery);
        $stmt1 = $connection->prepare($updatequery);
        $stmt->execute(["user_id"=>$_SESSION["user_id"],"post_id"=>$id]);
        $stmt1->execute(["post_id"=>$id]);
        echo "Unlike";   
    }
    
?>