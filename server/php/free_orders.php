<?php

echo "ok";

include 'config.php';
include 'getConfigs.php';
include 'provader_functions.php';

function noteOrder($data){
    global $mysql;
    $name_servis = $data['quantity-text'];
    $service = $data['service'];
    $link = $data['post-link'];
    $quantity = $data['quantity'];
    $id_provider = $data['id_provider'];
    $result = $data['result'] -> message;;
    $sql = "INSERT INTO `orders_free` 
    (`name_servis`, `service`, `link`, `quantity`, `id_provider`, `provider_msg` )
    VALUES
    ('$name_servis', '$service', '$link', '$quantity', '$id_provider', '$result')";
    //push_log(json_encode($sql), basename(__FILE__), 'free_order_log');
    $mysql -> query($sql);
}

function findSameLink($pause, $link){
    global $mysql;
    $timePause = time() - $pause * 60 * 60;
    $untilDate = date('Y-m-d H:i:s', $timePause);
    $sql = "SELECT `id` from `orders_free` WHERE `link` = '$link' AND `datetime` > '$untilDate' "; 
    $result = $mysql -> query($sql);
    $result = $result -> fetch_assoc();
    $result = $result['id'];
    return $result;
}


$pauseServis_hours = (int)$configs['pause_free_orders_h'];
$quantity_max = $configs['quantity_max_free_orderd'];
$id_provider = $configs['provider_free_orders'];

$api_key = $_POST['api_k'];
$quantity = $_POST['quantity'];
$service = $_POST['service'];
$link = $_POST['link'];

if($api_key != _APY_KEY_){ exit(); }

$sameLink_id = findSameLink($pauseServis_hours, $link);
if( isset($sameLink_id)){
    push_log(json_encode("deny by same link ".$link), basename(__FILE__), 'free_order_log');
    exit();
}

if($quantity > $quantity_max){ $quantity = $quantity_max;}

$_POST['post-link'] = $link;
$_POST['prodavec_id'] = $id_provider;
$data = getDataProv($_POST, $mysql);
$result = sendOrderProvader($data);
$_POST['id_provider'] = $id_provider;
$_POST['result'] = $result;
noteOrder($_POST);
//push_log(json_encode($data), basename(__FILE__), 'free_order_log');
///push_log(json_encode($result), basename(__FILE__), 'free_order_log');
?>