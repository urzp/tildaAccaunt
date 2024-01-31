<?php

function balansRead($email, $mysql){
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $user = $mysql -> query($sql);
    $user = $user -> fetch_assoc();
    return $user['balans'];
}

function balansUpdate($id, $newBalans, $mysql){
    $sql = "UPDATE`users` SET `balans`='$newBalans' WHERE `id` = '$id'";
    $mysql -> query($sql);
}

function in_paymentRead($id_user, $mysql){
    $sql = "SELECT * FROM `in_payments` WHERE `id_user` = '$id_user'";
    $result = $mysql -> query($sql);
    while ($payment = $result->fetch_assoc()) {  
        $payments[] = $payment;
    }
    return $payments;
}

function out_paymentRead($id_user, $mysql){
    $sql = "SELECT * FROM `out_payments` WHERE `id_user` = '$id_user'";
    $result = $mysql -> query($sql);
    while ($payment = $result->fetch_assoc()) {  
        $payments[] = $payment;
    }
    return $payments;
}

?>