<?php
    
    include "includes/connection.inc.php";
    include "includes/header.inc.php";

    if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){

    }else{
        ?>
            <script>
                window.location.href="index.php";    
            </script>
        <?php
    }
    
    //online status
    $time = time();
    $selectquery = "select * from users where last_login >= :time";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute(["time"=>$time]);
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll();
    $output='';
    if($count>0){
        foreach($data as $d){
            $output.='<li class="list-item w-100 text-warning px-2">'.ucfirst($d['name']).'</li>';
        }
    }else{
        $output.='<p class="text-center text-danger pt-2">No user is online</p>';
    }


    //notifications

    $selectquery = "select notifications.*,users.name from notifications,users where notifications.reciever = :user_id and users.id=notifications.sender and status=0";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute(["user_id"=>$_SESSION["user_id"]]);
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll();
    $notifications='';
    if($count>0){
        foreach($data as $d){
            if($d["type"]==1){
                $notifications.='<li class="list-item w-100 text-warning px-2"> '.ucwords($d['name']).' tagged you in a post</li>';
            }
        }
    }else{
        $notifications.='<p class="text-center text-danger pt-2">No new notification</p>';
    }

    //posts
    $selectquery = "select posts.*,users.name from posts,users where users.id=posts.user_id order by id desc";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute();
    $count = $stmt->rowCount();
    $data = $stmt->fetchAll();
    
    $posts='';
    foreach($data as $d){

        //likes
        $query = "select * from likes where post_id=:post_id and user_id=:user_id";
        $stmt = $connection->prepare($query);
        $stmt->execute(["post_id"=>$d["id"],"user_id"=>$_SESSION["user_id"]]);   
        if($stmt->rowCount()>0){
            $btn="Unlike";   
        }else{
            $btn =  "Like";   
        }

        //comments

        $query = "select comments.*,users.name from comments,users where post_id=:post_id and comments.user_id=users.id order by id desc";
        $stmt = $connection->prepare($query);
        $stmt->execute(["post_id"=>$d["id"]]);   
        $row = $stmt->fetchAll();
        $comments = '';
        foreach($row as $r){
            $comments .= '<div class="pl-3 m-1">
                            <p class="m-0"><strong>'.ucwords($r["name"]).': </strong>'.$r["text"].'<span class="w-100 ml-5 bg-light"></span></p>
                            <p>'.$r["added_on"].' <a id="delete_cmt_btn" data-id="'.$r["id"].'"  data-post="'.$d["id"].'" class="ml-5" style="cursor:pointer;" ><i class="fa fa-trash text-danger"></i></a></p>
                        </div>';
        }
    
        $posts .= '<div class="row m-0 my-3 mb-5">
                        <div class="col-lg-12 p-0">
                            <div class="card">';
                            if($d["tag"]==0){
                                $posts.='<div class="card-header"><h5>'.ucwords($d["name"]).'</h5><small class="ml-auto">'.$d["added_on"].'</small>';
                            }else{
                                $query = "select name from users where id=:id";
                                $stmt = $connection->prepare($query);
                                $stmt->execute(["id"=>$d["tag"]]);   
                                $name = $stmt->fetch();
                                $posts.='<div class="card-header"><h5>'.ucwords($d["name"]).' is with '.ucwords($name[0]).'</h5> <small class="ml-auto">'.$d["added_on"].'</small>';
                            }
                                $posts.='<p class="float-right" id="options">
                                        <i data-id="'.$d["id"].'" id="edit-post" class="fa fa-edit mr-1 text-success" style="cursor:pointer"></i>
                                        <i data-id="'.$d["id"].'" id="delete-post" class="fa fa-trash text-danger" style="cursor:pointer"></i>
                                    </p>
                                </div>
                                <div class="card-body">'.$d["text"].'</div>      
                                <div class="card-img-top"><img class="w-100" src="public/media/postImages/'.$d["image"].'"></div>
                                <div class="card-footer">
                                <p class="pl-2 d-inline mr-2">
                                    <span id="likes_counter" data-id="'.$d["id"].'">0</span> likes
                                </p>
                                <p class="p-0 d-inline"><span id="comment_counter" data-id="'.$d["id"].'">0</span> comments</p>
                                    <div class="buttons mt-2">
                                        <a id="like_btn" data-id="'.$d["id"].'" class="px-3 py-1" style="cursor:pointer;box-shadow:0px 2px 4px 0px rgba(0,0,0,0.2);text-decoration:none;color:black;background-color:white">'.$btn.'</a>
                                        <a id="cmt" data-toggle="modal" data-target="#comment_modal" data-id="'.$d["id"].'" class="px-3 py-1" style="cursor:pointer;box-shadow:0px 2px 4px 0px rgba(0,0,0,0.2);text-decoration:none;color:black;background-color:white">Comment</a>      
                                    </div>
                                </div>
                                <div class="comment p-0">
                                '.$comments.'
                                </div>
                            </div>
                        </div>
                    </div>';
    }
    
