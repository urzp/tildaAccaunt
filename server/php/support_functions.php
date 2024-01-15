<?php

function checkToken($email, $token, $mysql){
    $sql = "SELECT `id`, `name`, `email` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();

    $result = false;
    if(isset($checkUser)){$result = true;}

    return $result;
}

?>