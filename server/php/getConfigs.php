<?php

$sql = "SELECT * FROM `config`";
$sql_result = $mysql -> query($sql);

foreach($sql_result as $item){
    $name_conf =  $item['name'];
    $val_conf = $item['value'];
    $configs[$name_conf] = $val_conf;
}

?>