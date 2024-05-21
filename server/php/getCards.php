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
    $data[$i] = $item;
    $i++;
}


$result = (object) [
    'success' => true,
    'data' => $data,
];

echo json_encode($result);

?>