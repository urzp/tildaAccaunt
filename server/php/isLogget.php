<?php

function checkLogin($email, $token, $mysql ){
    $sql = "SELECT `id`, `name`, `email` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();

    $result = false;
    if(isset($checkUser)){$result = true;}

    $mysql->close();
    echo json_encode($result);
    exit();
}

function checkReject($email, $mysql){
    $sql = "SELECT `reject_password`, `time_login` FROM `users` WHERE `email` = '$email'";
    $checkReject = $mysql -> query($sql);
    $checkReject = $checkReject -> fetch_assoc();
    return $checkReject;
}

function resetCheckLogin($email, $mysql){
    $sql = "UPDATE `users` SET `reject_password` = '' WHERE `email` = '$email'";
    $mysql -> query($sql); 
}

//https://ermakpass.ru/media_node/php/islogged.php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$email = $post-> email;
$token = $post -> token;
$wait_for_update = $post -> wait_for_update;

include 'config.php';
include 'support_functions.php';

if($wait_for_update!='1'){
    checkLogin($email, $token, $mysql );
}else{
    $i = 1;
    while ($i <= 5):
        $checkReject = checkReject($email, $mysql);
        $text_log = $email.' reject password '.$checkReject['reject_password'].' time '.time().'-'.$checkReject['time_login'].'='.(time() - $checkReject['time_login']);
        push_log($text_log, basename(__FILE__));
        if($checkReject['reject_password'] != '' && time() - $checkReject['time_login'] < 30){ 
            resetCheckLogin($email, $mysql);
            checkLogin($email, $token, $mysql );
        }
        sleep(2);
        $i++;
    endwhile;
    checkLogin($email, $token, $mysql );
}

?>
