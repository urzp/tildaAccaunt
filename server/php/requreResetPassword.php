<?php 

header('Access-Control-Allow-Origin: *');
include 'config.php';
include 'checkEmail.php';
include 'sendEmail.php';

$email = $_POST["email"];

$sql = "SELECT `id` FROM `users` WHERE `email`='$email'";
$check = $mysql -> query($sql);
$check = $check-> fetch_assoc();
$check = isset($check);

if(!$check){
    $result = (object) [
        'success' => true,//save cover process
    ];
    echo json_encode($result);   
    exit();
}

$reset_password = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

$sql = "UPDATE`users` SET `resetpassword`='$reset_password ' WHERE `email`='$email'";
$checkSession = $mysql -> query($sql);

sendResetPasswordEmail($email, $reset_password);

$result = (object) [
	'success' => true,
    'reset_password' => $reset_password
];
echo json_encode($result);

?>