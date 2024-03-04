<?php
    function updateUser($id, $email, $name, $phone, $token, $mysql  ){
        $sql = "SELECT `id`, `email`, `name`, `phone` FROM `users` WHERE `id` = '$id' && `login_token`='$token'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();

        $oldData = '';
        $newData = '';

        if($checkUser['email']!=$email){
            $oldData = "email: ".$checkUser['email']." , ";
            $newData = "email: ".$email." , ";
        }

        if($checkUser['name']!=$name){
            $oldData = $oldData."name: ".$checkUser['name']." , ";
            $newData = $newData."name: ".$name." , ";
        }

        if($checkUser['phone']!=$phone){
            $oldData = $oldData."phone: ".$checkUser['phone']." , ";
            $newData = $newData."phone: ".$phone." , ";
        }

        if($oldData!=''){$oldData = substr($oldData, 0, -3);}
        if($newData!=''){$newData = substr($newData, 0, -3);}
        
        if(isset($checkUser)){
            $sql = "UPDATE`users` SET `email`='$email', `name`='$name', `phone`='$phone' WHERE `id` = '$id' && `login_token`='$token'";
            $mysql -> query($sql);
            addTouserLog($id, 'update user unformation','true' ,$oldData ,$newData , $mysql);
            $result = (object) [
                'success' => true,
            ];    
        }else{
            addTouserLog($id, 'update user unformation','false login_token or id' ,$oldData ,$newData , $mysql);
            $result = (object) [
                'success' => false,
                'msg' => 'login'
            ];       
        }

        $mysql->close();
        echo json_encode($result);
        exit();
    }



    function checkEmail($id, $email, $mysql){
        $sql = "SELECT `id` FROM `users` WHERE `id` != '$id' && `email` = '$email'";
        $checkUser = $mysql -> query($sql);
        $checkUser = $checkUser -> fetch_assoc();
        if(isset($checkUser)||!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $result = (object) [
                'success' => false,
                'msg' => 'email'
            ];
            $oldData = "-";
            $newData = "email: ".$email;             
            addTouserLog($id, 'update user unformation','false email is busy' ,$oldData ,$newData , $mysql);
            $mysql->close();
            echo json_encode($result);
            exit();        
        }
    }

    header('Access-Control-Allow-Origin: *');
    header("Content-Type: application/json");
    
    $post = file_get_contents("php://input");
    $post = json_decode($post);
    
    
    $id = $post-> id;
    $email = $post-> email;
    $name = $post-> name;
    $token = $post -> token;
    $phone = $post -> phone;
    
    include 'config.php';
    include 'userLog.php';
    

    checkEmail($id, $email, $mysql);
    updateUser($id, $email, $name, $phone, $token, $mysql );
?>