<?php
    header('Access-Control-Allow-Origin: *');
    include_once('config/config.php');
    $sql = "SELECT `name`, `api_key` FROM `postavshik` WHERE  `id` = ?";
    $base_res = $pdo->prepare($sql);
    $base_res->execute([$_POST["prodavec_id"]]);
    $arr = [];
    while($row = $base_res->fetch()){
        $arr[] = $row;
    };
    $order = json_encode((array) $_POST);
    $ins_res = $pdo->prepare("INSERT INTO `orders` (`string`) VALUES ('?')");
    $ins_res->execute([$order]);
    if(isset($_POST) == false){
        // return error message
        die(json_encode(["status" => "bad request", "Error" => "no data"]));
    };
    if($_POST['type'] == "package"){
    	echo "package";
        $data = ['key' => $arr[0]["api_key"], 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link']];
    } else if($_POST['type'] == "comments"){
	echo "comments";
        $data = ['key' => $arr[0]["api_key"], 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'], 'comments' => $_POST['comments']];
    } else if($_POST['type'] == "poll"){
    	echo "poll";
        $data = ['key' => $arr[0]["api_key"], 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'],  'quantity' => $_POST['quantity'], 'answer_number' => $_POST['answer_number']];
    } else{
    	echo "default";
        $data = ['key' => $arr[0]["api_key"], 'action' => 'add', 'service' => $_POST['service'], 'link' => $_POST['post-link'], 'quantity' => $_POST['quantity']];
    };
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $arr[0]["name"],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data)
    ));
    $res = curl_exec($curl);
    if(curl_error($curl)){
        $errMes = curl_error($curl);
    };
    curl_close($curl);
    
    if(isset($errMes)) {
        echo "Error: " . $errMes;
    } else {
       echo json_encode(["status" => "success", "message" => $res]);
    }
?>
