<?php

include 'config_timer.php';
include 'modules/crud.php';
include 'modules/functions.php';
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);

$sessionToken = $post -> sessionToken;

$data['wheel'] = rand(0,10);
if($data['wheel'] > 5){$data['wheel'] = ceil($data['wheel']/2); }
if($data['wheel']==0){$data['wheel'] = rand(0,5);}

if($data['wheel']==0){
    $selector = "`sessionToken`='$sessionToken'";
    $result = crud_read('freeOrdersTimer','',$selector);
    $data['permissionKey'] = $result[0]['permissionKey'];
}

$result = (object) [
    'success' => true,
    'data' => $data,
];

echo json_encode($result);

?>