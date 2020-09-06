<?php
include "includes/connection.inc.php";
include "includes/header.inc.php";


    if(isset($_POST["submit"])){
        $username = $_POST["username"]; 
        $password = $_POST["password"]; 
        $selectquery = "select * from users where username=:username";
        $stmt = $connection->prepare($selectquery);
        $stmt->execute(["username"=>$username]);
        $count = $stmt->rowCount();
        $data = $stmt->fetch();
        if($count>0){
            if(password_verify($password,$data["password"])){
                $_SESSION["user_id"] = $data["id"];
                $_SESSION["username"] = $data["name"];
                header('location:timeline.php');
            }else{
                $passwordError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                Wrong Password
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
            }
        }else{
            $usernameError = '<div class="alert alert-danger alert-dismissible fade show mt-1" role="alert">
                                User doesn\'t exists
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>';
        }
    }
?>
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v8.0&appId=336609257694283&autoLogAppEvents=1" nonce="ANkHRkZe"></script>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto my-5">
            <div class="login-div">
            <div class="fb-login"><a href="#" onlogin="checkLoginState();" class="w-100 btn btn-primary">Login with facebook</a></div>
            <form action="" method="post">
                <h1 class="text-center">LOGIN</h1>
                    <label class="form-label">Username:</label>
                    <input type="text" class="form-control mb-2" name="username">
                    <?php if(isset($usernameError)){echo $usernameError;}?>
                    <label class="form-label">Password:</label>
                    <input type="password"  class="form-control mb-2"  name="password">
                    <?php if(isset($passwordError)){echo $passwordError;}?>
                    <input type="submit" class="form-control btn btn-primary" name="submit" value="Login">
                </form>
                <small><p class="mt-1 text-center">Don't have an account? <a href="register.php">register here</a></p></small>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(()=>{
            $('.login').removeClass('text-light');
            $('.login').css('color','yellow');
        })
    </script>
<?php 
include "includes/footer.inc.php";
?>