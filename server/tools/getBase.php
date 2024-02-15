<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

$mysql = new mysqli('mysql','root','parolik9182123','main_database');
//$mysql = new mysqli('localhost','ermak8nk_lktilda','CZ*VT0zC','ermak8nk_lktilda');

$sql = "SELECT * FROM `postavshik`";
//$sql = "SELECT * FROM `out_payments`";
$data = $mysql -> query($sql);

while ($el = $data->fetch_assoc()) {  
    $result[] = $el;
}
echo json_encode($result);

?>