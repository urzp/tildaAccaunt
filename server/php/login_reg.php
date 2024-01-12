<?php
    //---------------------------------------------------------------------
    function req_user($email, $password, $mysql){
        if(checkEmail($email, $mysql)){return false;}
        $sql = "INSERT INTO `users` (`email`, `password` ) VALUES('$email','$password')";
        $mysql -> query($sql);
        $mysql->close();
        $result = (object) [
            'success' => true,
        ];
        return $result;
    }

    function login($email, $password, $login_token, $mysql){
        $log = date('Y-m-d H:i:s') .':  '. $email.' '.$login_token;
        file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);   
        $sql = "SELECT `id` FROM `users` WHERE `email` = '$email' && `password`='$password'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
        if(isset($checkUser)){
            $userId = $checkUser['id'];
            $sql = "UPDATE `users` SET `login_token` = '$login_token', `reject_password` = 'false' WHERE `id` = '$userId'";
            $mysql -> query($sql);
        }else{
            $sql = "UPDATE `users` SET `reject_password` = 'true' WHERE `email` = '$email'";
            $mysql -> query($sql);           
        }
    }

    function selectRequst($POST, $mysql){
        $requst = $POST["requst"];
        $email = $POST["email"];
        $login_token = $POST["login_token"];
        $password = $POST["password"];
        $password = md5($password."wqrtvfd");
        if($requst=='reg'){ req_user($email, $password, $mysql); }
        if($requst=='login'){ login($email, $password, $login_token, $mysql); }
    }
    //---------------------------------------------------------------------

    header('Access-Control-Allow-Origin: *');
    include 'db_mysql.php';
    include 'checkEmail.php';

    selectRequst($_POST, $mysql);


?>