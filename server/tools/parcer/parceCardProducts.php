<?php

header('Access-Control-Allow-Origin: *');
header("Content-Type: application/json");

include 'config.php';
include 'parserFunction.php';

function noteCauntCatdsPage($id_page, $countCatds){
    global $mysql;
    $sql = "UPDATE`pages` SET `cards`='$countCatds' WHERE `id` = '$id_page'";
    $mysql -> query($sql);    
}

function noteCardProduct($id_page, $url, $card, $i_card){
    global $mysql;
    $type = $card['type'];
    $id_provider = $card['id_provider']; 
    $id_servis = $card['id_servis'];
    $img = $card['icon'];
    $title = $card['title'];
    $description = $card['description'];
    $price = $card['price'];
    $currency = $card['currency'];
    $update_script_data = $card['update_script_data'];

    $sql="SELECT `id` FROM `cardsProduct` WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'";
    $result = $mysql -> query($sql);
    $result =  $result -> fetch_assoc();

    if(isset($result)){
        //update
        if($id_provider!=''){
            $sql = "UPDATE `cardsProduct` SET `type`='$type', `id_provider`='$id_provider', `id_servis`='$id_servis', `img`='$img', `title`='$title', `description`='$description', `price`='$price', `currency`= '$currency' WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'";
        }else{
            $sql = "UPDATE `cardsProduct` SET `type`='$type', `img`='$img', `title`='$title', `description`='$description', `price`='$price', `currency`= '$currency' WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'"; 
        }
        $mysql -> query($sql);
    }else{
        //new
        $sql = "INSERT INTO `cardsProduct` 
        (`id_page`,`number_in_page` ,`type`, `id_provider`, `id_servis`, `url`, `img`, `title`, `description`, `price`, `currency`) 
        VALUES
        ('$id_page', '$i_card','$type' , '$id_provider', '$id_servis', '$url', '$img', '$title', '$description', '$price', '$currency' )";
        $mysql -> query($sql);
    }
}

//------------------- BEGIN ------------------------------------------      

$sql="SELECT `value` FROM `config` WHERE `name`='mainLinkSite'";
$mainURL = $mysql -> query($sql);
$mainURL = $mainURL -> fetch_assoc();
$mainURL = $mainURL['value'].'/';

$sql="SELECT `id`, `url` FROM `pages`";
$pages = $mysql -> query($sql);

$i=0;
$count = 0;
foreach($pages as $item){
    //if($i>=0 && $i<4){
    $result =  pesePage($mainURL, $item['url']);
    noteCauntCatdsPage( $item['id'], count($result));
    $id_page = $item['id'];
    $url = $item['url'];
    if(count($result)>0){
        $i_card = 0;
        foreach($result as $item){
            $i_card++;
            noteCardProduct($id_page, $url, $item, $i_card);
        }
    }else{
        // echo '<br/>';
        // echo 'no cards';
        // echo '<br/>';
    }
    //}
    $i++;
    $count = $count + $i_card;
    $i_card = 0;
};

$msg = "обновлено ".$count." карточек услуг";

$result = (object) [
    'success' => $success,
    'msg' => $msg,
];    
echo json_encode($result);

?>