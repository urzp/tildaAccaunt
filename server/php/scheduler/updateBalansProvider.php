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
        if(isset($res_msg['error'])){
            $result = (object) ["status" => "false", "message" => $res];
        }else{
            if (str_contains($res, 'error')) {
                $result = (object) ["status" => "false", "message" => $res];
            }else{
                $result = (object) ["status" => "success", "message" => $res];
            }
        }
        
    }
    //echo $result -> status.'<br>';
    return $result;
}

function getProviders(){
    global $mysql;
    $sql = "SELECT * FROM `postavshik`";
    $sql_result = $mysql -> query($sql);
    
    while ($row = $sql_result->fetch_assoc()) { 
        $data[] = $row;
    }
    return $data;
}

function provReqStatus($id_provider){
    $request = ['action' => 'balance'];
    $result = apiRequesrProvider($id_provider, $request);
    $msq_result = json_decode($result -> message, true);
    //echo "msg ".$msq_result['balance'].'<br>';
    return $msq_result;
}

function updateOrderNote($id, $balans, $currency){
    global $mysql;
    $sql = "UPDATE `postavshik` SET `balans`='$balans', `currency`='$currency' WHERE `id` = '$id' ";
    $mysql -> query($sql);
}

$provaiders = getProviders();

foreach ($provaiders as $item) {
    $result = provReqStatus($item['id_old'], $item['id_order_prov']);
    $balance = $result['balance'];
    $currency = $result['currency'];
    if($balance == '') $balance = 'error';
    
    updateOrderNote($item['id'],$balance, $currency);
    //echo "id ".$item['id']." balance ".$balance.' '.$currency.'<br>';
}

?>