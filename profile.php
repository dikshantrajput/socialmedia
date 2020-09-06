<?php
    include "includes/header.inc.php";
    include "includes/connection.inc.php";

    
    if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){

    }else{
        ?>
            <script>
                window.location.href="index.php";    
            </script>
        <?php
    }

    //select user

    $id=$_GET["id"];
    $selectquery = "select * from users where id= :id";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute(["id"=>$id]);
    $data = $stmt->fetch();
    $name=$data["name"];
    $profile_image=$data["profile_photo"];

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
            $output.='<li class="list-item w-100 text-warning px-2">'.ucwords($d['name']).'</li>';
        }
    }else{
        $output.='<p class="text-center text-danger pt-2">No user is online</p>';
    }
    

    $query = "select posts.*,users.name from posts,users where posts.user_id=users.id and posts.user_id=:user_id order by id desc";
    $stmt = $connection->prepare($query);
    $stmt->execute(["user_id"=>$_SESSION["user_id"]]);   
    $posts = '';
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll();
        foreach($data as $d){
            $posts .= '<div class="row m-0 my-3 mb-5">
                        <div class="col-lg-12 p-0">
                            <div class="card">
                                <div class="card-header"><h5>'.ucwords($d["name"]).'</h5><small class="ml-auto">'.$d["added_on"].'</small>
                                    <p class="float-right" id="options">
                                        <i data-id="'.$d["id"].'" id="edit-post" class="fa fa-edit mr-1 text-success" style="cursor:pointer"></i>
                                        <i data-id="'.$d["id"].'" id="delete-post" class="fa fa-trash text-danger" style="cursor:pointer"></i>
                                    </p>
                                </div>
                                <div class="card-body">'.$d["text"].'</div>      
                                <div class="card-img-top"><img class="w-100" src="public/media/postImages/'.$d["image"].'"></div>
                                <div class="card-footer">
                                <p class="pl-2 d-inline mr-2">
                                    <span id="likes_counter" data-id="'.$d["id"].'">'.$d["likes"].'</span> likes
                                </p>
                                <p class="p-0 d-inline"><span id="comment_counter" data-id="'.$d["id"].'">'.$d["likes"].'</span> comments</p>
                                    <div class="buttons mt-2">      
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
        }
        
    }else{
        $posts.='<div class="card">
                    <div class="card-header">
                        <p class="card-title">No posts yet</p>
                    </div>
                </div>';
    }



    if(isset($_POST["post"])){
        if(isset($_FILES["image"]) && !empty($_FILES["image"])){
            $array = array("jpeg","jpg","png","gif");
            $ext = explode('/',$_FILES["image"]["type"]);
            $extension = $ext[1];
            $fileName  = $name.time().'.'.$extension;
            if(in_array($extension,$array)){
                if(move_uploaded_file($_FILES["image"]["tmp_name"],'public/media/profileImages/'.$fileName)){
                    $image = $fileName;
                }else{
                    $imageError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                    Error uploading file 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                }
            }else{
                $imageError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            Not a valid image file
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            }
        }else{
            $imageError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Please select an image
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
        }

        if(isset($image)){
            $updatequery = "update users set profile_photo=:image where id=:id";
            $stmt = $connection->prepare($updatequery);
            $result = $stmt->execute(["image"=>$image,"id"=>$id]);
            if($result){
                $success='<div class="alert alert-success alert-dismissible fade show mt-1" role="alert">
                                Profile photo changed successfully
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }else{
                $error = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            Error updating photo
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            }
        }else{
            $error = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            Error updating photo
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }
    }
?>
<style>
    .change-div{
        position:absolute;
        top:50%;
        left:150%;
        transform:translate(-50%,-50%);
        width:100%;
        display:flex;
        justify-content:center;
        align-items:center;
        transition:all 0.3s linear;
    }
    .img-div{
        overflow:hidden;
        position:relative;
    }
    .img-div:hover > .change-div{
        left:50%;
    }
    .img-div::before{
        content:'';
        background:rgba(0,0,0,0.2);
        width:100%;
        height:100%;
        position:absolute;
        top:0;
        left:0;
        cursor:pointer;
    }
    .change-div a:hover{
        color:yellow !important;
    }
</style>
<div class="container">
    <div class="row mt-3">
        <?php if(isset($success)){echo $success;}?>
        <?php if(isset($error)){echo $error;}?>
        <div class="col-lg-8">
            <div class="form mb-5">
                <div class="profile-photo">
                    <div class="img-div mx-auto text-center" style="max-width:200px;height:200px;border-radius:50%;">
                        <img src="public/media/profileImages/<?php echo $profile_image; ?>"  style="cursor:pointer;max-width:200px;height:200px;border-radius:50%" alt="profile_photo">
                        <div class="change-div p-3"><a class="text-light text-decoration-none" href="changeProfile.php?id=<?php echo $id; ?>"><p class="p-0 m-0">Change profile photo</p></a></div>
                    </div>
                </div>
                <h1 class="display-6 text-center"><?php if(isset($name)){echo ucwords($name);}  ?></h1>
            </div>
            <div class="post-div">
                <?php if(isset($posts)){echo $posts;} ?>
            </div>
        </div>

        <div class="col-lg-4 ml-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Online Users</h5>
                </div>
                <div class="card-body">
                    <?php if(isset($output)){echo $output;}?>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
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

        const delete_post = (id)=>{
            $.ajax({
                url:'delete_post.php',
                type:'post',
                data:{id:id},
            })
        }

        $(document).on("click","#edit-post",(e)=>{
            let id = e.target.dataset.id;
            window.location.href="updatepost.php?id="+id
        })

        $(document).on("click","#delete-post",(e)=>{
            let id = e.target.dataset.id;
            delete_post(id);
            location.reload();
        })
</script>
<?php include "includes/footer.inc.php";?>