<?php

function addTouserLog($id_user, $action_name, $success, $oldData, $newData, $mysql){
    $sql = "INSERT INTO `user_log` (`id_user`, `action`, `succsess`, `old_data`, `new_data` ) VALUES('$id_user','$action_name','$success' , '$oldData', '$newData')";
    $log = date('Y-m-d H:i:s') .basename(__FILE__). ' '.$sql;
    file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
    $mysql -> query($sql);
}

?>