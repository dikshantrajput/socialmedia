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

    if(isset($_POST["post"])){
        if(isset($_FILES["image"]) && !empty($_FILES["image"])){
            $array = array("jpeg","jpg","png","gif");
            $ext = explode('/',$_FILES["image"]["type"]);
            $extension = $ext[1];
            $fileName  = $name.time().'.'.$extension;
            $fileSize = getimagesize($_FILES["image"]["tmp_name"]);
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

            $query = "select profile_photo from users where id=:id";
            $stmt = $connection->prepare($query);
            $result = $stmt->execute(["id"=>$id]);
            $pf = $stmt->fetch();
            if($pf["profile_photo"] != "blank.png"){
                unlink('public/media/profileImages/'.$pf["profile_photo"]);
                
            }
            $updatequery = "update users set profile_photo=:image where id=:id";
            $stmt = $connection->prepare($updatequery);
            $result = $stmt->execute(["image"=>$image,"id"=>$id]);
            if($result){
                header('location:profile.php?id='.$id);
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
        <?php if(isset($success)){echo $success;}?>
        <?php if(isset($error)){echo $error;}?>
        <div class="col-lg-12">
            <div class="form mb-5">
                <h1 class="display-6 text-center"><?php if(isset($name)){echo ucwords($name);}  ?></h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <label class="form-label">Upload profile pic</label>
                        <input type="file" name="image" class="form-control">
                        <?php if(isset($imageError)){echo $imageError;}?>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="post" class="form-control btn btn-secondary">Change</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php include "includes/footer.inc.php";?>