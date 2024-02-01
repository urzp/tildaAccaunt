<?php

//https://ermakpass.ru/media_node/php/islogged.php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$email = $post-> email;

include 'config.php';

$sql = "SELECT `reject_password` FROM `users` WHERE `email` = '$email'";
$checkUser = $mysql -> query($sql);
$checkUser = $checkUser -> fetch_assoc();

$result = false;
if($checkUser==1){
    $result = true;
    $sql = "UPDATE `users` SET `reject_password` = '0' WHERE `email` = '$email'";
    $mysql -> query($sql);       
}

$mysql->close();
echo json_encode($result);

?>
