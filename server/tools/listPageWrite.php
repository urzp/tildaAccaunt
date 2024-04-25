<?php
include '../config.php';

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);

$result = (object) [
    'success' => true,
    'data' => $post,
];
echo json_encode($result);

?>

