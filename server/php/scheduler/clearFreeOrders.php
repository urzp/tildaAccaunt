<?php

$mysql = new mysqli('localhost','ruslarjn_lktilda','4idekl$dws','ruslarjn_lktilda');

$sql = "SELECT `value` FROM `config` WHERE  `name` = 'clearFreeOrdersDays'";
$sql_result = $mysql -> query($sql);
$sql_result = $sql_result -> fetch_assoc();

$days = $sql_result['value'];
$from_date = date("Y-m-d", strtotime("-$days days"));

$sql = "DELETE FROM `orders_free` WHERE  `datetime` < '$from_date'";
$sql_result = $mysql -> query($sql);


?>