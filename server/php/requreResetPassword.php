<?php 

header('Access-Control-Allow-Origin: *');
include 'db_mysql.php';
include 'checkEmail.php';

$email = $_POST["email"];

$sql = "SELECT `id` FROM `users` WHERE `email`='$email'";
$check = $mysql -> query($sql);
$check = $check-> fetch_assoc();
$check = isset($check);

if(!$check){
    $result = (object) [
        'success' => true,//save cover process
    ];
    echo json_encode($result);   
    exit();
}

$reset_password = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

$sql = "UPDATE`users` SET `resetpassword`='$reset_password ' WHERE `email`='$email'";
$checkSession = $mysql -> query($sql);

$to  = "$email" ; 
//$to .= "mail2@example.com>"; 

$subject = "Сброс пароля"; 

$message = " <p>Код для сброса пароля</p> </br> <b>$reset_password</b>";

$headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
//$headers .= "From:<noreply@unverified.beget.ru>\r\n"; 
$headers .= "Reply-To: reply-to@example.com\r\n"; 

mail($to, $subject, $message, $headers); 

$result = (object) [
	'success' => true,
    'reset_password' => $reset_password
];
echo json_encode($result);

?>