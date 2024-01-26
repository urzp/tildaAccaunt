<?php

function passwordValid($password){
    if($password==''){
        $result = (object) [
            'success' => false,
            'msg' => 'valid password'
        ];   
        echo json_encode($result);
        exit();       
    }
}

function checkToken($id, $token, $mysql){

    $sql = "SELECT `id`, `email`, `name` FROM `users` WHERE `id` = '$id' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();

    if(!isset($checkUser)){
        $result = (object) [
            'success' => false,
            'msg' => 'login'
        ];   
        $mysql->close();
        echo json_encode($result);
        exit();
    }

}



function updatePassword($id, $new_password, $old_password,  $mysql  ){
    $sql = "UPDATE`users` SET `password`='$new_password' WHERE `id` = '$id'";
    $mysql -> query($sql);
    
    $oldData = "password: ".$old_password;
    $newData = "password: ".$new_password;
    addTouserLog($id, 'update user password','true' ,$oldData ,$newData , $mysql);

    $result = (object) [
        'success' => true,
    ];  
    
    $mysql->close();
    echo json_encode($result);
    exit();
}

function checkPassword($id, $old_password, $mysql){
    $sql = "SELECT `id` FROM `users` WHERE `id` = '$id' && `password`='$old_password'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();
    if(!isset($checkUser)){
        $result = (object) [
            'success' => false,
            'msg' => 'password',
        ];
        $oldData = "-";
        $newData = "old_password: ".$old_password;            
        addTouserLog($id, 'update password','false' ,$oldData ,$newData , $mysql);
        $mysql->close();
        echo json_encode($result);
        exit();        
    }
}

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$post = file_get_contents("php://input");
$post = json_decode($post);


$id = $post-> id;
$old_password = $post-> old_password;
$new_password = $post-> new_password;
$token = $post -> token;

passwordValid($old_password);
passwordValid($new_password);
$old_password = md5($old_password."wqrtvfd");
$new_password = md5($new_password."wqrtvfd");

include 'db_mysql.php';
include 'userLog.php';

checkToken($id, $token, $mysql);
checkPassword($id, $old_password, $mysql);
updatePassword($id, $new_password, $old_password, $mysql );

?>