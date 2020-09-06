<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Messages</title>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.5.1.js"  ></script>

<?php 
session_start();
    if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
        include "login_navbar.inc.php";
    }else{
        include "navbar.inc.php";
    }
?>