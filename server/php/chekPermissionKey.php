<?php

$deny_order = false;

$page_list = ['free-vk-views', 'free-instagram-views'];

// $isCheckPage = false;
// foreach($page_list as $item){
//     if($_POST['url_page']==$item){ $isCheckPage = true; }
// }

$isCheckPage = true;

if($isCheckPage){

    $mysql_2 = new mysqli('localhost','ruslarjn_timer','BI7XqM*M0rYd','ruslarjn_timer');

    $sessionToken = $_POST['sessionToken'];
    $permissionKey = $_POST['permissionKey'];

    $sql ="SELECT * from `freeOrdersTimer` WHERE `sessionToken` = '$sessionToken' AND  `permissionKey` = '$permissionKey' AND `checked` = '' ";

    $result = $mysql_2 -> query($sql);
    $result = $result -> fetch_assoc();

    if(empty($result)){
        $deny_order = true;
        $_POST['usedKey'] = 'fail';
    }else{
        $sql = "UPDATE `freeOrdersTimer` SET `checked` = 'true' WHERE `sessionToken` = '$sessionToken' AND  `permissionKey` = '$permissionKey'";
        $mysql_2 -> query($sql);
        $_POST['usedKey'] = 'sucsses';
    }

    //echo($_POST['usedKey']);

}

?>