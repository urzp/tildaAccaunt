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

function notePatment($id_user, $paymentsystem, $transaction, $sum, $oldBalans, $newBalans, $products, $quantity, $post_email, $post_link, $id_provider, $prov_result, $mysql){
    $status = $prov_result -> status;
    $message = $prov_result -> message;
    $sql = "INSERT INTO `out_payments` 
    (`id_user`,`paymentsystem` ,`trnsaction`, `sum`, `oldBalans`, `newBalans`, `products`, `quantity`, `form_email`, `form_link`, `id_provider` , `provader_status`, `provader_msg` ) 
    VALUES
    ('$id_user','$paymentsystem','$transaction', '$sum', '$oldBalans', '$newBalans','$products' ,'$quantity', '$post_email', '$post_link', '$id_provider' , '$status' , '$message')";
    $mysql -> query($sql);
}

function notePatmentNotUser( $paymentsystem, $transaction, $sum, $products, $quantity, $email, $link, $id_provider, $prov_result, $mysql){
    $status = $prov_result -> status;//--
    $message = $prov_result -> message;//--
    //$status = 'none';
    //$message = 'none';
    $sql = "INSERT INTO `orders` 
    (`transaction`, `paymentsystem`, `products` , `quantity`, `sum`, `email`, `link`, `id_provider` , `provader_status`, `provader_msg` ) 
    VALUES
    ('$transaction', '$paymentsystem', '$products' ,'$quantity', '$sum', '$email', '$link', '$id_provider' ,'$status', '$message')";
    $mysql -> query($sql);
}


header('Access-Control-Allow-Origin: *');

//push_log(json_encode($_POST), basename(__FILE__), 'order_log');

if($_POST["api_k"]!=_APY_KEY_){ exit(); }

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
$id_provader = $_POST['prodavec_id'];

if(!isset($payment)){exit();}

if(!isset($_POST["token"])||!isset($_POST["email_user"])){
    $data = getDataProv($_POST, $mysql);//---
    $result = sendOrderProvader($data);//---
    //$result = '';
    
    notePatmentNotUser( $paymentsystem, $transaction, $sum, $products, $quantity, $post_email, $post_link, $id_provader, $result, $mysql);
    exit();
}

$user = checkUser($email_user, $token, $mysql);

$oldBalans = $user['balans'];
if($paymentsystem == 'cash'){
    $newBalans = $oldBalans - $sum;
    if($newBalan < 0){ exit();}
    $data = getDataProv($_POST, $mysql);
    $result = sendOrderProvader($data);
    if($result-> status == 'false'){ $newBalans = $oldBalans; }
}else{
    $newBalans = $oldBalans;
    $data = getDataProv($_POST, $mysql);
    $result = sendOrderProvader($data);
}
push_log( json_encode($result), basename(__FILE__));
balansUpdate($user['id'], $newBalans, $mysql);
notePatment($user['id'], $paymentsystem, $transaction, $sum, $oldBalans, $newBalans, $products, $quantity, $post_email, $post_link, $id_provader, $result,  $mysql);

?>