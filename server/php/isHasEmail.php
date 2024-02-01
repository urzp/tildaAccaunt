<?php

//https://ermakpass.ru/media_node/php/islogged.php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$email = $post-> email;

include 'config.php';
include 'checkEmail.php';

$result = checkEmail($email, $mysql);


$mysql->close();
echo json_encode($result);

?>