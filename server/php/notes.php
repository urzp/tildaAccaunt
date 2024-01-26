<?php
    //https://lktilda.ru/php/login_reg.php

    $log = date('Y-m-d H:i:s') . ' '.json_encode($checkUser);
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
?>