<?php

//https://ermakpass.ru/media_node/php/islogged.php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$email = $post-> email;
$token = $post -> token;

include 'db_mysql.php';

$sql = "SELECT `id`, `name`, `email` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
$checkUser = $mysql -> query($sql);
$checkUser = $checkUser -> fetch_assoc();

$result = false;
if(isset($checkUser)){$result = true;}

$mysql->close();
echo json_encode($result);

?>
