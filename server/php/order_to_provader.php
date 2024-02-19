<?php
include 'config.php';
include 'support_functions.php';
push_log(json_encode($_POST), basename(__FILE__));

$prodavec_id = $_POST['prodavec_id'];
$sql = "SELECT `name`, `api_key` FROM `postavshik` WHERE  `id` = ?";
$base_res = $mysql->prepare($sql);
$base_res->execute([$prodavec_id]);
$arr = [];
while($row = $base_res->fetch()){
    $arr[] = $row;
};

push_log($arr[0]["api_key"], basename(__FILE__));

?>