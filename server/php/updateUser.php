<?php
    function updateUser($id, $email, $name, $token, $mysql  ){
        $sql = "SELECT `id` FROM `users` WHERE `id` = '$id' && `login_token`='$token'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
  
        if(isset($checkUser)){
            $sql = "UPDATE`users` SET `email`='$email', `name`='$name' WHERE `id` = '$id' && `login_token`='$token'";
            $res = $mysql -> query($sql);
             $result = (object) [
                'success' => true,
            ];    
        }else{
            $result = (object) [
                'success' => false,
                'msg' => 'login'
            ];       
        }

        $mysql->close();
        echo json_encode($result);
        exit();
    }

    function checkEmail($id, $email, $mysql){
        $sql = "SELECT `id` FROM `users` WHERE `id` != '$id' && `email` = '$email'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
        if(isset($checkUser)||!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result = (object) [
                'success' => false,
                'msg' => 'email'
            ];    
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
    $email = $post-> email;
    $name = $post-> name;
    $token = $post -> token;
    
    include 'db_mysql.php';
    

    checkEmail($id, $email, $mysql);
    updateUser($id, $email, $name, $token, $mysql );
?>