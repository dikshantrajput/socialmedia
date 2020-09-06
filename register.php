<?php

include "includes/connection.inc.php";
include "includes/header.inc.php";


    if(isset($_POST["submit"])){
        //valid username and it doesn't exists in db
        if(isset($_POST["username"]) && !empty($_POST["username"])){
            if(preg_match('/^[a-zA-Z0-9]{3,20}$/',$_POST["username"])){
                $selectquery = "select username from users where username=:username";
                $stmt = $connection->prepare($selectquery);
                $stmt->execute(["username"=>$_POST["username"]]);
                if($stmt->rowCount()>0){
                    $usernameError='<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                        Username already exists 
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>'; 
                }else{
                    $username = $_POST["username"];
                }
            }else{
                $usernameError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                    Username must be more than 3 characters long 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>'; 
            }
        }else{
            $usernameError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Please enter your username
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'; 
        } 
        
        //validate password

        if(isset($_POST["password"]) && !empty($_POST["password"])){
            if(preg_match('/^.{6,}$/',$_POST["password"])){
                $password = $_POST["password"];
            }else{
                $passwordError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                    Password must be more than 6 characters long 
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>'; 
            }
        }else{
            $passwordError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Please enter a password
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'; 
        } 

        //confirm password

        if(isset($_POST["cpassword"]) && !empty($_POST["cpassword"])){
            if($_POST["password"] === $_POST["cpassword"]){
                $cpassword = $_POST["cpassword"];
            }else{
                $cpasswordError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Password doesn\'t match
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'; 
            }
        }else{
            $cpasswordError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Please enter confirm password
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>'; 
        } 

        if(isset($username) && isset($password) && isset($cpassword)){
            $selectquery = "insert into users(username,password,name) values(:username,:password,:username)";
            $stmt = $connection->prepare($selectquery);
            $stmt->execute(["username"=>$username,"password"=>password_hash($password,PASSWORD_BCRYPT)]);
            $count = $stmt->rowCount();
            $data = $stmt->fetch();
            if($count>0){
                $_SESSION["user_id"] = $data["id"];
                $_SESSION["username"] = $data["name"];
                header('location:index.php');
            }
        }else{
            $error = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Wrong credentials
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
        }
        
    }
?>


    <div class="container">
        <div class="row">
            <div class="col-lg-6 mx-auto my-5">
                <div class="login-div">
                <?php if(isset($error)){echo $error;}?>
                    <form action="" method="post">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control mb-2" name="username">
                        <?php if(isset($usernameError)){echo $usernameError;}?>
                        <label class="form-label">Password:</label>
                        <input type="password"  class="form-control mb-2"  name="password">
                        <?php if(isset($passwordError)){echo $passwordError;}?>
                        <label class="form-label">Confirm Password:</label>
                        <input type="password"  class="form-control mb-2"  name="cpassword">
                        <?php if(isset($cpasswordError)){echo $cpasswordError;}?>
                        <input type="submit" class="form-control btn btn-primary" name="submit" value="Register">
                    </form>
                    <small><p class="mt-1 text-center">Already have an account? <a href="index.php">login now</a></p></small>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(()=>{
            $('.register').removeClass('text-light');
            $('.register').css('color','yellow');
        })
    </script>  
<?php 
include "includes/footer.inc.php";
?>