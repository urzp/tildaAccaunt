<?php

include 'config.php';
include 'support_functions.php';
include 'balans_functions.php';

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$email = $post-> email;
$token = $post -> token;


if(!checkToken($email, $token, $mysql)){
    $result = (object) [
		'success' => false,
		'error' => 'token',
	];
    echo json_encode($result);
    exit();
}

$balans = balansRead($email, $mysql);
$result = (object) [
    'success' => true,
    'balans' => $balans,
];
echo json_encode($result);

?>