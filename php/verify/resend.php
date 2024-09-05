<?php
require "../../auth/db.php";

$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
$verify = R::findOne('verify', 'user_id = ?', [$user->id]);
R::trash($verify);

$verify = R::dispense('verify');
$verify->user = $user;
$verify->code = rand(100000, 999999);
$verify->time = time() + 360;
R::store($verify);
echo $verify->code;
?>