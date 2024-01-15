<?php

function balansRead($email, $mysql){
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $user = $mysql -> query($sql);
    $user = $user -> fetch_assoc();
    return $user['balans'];
}

function balansUpdate(){

}

?>