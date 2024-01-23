<?php
    function readUser($id, $email, $name, $token, $mysql  ){
        $sql = "UPDATE`users` SET `email`='$email', `name`='$name' WHERE `id` = '$id' AND  && `login_token`='$token'";
        $User = $mysql -> query($sql);
        $User = $User -> fetch_assoc();

        if(isset($User)){
            $result = (object) [
                'success' => true,
                'user' => $User
            ];
        }else{
             $result = (object) [
                'success' => false,
            ];           
        }

        $mysql->close();
        echo json_encode($result);
        exit();
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

    readUser($id, $email, $name, $token, $mysql );
?>