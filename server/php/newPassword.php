<?php 

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);

$code = $post-> code;
$email = $post-> email;
$password = $post -> password;

if($code==''||$email==''||$password==''){
    sleep(2);
    $result = (object) [
        'success' => false,
    ];
    echo json_encode($result);   
    exit();
}

include 'db_mysql.php';

$sql = "SELECT `id` FROM `users` WHERE `resetpassword`='$code'";
$user = $mysql -> query($sql);
$user = $user-> fetch_assoc();
$check = isset($user);

if(!$check){
    sleep(2);
    $result = (object) [
        'success' => false,
    ];
    echo json_encode($result);   
    exit();
}

$password = md5($password."wqrtvfd");

$sql = "UPDATE `users` SET `password` = '$password' WHERE `id` = '$user[id]' AND `email`='$email'";
$mysql -> query($sql);
$result = (object) [
	'success' => true,
];
echo json_encode($result);

?>