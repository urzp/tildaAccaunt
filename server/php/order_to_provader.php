<?php
include 'provader_functions.php';

if($_POST["api_k"]!=_APY_KEY_){ exit(); }
if($_POST['paymentsystem']=='cash'){ exit(); }
$data = getDataProv($_POST, $mysql);
$result = sendOrderProvader($data);
push_log( json_encode($result), basename(__FILE__));

?>