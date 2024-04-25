<?php
include '../config.php';

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

function notePageTable($name, $title){
    global $mysql;

    $sql = "INSERT INTO `pages` 
    (`name`,`title`) 
    VALUES
    ('$name','$title')";
    $mysql -> query($sql);
}

$post = file_get_contents("php://input");
$post = json_decode($post);
$data = $post;

foreach($data as $item){
    $name = $item -> link;
    $title = $item -> title;
    notePageTable($name, $title);
}

$result = (object) [
    'success' => true,
    'data' => $data[1] -> link,
];
echo json_encode($result);

?>

