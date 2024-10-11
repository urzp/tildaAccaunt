<?php

if($_POST['url_page']=='free-vk-views'){

    $mysql_2 = new mysqli('localhost','ruslarjn_timer','BI7XqM*M0rYd','ruslarjn_timer');

    $sessionToken = $_POST['sessionToken'];
    $permissionKey = $_POST['permissionKey'];

    $sql ="SELECT * from `freeOrdersTimer` WHERE `sessionToken` = '$sessionToken' AND  `permissionKey` = '$permissionKey' AND `checked` = '' ";

    $result = $mysql_2 -> query($sql);
    $result = $result -> fetch_assoc();

    if(empty($result)){
        //exit();
        $_POST['usedKey'] = 'fail';
    }else{
        $sql = "UPDATE `freeOrdersTimer` SET `checked` = 'true' WHERE `sessionToken` = '$sessionToken' AND  `permissionKey` = '$permissionKey'";
        $mysql_2 -> query($sql);
        $_POST['usedKey'] = 'sucsses';
    }

    //echo($_POST['usedKey']);

}

?>