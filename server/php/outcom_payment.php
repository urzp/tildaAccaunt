<?php

include 'config.php';
include 'balans_functions.php';
include 'support_functions.php';

function checkUser($email, $token, $mysql ){
    $sql = "SELECT `id`, `name`, `email`, balans FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();
    if(isset($checkUser)){return $checkUser;}
    exit();
}

function notePatment($id_user, $paymentsystem, $transaction, $sum, $oldBalans, $newBalans, $products, $quantity, $post_email, $post_link, $mysql){
    $sql = "INSERT INTO `out_payments` 
    (`id_user`,`paymentsystem` ,`trnsaction`, `sum`, `oldBalans`, `newBalans`, `products`, `quantity`, `form_email`, `form_link` ) 
    VALUES
    ('$id_user','$paymentsystem','$transaction', '$sum', '$oldBalans', '$newBalans','$products' ,'$quantity', '$post_email', '$post_link')";
    $mysql -> query($sql);
}


header('Access-Control-Allow-Origin: *');

push_log(json_encode($_POST), basename(__FILE__));

$post_link = $_POST['post-link'];
$post_email = $_POST['Email'];

$email_user = $_POST["email_user"];
//$email_user = 'ermak80_pass@mail.ru';

$token = $_POST["token"];
//$token = '29f9hfhfcq6uvem24gjp8c';

$paymentsystem = $_POST['paymentsystem'];
//$paymentsystem = 'cash';


$quantity = $_POST['quantity'];
//$quantity = '100';

$payment = $_POST['payment'];
//$payment = "{\"orderid\":\"1950728889\",\"products\":[\"\u041f\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u0431\u0430\u043b\u0430\u043d\u0441=100\"],\"amount\":\"100\"}";

$payment = json_decode(str_replace('\"', "",$payment), true);
$transaction = $payment['orderid'];
$sum = $payment['amount'];
$products = $payment['products'][0];

$user = checkUser($email_user, $token, $mysql);

$oldBalans = $user['balans'];
if($paymentsystem == 'cash'){
    $newBalans = $oldBalans - $sum;
}else{
    $newBalans = $oldBalans;
}
balansUpdate($user['id'], $newBalans, $mysql);
notePatment($user['id'], $paymentsystem, $transaction, $sum, $oldBalans, $newBalans, $products, $quantity, $post_email, $post_link,  $mysql);

?>