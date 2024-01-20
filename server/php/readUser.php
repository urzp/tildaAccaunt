<?php
    function readUser($email, $token, $mysql ){
        $sql = "SELECT `id`, `name`, `email`, `balans` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
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
    
    
    $email = $post-> email;
    $token = $post -> token;
    
    include 'db_mysql.php';

    readUser($email, $token, $mysql );
?>