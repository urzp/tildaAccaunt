<?php

include 'config.php';
include 'balans_functions.php';
include 'support_functions.php';
include 'provader_functions.php';

function CheckSumOrder($cardsProduct_id, $quantity, $sum){
    global $mysql;
    $sql = "SELECT `value` FROM `config` WHERE `name`='checkSumOrder' ";
    $isChekingSum = $mysql -> query($sql);
    $isChekingSum = $isChekingSum -> fetch_assoc();
    $isChekingSum = $isChekingSum['value'];
    if($isChekingSum == '0') return 'noCheck by settings';
    if($cardsProduct_id == '') return 'noCheck by nodata card id';
    $sql = "SELECT * FROM `cardsProduct` WHERE `id`= '$cardsProduct_id' ";
    $cardData = $mysql -> query($sql);
    $cardData = $cardData -> fetch_assoc();
    $priceData = round($cardData['price'], 2);
    $sumData = $priceData * $quantity;
    if( round($sum) != round($sumData) ) return 'false: countSum='.$sumData.' orderSum='.$sum.' priceBD='.$priceData;
    return 'true';
}

function checkUser($email, $token, $mysql ){
    $sql = "SELECT `id`, `name`, `email`, balans FROM `users` WHERE `email` = '$email' && `login_token`='$token'";
    $checkUser = $mysql -> query($sql);
    $checkUser = $checkUser -> fetch_assoc();
    if(isset($checkUser)){return $checkUser;}
    exit();
}

function notePatment($id_user, $paymentsystem, $transaction, $sum, $checkSum, $oldBalans, $newBalans, $cardsProduct_id , $products, $quantity, $post_email, $post_link, $id_provider, $prov_result, $mysql){
    $status = $prov_result -> status;
    $message = $prov_result -> message;
    $sql = "INSERT INTO `out_payments` 
    (`id_user`,`paymentsystem` ,`trnsaction`, `sum`, `checkSum` , `oldBalans`, `newBalans`, `cardsProduct_id` ,`products`, `quantity`, `form_email`, `form_link`, `id_provider` , `provader_status`, `provader_msg` ) 
    VALUES
    ('$id_user','$paymentsystem','$transaction', '$sum', '$checkSum', '$oldBalans', '$newBalans', '$cardsProduct_id' ,'$products' ,'$quantity', '$post_email', '$post_link', '$id_provider' , '$status' , '$message')";
    //push_log(json_encode($sql), basename(__FILE__), 'order_log');
    $mysql -> query($sql);
}

function notePatmentNotUser( $paymentsystem, $transaction, $sum, $checkSum , $cardsProduct_id, $products, $quantity, $email, $link, $id_provider, $prov_result, $mysql){
    $status = $prov_result -> status;//--
    $message = $prov_result -> message;//--
    //$status = 'none';
    //$message = 'none';
    $sql = "INSERT INTO `orders` 
    (`transaction`, `paymentsystem`, `cardsProduct_id` ,`products` , `quantity`, `sum`, `checkSum` , `email`, `link`, `id_provider` , `provader_status`, `provader_msg` ) 
    VALUES
    ('$transaction', '$paymentsystem', '$cardsProduct_id' , '$products' ,'$quantity', '$sum', '$checkSum', '$email', '$link', '$id_provider' ,'$status', '$message')";
    //push_log(json_encode($sql), basename(__FILE__), 'order_log');
    $mysql -> query($sql);
    
}

//---------------------------------------------------- BEGIN -------------------------------------------------------------

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
$typeData = $_POST['typeData'];

if($typeData=="new"){
    $id_page = $_POST['id_page'];
    $card_number = $_POST['card_number'];
    $sql = "SELECT * FROM `cardsProduct` WHERE `id_page`='$id_page' AND `number_in_page`='$card_number' ";
    $cardData = $mysql -> query($sql);
    $cardData = $cardData -> fetch_assoc();
    $_POST['type'] = $cardData['type'];
    $_POST['prodavec_id'] = $cardData['id_provider'];
    $_POST["service"] = $cardData['id_servis'];
    $cardsProduct_id = $cardData['id'];
    $sql = "SELECT * FROM `cardProductParams` WHERE `id_card` = $cardsProduct_id ";
    $cardPrams = $mysql -> query($sql);
    foreach( $cardPrams as $item ){
        $_POST[$item['name']]=$item['value'];
    } 
}

$id_provader = $_POST['prodavec_id'];

$checkSum = CheckSumOrder($cardsProduct_id, $quantity, $sum);
//push_log(json_encode($checkSum), basename(__FILE__), 'order_log');

if(!isset($payment)){exit();}

if(!isset($_POST["token"])||!isset($_POST["email_user"])){
    $data = getDataProv($_POST, $mysql);//---
    $result = sendOrderProvader($data);//---
    //$result = '';
    
    notePatmentNotUser( $paymentsystem, $transaction, $sum, $checkSum , $cardsProduct_id, $products, $quantity, $post_email, $post_link, $id_provader, $result, $mysql);
    echo json_encode($result);
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
notePatment($user['id'], $paymentsystem, $transaction, $sum, $checkSum, $oldBalans, $newBalans, $cardsProduct_id ,$products, $quantity, $post_email, $post_link, $id_provader, $result,  $mysql);

?>