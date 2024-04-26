<?php
include '../config.php';

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

function notePageTable($id, $folder_id , $url, $title, $img){
    global $mysql;
    $sql = "INSERT INTO `pages` 
    (`id`, `folder_id`, `url`, `title`, `img`) 
    VALUES
    ('$id', '$folder_id' ,'$url','$title', '$img')";
    $mysql -> query($sql);
}

function noteFolderTable($id, $name){
    global $mysql;
    $sql = "INSERT INTO `folders` 
    (`id`, `name`) 
    VALUES
    ('$id', '$name')";
    $mysql -> query($sql);
}

function send_response($success, $msg = ''){
    $result = (object) [
        'success' => $success,
        'msg' => $msg,
    ];    
    echo json_encode($result);
    exit();
}

//-------------------- BEGIN ---------------------

$post = file_get_contents("php://input");
$post = json_decode($post);
$pages = $post -> pages;
$folders = $post -> folders;

if(!isset($pages)){ send_response(false, 'No one page');}
$sql = "DELETE FROM `pages`";
$mysql -> query($sql);

foreach($pages as $item){
    $id = $item -> id;
    $folder_id = $item -> folderid;
    $url = $item -> url;
    $title = $item -> title;
    $img = $item -> img;
    notePageTable($id, $folder_id, $url, $title, $img);
}

$sql = "DELETE FROM `folders`";
$mysql -> query($sql);

foreach($folders as $item){
    $id = $item -> id;
    $name = $item -> title;
    noteFolderTable($id, $name);
}

send_response(true);

?>

