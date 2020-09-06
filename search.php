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
                                    <span id="likes_counter" data-id="'.$d["id"].'">0</span> likes
                                </p>
                                <p class="p-0 d-inline"><span id="comment_counter" data-id="'.$d["id"].'">0</span> comments</p>
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

<div class="container">
    <div class="row mt-3">
        <div class="col-lg-8">
            <div class="form mb-5">
                <form action="" method="post" id="form">
                    <div class="form-group">
                        <label class="form-label">Search for User/Post:</label>
                        <input type="text" id="search" class="form-control" name="search">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8" id="output">

        </div>
    </div>
</div>



<script>

    $(document).ready(()=>{
        $('#search').on('keyup',(e)=>{
            let value = e.target.value
            $.ajax({
                url:'search_keyword.php',
                type:'post',
                data:{value:value},
                success:(data)=>{
                    $('#output').html(data)
                }
            })
        })
            $('.search').removeClass('text-light');
            $('.search').css('color','yellow');
    })
</script>
<?php include "includes/footer.inc.php";?>