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

function notePageInf($id_page, $inf){
    global $mysql;
    $title = $inf['page_title'];
    $descr = $inf['page_discription'];
    $sql = "UPDATE`pages` SET `titile_in_page`='$title', `descr_in_page`='$descr' WHERE `id` = '$id_page'";
    $mysql -> query($sql);       
}

function noteCauntCatdsFolder(){
    global $mysql;
    $sql="SELECT * FROM `folders`";
    $folders = $mysql -> query($sql);
    foreach($folders as $item){
        $id_folder = $item['id'];
        $sql="SELECT SUM(`cards`) FROM `pages` WHERE `folder_id` = '$id_folder'";
        $countCards = $mysql -> query($sql);
        $countCards = $countCards -> fetch_assoc();
        $countCards = $countCards['SUM(`cards`)'];
        $sql="UPDATE`folders` SET `cards`='$countCards' WHERE `id` = '$id_folder'";
        $mysql -> query($sql);
    }
}

function noteCardProduct($id_page, $url, $card, $i_card){
    global $mysql;
    $type = $card['type'];
    $id_provider = $card['id_provider']; 
    $id_servis = $card['id_servis'];
    $img = $card['icon'];
    $title = $card['title'];
    $description = $card['description'];
    $price_title = $card['price_title'];
    $price = $card['price'];
    $currency = $card['currency'];
    $update_script_data = $card['update_script_data'];

    $sql="SELECT `id` FROM `cardsProduct` WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'";
    $result = $mysql -> query($sql);
    $result =  $result -> fetch_assoc();

    if(isset($result)){
        //update if( $id_provider!='' ){
        if(false){
            $sql = "UPDATE `cardsProduct` SET `type`='$type', `id_provider`='$id_provider', `id_servis`='$id_servis', `img`='$img', `title`='$title', `description`='$description', `price_title`='$price_title',  `price`='$price', `currency`= '$currency' WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'";
        }else{
            $sql = "UPDATE `cardsProduct` SET `type`='$type', `img`='$img', `title`='$title', `description`='$description', `price_title`='$price_title' ,`price`='$price', `currency`= '$currency' WHERE `id_page`='$id_page' AND `number_in_page`='$i_card'"; 
        }
        $mysql -> query($sql);
    }else{
        //new
        //`id_provider`, `id_servis`, 
        //'$id_provider', '$id_servis',
        $sql = "INSERT INTO `cardsProduct` 
        (`id_page`,`number_in_page` ,`type`, `url`, `img`, `title`, `description`, `price_title`, `price`, `currency`) 
        VALUES
        ('$id_page', '$i_card','$type' , '$url', '$img', '$title', '$description', '$price_title' ,'$price', '$currency' )";
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
    //if($i>=0 && $i<1){
    $result =  pesePage($mainURL, $item['url']);
    noteCauntCatdsPage( $item['id'], count($result));
    notePageInf($item['id'], $result[0]);
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

noteCauntCatdsFolder();

$msg = "обновлено ".$count." карточек услуг";

$result = (object) [
    'success' => $success,
    'msg' => $msg,
    'data' => $countCards,
];    
echo json_encode($result);

?>