<?php

function getUser($id_user, $mysql){
     $sql = "SELECT `id`, `name`, `email`, `balans` FROM `users` WHERE `id` = '$id_user'";
     $user = $mysql -> query($sql);
     $user = $user -> fetch_assoc();
     return $user;
}

function notePatment($id_user, $trnsaction, $sum, $oldBalans, $newBalans, $mysql){
     $sql = "INSERT INTO `in_payments` 
     (`id_user`, `trnsaction`, `sum`, `oldBalans`, `newBalans` ) 
     VALUES
     ('$id_user','$trnsaction', '$sum', '$oldBalans', '$newBalans')";
     $mysql -> query($sql);
 }


header('Access-Control-Allow-Origin: *');


$email = $_POST["email"];
$id = $_POST["id"];
$id_user = 4;
$payment = "{\"orderid\":\"2024788416\",\"products\":[\"\u041f\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u0431\u0430\u043b\u0430\u043d\u0441=100\"],\"amount\":\"100\"}";
$payment = json_decode(str_replace('\"', "",$payment), true);

$log = date('Y-m-d H:i:s') . ' '.json_encode($payment);
file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

$transaction = $payment['orderid'];
$sum = $payment['amount'];


include 'db_mysql.php';
include 'balans_functions.php';

$user = getUser($id_user, $mysql);


$oldBalans = $user['balans'];
$newBalans = $oldBalans + $sum;



balansUpdate($id_user, $newBalans, $mysql);
notePatment($id_user, $transaction, $sum, $oldBalans, $newBalans, $mysql);

?>