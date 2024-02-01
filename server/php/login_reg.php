<?php
    //---------------------------------------------------------------------
    function req_user($email, $password, $mysql){
        
        if(checkEmail($email, $mysql)){return false;}
        $sql = "INSERT INTO `users` (`email`, `password` ) VALUES('$email','$password')";
        $mysql -> query($sql);
        $result = (object) [
            'success' => true,
        ];
        $sql = "SELECT `id` FROM `users` WHERE `email` = '$email'";
        $user = $mysql -> query($sql);
        $user = $user -> fetch_assoc();
        addTouserLog($user['id'], 'new user','true' ,'-' , $email , $mysql);
        $mysql->close();
        return $result;
    }

    function login($email, $password, $login_token, $mysql){
        $sql = "SELECT `id` FROM `users` WHERE `email` = '$email' && `password`='$password'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
        $time = time();
        if(isset($checkUser)){
            $userId = $checkUser['id'];
            $sql = "UPDATE `users` SET `login_token` = '$login_token', `reject_password` = 'false', `time_login`='$time' WHERE `id` = '$userId'";
            $mysql -> query($sql);
        }else{
            $sql = "UPDATE `users` SET `reject_password` = 'true', `time_login`='$time' WHERE `email` = '$email'";
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
    include 'userLog.php';
    include 'support_functions.php';
    
    push_log($_POST["requst"], basename(__FILE__));

    selectRequst($_POST, $mysql);


?>