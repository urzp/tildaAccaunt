<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

$http_origin = $_SERVER['HTTP_ORIGIN'];

header('Access-Control-Allow-Origin: *');

header('Content-Type: application/json; charset=utf-8');
session_start();

if(!$_POST['verify']){
	$_SESSION['endTime'] = time()+5*60;
	$_SESSION['rand'] = rand(100000, 999999);
	$data = array('time' => $_SESSION['endTime'], 'rand' => $_SESSION['rand']);
}else{
	if($_SESSION['endTime'] <= time()){
		$hash = hash('sha256', $_SESSION['rand'].':'.$_SESSION['endTime']);
		$data = array('verify' => $hash);
	}else{
		$data = false;
	}
}

echo json_encode(array('response' => $data));
?>