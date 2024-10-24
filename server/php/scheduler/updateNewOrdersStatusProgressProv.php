<?php

$mysql = new mysqli('localhost','ruslarjn_lktilda','4idekl$dws','ruslarjn_lktilda');

function apiRequesrProvider($id_provider, $request){
    global $mysql;

    $sql = "SELECT `name`, `api_key` FROM `postavshik` WHERE  `id_old` = '$id_provider'";
    $sql_result = $mysql -> query($sql);
    $sql_result = $sql_result -> fetch_assoc();
    $api_key = $sql_result['api_key'];
    $url_prov = $sql_result['name'];

    $request['key'] = $api_key;
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url_prov,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($request)
    ));
    $res = curl_exec($curl);
    if(curl_error($curl)){
        $errMes = curl_error($curl);
    };
    curl_close($curl);

    if(isset($errMes)) {
        $result = (object) ["status" => "false", "message" => $errMes];
    } else {
        $res_msg = json_decode($res,true);
        if(isset($res_msg['Error'])){
            $result = (object) ["status" => "false", "message" => $res];
        }else{
            if (str_contains($res, 'error')) {
                $result = (object) ["status" => "false", "message" => $res];
            }else{
                $result = (object) ["status" => "success", "message" => $res];
            }
        }
        
    }
    return $result;
}

function getOrders(){
    global $mysql;
    $sql = "SELECT * FROM `orders` WHERE `progress_status`='' AND `provader_status`='success' ORDER BY id DESC LIMIT 50 ";
    $sql_result = $mysql -> query($sql);
    
    while ($row = $sql_result->fetch_assoc()) { 
        $row['id_order_prov'] = json_decode($row['provader_msg'],true)['order'];
        if(!isset($row['id_order_prov'])) continue;
        $data[] = $row;
    }
    return $data;
}

function provReqStatus($id_provider, $id_order_prov){
    $request = ['action' => 'status', 'order' => $id_order_prov ];
    $result = apiRequesrProvider($id_provider, $request);
    $msq_result = json_decode($result -> message, true);
    return $msq_result;
}

function updateOrderNote($id, $status, $remains, $start_count){
    global $mysql;
    $sql = "UPDATE `orders` SET `progress_status`='$status', `progress_remains`='$remains', `start_count`= '$start_count'  WHERE `id` = '$id' ";
    $mysql -> query($sql);
}

$orders = getOrders();

foreach ($orders as $item) {
    $result = provReqStatus($item['id_provider'], $item['id_order_prov']);
    updateOrderNote($item['id'],$result['status'],$result['remains'], $result['start_count']);
    //echo "id ".$item['id']." status ".$result['status'].'<br>';
}

?>