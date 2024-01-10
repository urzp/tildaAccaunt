<?php

    function checkEmail($email, $mysql){
        $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
        return isset($checkUser);
    }

?>