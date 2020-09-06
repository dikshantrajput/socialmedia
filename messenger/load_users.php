<?php

include "../includes/connection.inc.php";

$output = '';
    $selectquery = "select * from users";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute();
    if($stmt->rowCount()>0){
        $data = $stmt->fetchAll();
    }

    foreach($data as $d){
        $output .='<option value="'.$d["id"].'">'.$d["name"].'</option>';
    }
    echo $output;
?>