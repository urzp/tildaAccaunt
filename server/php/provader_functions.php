<?php

include_once 'config.php';
include_once 'support_functions.php';

function getDataProv($POST, $mysql){
    $prodavec_id = $POST['prodavec_id'];
    $sql = "SELECT `name`, `api_key` FROM `postavshik` WHERE  `id_old` = '$prodavec_id'";
    $sql_result = $mysql -> query($sql);
    $sql_result = $sql_result -> fetch_assoc();
    $api_key_prov = $sql_result['api_key'];
    $url_prov = $sql_result['name'];

    if($_POST['type'] == "package"){
        echo "package";
        $data = ['key' => $api_key_prov, 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link']];
    } else if($_POST['type'] == "comments"){
    echo "comments";
        $data = ['key' => $api_key_prov, 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'], 'comments' => $_POST['comments']];
    } else if($_POST['type'] == "poll"){
        echo "poll";
        $data = ['key' => $api_key_prov, 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'],  'quantity' => $_POST['quantity'], 'answer_number' => $_POST['answer_number']];
    } else{
        echo "default";
        $data = ['key' => $api_key_prov, 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'], 'quantity' => $_POST['quantity']];
    };

    $result = (object) [
        'url' => $url_prov,
        'data' => $data,
    ];

    return $result;
}

function sendOrderProvader($data_prov){
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $data_prov -> url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data_prov -> data)
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
                if(str_contains($res, 'order')){
                    $result = (object) ["status" => "success", "message" => $res];
                }else{
                    $result = (object) ["status" => "false", "message" => $res];
                }
            }
        }
        
    }

    return $result;
}

?>