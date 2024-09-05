<?php
require "../../auth/db.php";
$data = $_POST;
$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
$verify = R::findOne('verify', 'user_id = ?', [$user->id]);

if($verify->time >= time()){
    if($data['code'] == $verify->code){
        R::trash($verify);
        $user->verify = true;
        R::store($user);
        echo true;
    }
    else{
        echo false;
    }
}
else{
    echo false;
}
?>