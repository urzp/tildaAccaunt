<?php
include 'config_timer.php';
include 'modules/crud.php';
include 'modules/functions.php';
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);

$sessionToken = $post -> sessionToken;

$selector = "`sessionToken`='$sessionToken'";

$data = crud_read('freeOrdersTimer','',$selector);

$keyPermision ='';
if(empty($data)){
    $data['permissionKey'] = generateRandomString();
    $data['sessionToken']=$sessionToken;
    crud_create('freeOrdersTimer', $data);
}else{
    if(checkTimout(3, $data[0]['date_time'])){ 
        $keyPermision = $data[0]['permissionKey'];
    }
}

$result = (object) [
    'success' => true,
    'data' => $keyPermision,
];

echo json_encode($result);

?>