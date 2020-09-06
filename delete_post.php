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
    
    $query = "delete from posts where id=:post_id and user_id=:user_id";
    $stmt = $connection->prepare($query);
    $result = $stmt->execute(["post_id"=>$id,"user_id"=>$_SESSION["user_id"]]);
?>