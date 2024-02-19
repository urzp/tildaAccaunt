<?php
include 'config.php';
include 'support_functions.php';
push_log(json_encode($_POST), basename(__FILE__));

$prodavec_id = $_POST['prodavec_id'];
$sql = "SELECT `name`, `api_key` FROM `postavshik` WHERE  `id_old` = '$prodavec_id'";
$api_key_prov = $mysql -> query($sql);
$api_key_prov = $api_key_prov -> fetch_assoc();
$api_key_prov = $api_key_prov['api_key'];

push_log($api_key_prov, basename(__FILE__));

?>