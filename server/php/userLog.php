<?php

function addTouserLog($id_user, $action_name, $success, $oldData, $newData, $mysql){
    $sql = "INSERT INTO `user_log` (`id_user`, `action`, `succsess`, `old_data`, `new_data` ) VALUES('$id_user','$action_name','$success' , '$oldData', '$newData')";
    $mysql -> query($sql);
}

?>