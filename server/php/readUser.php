<?php
    function readUser($email, $token, $mysql ){
        $sql = "SELECT `id`, `name`, `email`, `phone`, `balans` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
        $User = $mysql -> query($sql);
        $User = $User -> fetch_assoc();

        if(isset($User)){
            $payments = in_paymentRead($User['id'], $mysql);
            $orders = out_paymentRead($User['id'], $mysql);
            $result = (object) [
                'success' => true,
                'user' => $User,
                'payments' => $payments,
                'orders' => $orders
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
    
    include 'config.php';
    include 'balans_functions.php';

    readUser($email, $token, $mysql );
?>