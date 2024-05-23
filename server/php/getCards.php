<?php
include 'config.php';
header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");
$post = file_get_contents("php://input");
$post = json_decode($post);
$id_page = $post -> id_page;

$sql = "SELECT * FROM `cardsProduct` WHERE `id_page` = '$id_page' ORDER BY cast(`number_in_page` as unsigned)";
$pages = $mysql -> query($sql);
$data = [];
$i=0;
foreach($pages as $item){
    $id_card = $item['id'];
    $sql = "SELECT `name`, `value` FROM `cardProductParams` WHERE `id_card`='$id_card'";
    $sql_res = $mysql -> query($sql);
    $additional_params = [];
    $ii=0;
    foreach($sql_res as $item_){
        $additional_params[$ii] = $item_;
        $ii++;
    }
    $item['additional_params'] = $additional_params;
    $data[$i] = $item;
    $i++;
}


$result = (object) [
    'success' => true,
    'data' => $data,
];

echo json_encode($result);

?>