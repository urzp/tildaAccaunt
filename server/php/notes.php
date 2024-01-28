<?php
    //https://lktilda.ru/php/login_reg.php

    $log = date('Y-m-d H:i:s') . ' '.json_encode($checkUser);
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);

    '{
        "Name":"Paul",
        "Email":"ermak80_pass@mail.ru",
        "Phone":"+7 920 953 76 08",
        "paymentsystem":"cash",
        "payment":"{
            \"orderid\":\"1950728889\",
            \"products\":[\"\u041f\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u0431\u0430\u043b\u0430\u043d\u0441=100\"],
            \"amount\":\"100\"}",
            "formid":"form697611663",
            "formname":"Cart"
        }';

    '{
        "Name":"Paul",
        "Email":"ermak80_pass@mail.ru"
        ,"Phone":"+79209537608",
        "paymentsystem":"cash",
        "payment":"{\"orderid\":\"1969362460\",
            \"products\":[\"\u041f\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u0431\u0430\u043b\u0430\u043d\u0441=400\"],
            \"amount\":\"400\"}",
            "formid":"form697611663",
            "formname":"Cart"}';
?>

