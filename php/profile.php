<?php
require "db.php";
$data = $_POST;

$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
foreach ($data as $key => $value){
    $user->$key = $value;
    R::store($user);
}
header('Location: ../profile.php');
?>