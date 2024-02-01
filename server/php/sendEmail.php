<?php

function test(){
    $email = 'ermak80_pass@mail.ru';

    $to  = "$email" ; 
    //$to .= "mail2@example.com>"; 

    $subject = "Регистрация"; 

    $message = " <p>Спасибо за регистрацию</p>";

    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    //$headers .= "From:<support@smmnakrutka.ru>\r\n"; 
    $headers .= "Reply-To: reply-to@example.com\r\n"; 

    mail($to, $subject, $message, $headers); 
}

function sendRegEmail($email,$openPassword){
    $to  = "$email";
    $subject = "Регистрация";
    $message = " <p>Спасибо за регистрацию на сервесе SMMnakrutka</p>  </br> <p>ваш пароль: </p> <b>$openPassword</b>";
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    //$headers .= "From:<support@smmnakrutka.ru>\r\n"; 
    $headers .= "Reply-To: reply-to@example.com\r\n"; 
    mail($to, $subject, $message, $headers); 
}

function sendResetPasswordEmail($email, $resetpassword){
    $to  = "$email";
    $subject = "Сброс пароля";
    $message = " <p>Сброс пароля на сервесе SMMnakrutka</p>  </br> <p>ваш код: </p> <b>$resetpassword</b>";
    $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
    //$headers .= "From:<support@smmnakrutka.ru>\r\n"; 
    $headers .= "Reply-To: reply-to@example.com\r\n"; 
    mail($to, $subject, $message, $headers); 
}

//sendResetPasswordEmail('ermak80_pass@mail.ru','12345678');


?>