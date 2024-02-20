<?php

include 'config.php';
include 'balans_functions.php';
include 'support_functions.php';
include 'provader_functions.php';

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
$token = $_POST["token"];
$paymentsystem = $_POST['paymentsystem'];
$quantity = $_POST['quantity'];
$payment = $_POST['payment'];

$payment = json_decode(str_replace('\"', "",$payment), true);
$transaction = $payment['orderid'];
$sum = $payment['amount'];
$products = $payment['products'][0];

$user = checkUser($email_user, $token, $mysql);

$oldBalans = $user['balans'];
if($paymentsystem == 'cash'){
    $newBalans = $oldBalans - $sum;
    if($newBalan < 0){ exit();}
    $data = getDataProv($_POST, $mysql);
    $result = sendOrderProvader($data);
    push_log( json_encode($result), basename(__FILE__));
}else{
    $newBalans = $oldBalans;
}
balansUpdate($user['id'], $newBalans, $mysql);
notePatment($user['id'], $paymentsystem, $transaction, $sum, $oldBalans, $newBalans, $products, $quantity, $post_email, $post_link,  $mysql);

?>