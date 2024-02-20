<?php
    //https://lktilda.ru/php/login_reg.php
    //https://lktilda.ru/php/outcom_payment.php
    //https://lktilda.ru/damp.php
    //https://lktilda.ru/getBase.php
    //https://scryptdlyasmm.site/getBase.php
    //https://scryptdlyasmm.site/exportDB.php
    //https:/smmbackmy.ru/php/tools/fil_table.php
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
            \"amount\":\"100\"
            }",
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

    '
    {
        "post-link":"https:\/\/lktilda.ru\/php\/outcom_payment.php",
        "Email":"ermak80_pass@mail.ru",
        "paymentsystem":"cash",
        "service":"2073",
        "prodavec_id":"5",
        "quantity":"100",
        "payment":"
        {
            \"orderid\":\"1558878517\",
            \"products\":[\"\u041f\u043e\u0434\u043f\u0438\u0441\u0447\u0438\u043a\u0438 \u0418\u043d\u0441\u0442\u0430\u0433\u0440\u0430\u043c \u0434\u0435\u0448\u0435\u0432\u044b\u0435=6\"],
            \"amount\":\"6\"}",
            "formid":"form688582102",
            "formname":"Cart"
        }
    ';

    '
    {"
        post-link":"https:\/\/www.instagram.com\/brand_kina\/",
        "Email":"ermak80_pass@mail.ru",
        "email_user":"ermak80_pass@mail.ru",
        "token":"29f9hfhfcq6uvem24gjp8c",
        "paymentsystem":"cash",
        "service":"2073",
        "prodavec_id":"5",
        "quantity":"100",
        "payment":
            "{\"orderid\":\"2117318733\",
                \"products\":[\"\u041f\u043e\u0434\u043f\u0438\u0441\u0447\u0438\u043a\u0438 \u0418\u043d\u0441\u0442\u0430\u0433\u0440\u0430\u043c \u0434\u0435\u0448\u0435\u0432\u044b\u0435=6\"],
                \"amount\":\"6\"}",
                "formid":"form688582102",
                "formname":"Cart"
            }
    '
    //$('[name="paymentsystem"][value="cash"]').is(':checked')


    /*'{
        "email":"ermak80_pass@mail.ru",
        "id_user":"4",
        "paymentsystem":"cash",
        "payment":"
        {\"orderid\":\"1863541175\",
            \"products\":[\"\u041f\u043e\u043f\u043e\u043b\u043d\u0438\u0442\u044c \u0431\u0430\u043b\u0430\u043d\u0441=100\"],
            \"amount\":\"100\"}",
            "formid":"form697611663",
            "formname":"Cart",
            "api_k":"0234$567DAs"
        }';

    */
?>

