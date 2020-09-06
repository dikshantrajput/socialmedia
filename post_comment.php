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
        
        date_default_timezone_set('Asia/kolkata');
        $date  = date("Y-m-d h:i:s");
        $id = $_POST["id"];
        $text = $_POST["text"];
        $insertquery = "insert into comments(text,post_id,user_id,added_on) values(:text,:post_id,:user_id,:added_on)";

        // $commentquery = "insert into notifications(type,reciever,sender,added_on) values(:type,:reciever,:sender,:added_on)";
        // $stmt2 = $connection->prepare($commentquery);
        // $query = "select user_id from posts where id=:post_id ";
        // $stmt3 = $connection->prepare($query);
        // $stmt3->execute(["post_id"=>$id]);
        // $user = $stmt3->fetch();
        // $stmt2->execute(["type"=>2,"reciever"=>$user["id"],"sender"=>$_SESSION["user_id"],"added_on"=>$date]);


        $updatequery = "update posts set comments=comments+1 where id=:post_id";
        $stmt1 = $connection->prepare($updatequery);
        $stmt1->execute(["post_id"=>$id]);

        $stmt = $connection->prepare($insertquery);
        $result = $stmt->execute(["text"=>$text,"post_id"=>$id,"user_id"=>$_SESSION["user_id"],"added_on"=>$date]);   
?>