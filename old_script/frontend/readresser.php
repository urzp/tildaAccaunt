<?php
    header('Access-Control-Allow-Origin: *');
    include_once('config/config.php');

    $key = $_POST["w_key"];
    $id = explode('|||', $key)[1];
    $sql = "SELECT `name`, `api_key` from `postavshik` WHERE `id` = ?";
    $get = $pdo->prepare($sql);
    $get->execute([$id]);
    $info = [];
    while($row = $get->fetch()){
        $info[] = $row;
    };
    echo json_encode($info);
    if (explode("|||", $key)[0] == "YOUR_API_KEY"){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $info[0]['name'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query(array_merge($_POST, ['key' => $info[0]["api_key"]]))
        ));
        $res = curl_exec($curl);
        echo json_encode($res);
    }
?>