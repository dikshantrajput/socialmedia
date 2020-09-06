<?php
include "../includes/connection.inc.php";
session_start();
    $table ='<tr>
                <th>Username</th>
                <th>Status</th>
            </tr>';

            if(isset($_POST["page"])){
                $per_page = 4;
                $page = $_POST["page"];
                $page = (($page-1)*$per_page);
            }else{
                $page =  0;
            }
            
$selectquery ="select * from users limit $page,4";
$stmt = $connection->prepare($selectquery);
$stmt->execute();
if($stmt->rowCount()>0){
    $data = $stmt->fetchAll();
}

$time = time()+10;
$time1 = time();
$msg = "offline";
foreach($data as $d){
    if($d["last_login"]>$time1){
        $msg = "online";
        $class="btn btn-success bg-success";
    }else{
        $msg = "offline";
        $class="btn btn-danger bg-danger";
    }
    $table.=  '<tr>
                <td>'.$d["name"].'</td>
                <td><button class="'.$class.'">'.$msg.'</button></td>
            </tr>' ;
    
}
$id = $_SESSION["user_id"];
$insertquery = "update users set last_login='$time' where id=$id";
$stmt = $connection->prepare($insertquery);
$stmt->execute();

echo $table;