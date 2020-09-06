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
    
    $id = $_GET["id"];

    $query = "select posts.*,users.name from posts,users where posts.user_id=users.id and user_id=:user_id order by id desc limit 1";
        $stmt = $connection->prepare($query);
        $stmt->execute(["user_id"=>$_SESSION["user_id"]]);   
    $output = '';
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll();
        foreach($data as $d){
            $output.='<div class="card mb-4">
                    <div class="card-img-top">
                        <img src="public/media/postImages/'.$d["image"].'" class="w-100">
                    </div>
                    <div class="card-header">
                        <p class="card-title">'.ucwords($d["name"]).'</p>
                        <p class="card-title">'.($d["added_on"]).'</p>
                    </div>
                    <div class="card-body">
                        <p>'.$d["text"].'</p>
                    </div>  
                </div>';
        }
        
    }else{
        $output.='<div class="card">
                    <div class="card-header">
                        <p class="card-title">No posts yet</p>
                    </div>
                </div>';
    }


    $selectquery = "select * from posts where id=:id";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute(["id"=>$id]);
    $data = $stmt->fetch();
    $text = $data["text"];
    $image = $data["image"];

    if(isset($_POST["post"])){
        if(isset($_POST["text"]) && !empty($_POST["text"])){
            $text = $_POST["text"];
        }else{
            $textError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            This field can\'t be empty
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }

        if(isset($_FILES["image"])){
            $array = array("jpeg","jpg","png","gif");
            $ext = explode('/',$_FILES["image"]["type"]);
            $extension = $ext[1];
            $fileName  = time().'.'.$extension;
            if(in_array($extension,$array)){
                if(move_uploaded_file($_FILES["image"]["tmp_name"],'public/media/postImages/'.$fileName)){
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
        }

        if(isset($image) && isset($text)){
            $dateTime = date('Y-m-d h:i:s');
            $insertquery = "update posts set text=:text,image=:image,added_on=:added_on where id=:id and user_id=:user_id";
            $stmt = $connection->prepare($insertquery);
            $result = $stmt->execute(["id"=>$id,"text"=>$text,"image"=>$image,"added_on"=>$dateTime,"user_id"=>$_SESSION["user_id"]]);
            if($result){
                header('location:timeline.php');
            }else{
                $error = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            Error updating post
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
            }
        }else{
            $error = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                            Error updating post
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        }

    }

?>

<div class="container">
    <div class="row mt-5">
        <div class="col-lg-8">
            <div class="form">
            <?php if(isset($error)){echo $error;}?>
            <h1 class="display-6 text-center">Update POST </h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <label class="form-label">What's On Your Mind:</label>
                        <textarea  placeholder="Write something here..." rows="5" class="form-control" name="text" ><?php if(isset($text)){echo $text;} ?></textarea>
                        <?php if(isset($textError)){echo $textError;}?>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">Post an image</label>
                        <input type="file" name="image" class="form-control">
                        <?php if(isset($imageError)){echo $imageError;}?>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="post" class="form-control btn btn-secondary">Update Post</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-4 ml-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title text-center">Recent Posts</h5>
                </div>

                <div class="card-body">
                    <?php if(isset($output)){echo $output;}?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.inc.php";?>