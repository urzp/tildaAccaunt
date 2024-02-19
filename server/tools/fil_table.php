<?php
    //https://smmbackmy.ru/php/tools/fil_table.php

    include '../config.php';

    $json = '';
    
    $array = json_decode($json);

    $sql = "INSERT INTO postavshik (name, api_key) VALUES";
    foreach ($array as $arr) {
        $sql_ =  $sql_."('{$arr -> name }','{$arr -> api_key }'), ";
    }

    $sql = $sql.$sql_;
    $sql = substr($sql,0,-2);

    mysqli_query($mysql, $sql);
?>