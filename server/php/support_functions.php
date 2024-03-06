<?php

function checkToken($email, $token, $mysql){
    $sql = "SELECT `id`, `name`, `email` FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();

    $result = false;
    if(isset($checkUser)){$result = true;}

    return $result;
}

function push_log($text, $scypt_php = '', $log_file = 'log'){
    $log = date('Y-m-d H:i:s').' '.$scypt_php . ' '.$text;
    file_put_contents(__DIR__ . '/'.$log_file.'.txt', $log . PHP_EOL, FILE_APPEND);
}

?>