<?php

$mysql_timer = new mysqli('localhost','ruslarjn_timer','BI7XqM*M0rYd','ruslarjn_timer');

$from_date = date("Y-m-d H:m:s", strtotime("-2 hours"));
//$from_date = date("Y-m-d", strtotime("-1 days"));

$sql = "DELETE FROM `freeOrdersTimer` WHERE  `date_time` < '$from_date'";
$sql_result = $mysql_timer -> query($sql);
$mysql_timer -> close();


?>