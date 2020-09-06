<?php
    session_start();
    include "includes/connection.inc.php";

    $value = $_POST["value"];
    
    $query = "select distinct * from users where name like :value";
    $stmt = $connection->prepare($query);
    $stmt->execute(["value"=>'%'.$value.'%']);
    $users = $stmt->fetchAll();

    $query1 = "select distinct * from posts where text like :value";
    $stmt1 = $connection->prepare($query1);
    $stmt1->execute(["value"=>'%'.$value.'%']);
    $posts = $stmt1->fetchAll();
    $output='';
    if($users!=''){
        foreach($users as $user){
            $output.='<div class="card my-2">
                        <div class="card-img-top">
                            <img src="public/media/profileImages/'.$user["profile_photo"].'" class="w-100">
                        </div>
                        <h5 class="text-center card-header">User</h5>
                            <div class="card-body">
                                '.$user["name"].'
                            </div>    
                    </div>';
        }
        if($posts!=''){
            foreach($posts as $post){
                $output.='<div class="card my-2">
                            <div class="card-img-top">
                                <img src="public/media/postImages/'.$post["image"].'" class="w-100">
                            </div>
                            <h5 class="text-center card-header">Post</h5>
                                <div class="card-body">
                                    '.$post["text"].'
                                </div>  
                        </div>';
            }
    }else{
        $output.='No data found';
    }
}

    echo $output;
    
?>