?>


<!-- comment modal  -->
<div class="modal" tabindex="-1" id="comment_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Comment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="post_comment.php" method="post">
            <div class="form-group">
                <label class="form-label">Write your comment here:</label>
                <input type="text" id="text" class="form-control">
            </div>
        </form>   
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" id="comment_btn" class="btn btn-primary">Comment</button>
      </div>
    </div>
  </div>
</div>
<!-- comment modal ends  -->


<div class="container">
    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="post-div">
                <a href="newpost.php" class="btn btn-primary">Post</a>
                <?php if(isset($posts)){echo $posts;} ?>
            </div>
        </div>

        <div class="ml-auto p-0 col-lg-3">
        <a href="messenger/messages.php" class="w-100 mb-2 btn btn-primary text-center">Messages</a>
            <div class="card my-3">
                <h5 class="text-center card-header">Notifications</h5>
                <a class="btn btn-dark my-2 mx-auto w-50" id="read_notifications">Mark as read</a>
                <ul class="list p-0 pt-2" style="list-style:none;">
                    <?php if(isset($notifications)){echo $notifications;} ?>
                </ul>    
            </div>
            <div class="card my-2">
                <h5 class="text-center card-header">Online Users</h5>
                <ul class="list p-0 pt-2" style="list-style:none;">
                    <?php if(isset($output)){echo $output;} ?>
                </ul>    
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(()=>{

//count likes
        const count_likes = (id)=>{
            $.ajax({
                url:'count_likes.php',
                type:'post',
                data:{id:id},
                success:(data)=>{ 
                    const likes_counter = document.querySelectorAll("#likes_counter");
                    likes_counter.forEach((like)=>{
                        if(id == like.dataset.id){
                            like.innerHTML = (data)
                        }
                    })
                }
            })
        }

        //count comments

        const count_comments = (id)=>{
            $.ajax({
                url:'count_comments.php',
                type:'post',
                data:{id:id},
                success:(data)=>{ 
                    const comments_counter = document.querySelectorAll("#comment_counter");
                    comments_counter.forEach((comment)=>{
                        if(id == comment.dataset.id){
                            comment.innerHTML = (data)
                        }
                    })
                }
            })
        }
        <?php
            $selectquery = "select post_id from likes";
            $stmt = $connection->prepare($selectquery);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
            foreach($data as $d){
                ?>  
                count_likes(<?php echo $d["post_id"]?>);
                count_comments(<?php echo $d["post_id"]?>);
                <?php
            }
            ?>

        

        setInterval(()=>{
            updateStatus()
        },3000)
        const updateStatus = ()=>{
            $.ajax({
            url:'messenger/updateOnlineOffline.php',
            type:'post',
            success:(data)=>{}
            })
        }

//like functionality and no of likes

        const like_post = (id)=>{
            $.ajax({
                url:'like_post.php',
                type:'post',
                data:{id:id},
                success:(data)=>{
                    const like_btn = document.querySelectorAll("#like_btn");
                    like_btn.forEach((like)=>{
                        if(id == like.dataset.id){
                            like.innerHTML = (data)
                        }
                    })
                }
            })
            count_likes(id);
        }


        //read notifications

        $(document).on("click","#read_notifications",()=>{
            $.ajax({
                url:'hide_notifications.php',
                type:'post',
            })            
            location.reload();
        })
        

        //comment functionality

        const post_comment = (id,text)=>{
            $.ajax({
                url:'post_comment.php',
                type:'post',
                data:{text:text,id:id},
            })
            count_comments(id);
        }

        const delete_post = (id)=>{
            $.ajax({
                url:'delete_post.php',
                type:'post',
                data:{id:id},
                success:(data)=>{
                }
            })
        }


        const delete_comment = (id,post_id)=>{
            $.ajax({
                url:'delete_comment.php',
                type:'post',
                data:{id:id,post_id:post_id},
                success:(data)=>{
                }
            })
            count_comments(post_id);
        }



        $(document).on("click","#like_btn",(e)=>{
            let id = e.target.dataset.id;
            like_post(id);
        })

        $(document).on("click","#edit-post",(e)=>{
            let id = e.target.dataset.id;
            window.location.href="updatepost.php?id="+id
        })

        $(document).on("click","#delete-post",(e)=>{
            let id = e.target.dataset.id;
            delete_post(id);
            location.reload();
        })


        $(document).on("click","#cmt",(e)=>{
            let id = e.target.dataset.id
            $(document).on("click","#comment_btn",()=>{
                let text = $('#text').val();
                post_comment(id,text);
                location.reload();
            })
        })

        $(document).on("click","#delete_cmt_btn",(e)=>{
            let id = e.target.parentElement.dataset.id
            let post_id = e.target.parentElement.dataset.post
            delete_comment(id,post_id);
            location.reload();
        })

    })
</script>

<?php 
include "includes/footer.inc.php";
?>