<?php
require "db/rb-mysql.php";
R::setup( 'mysql:host=localhost;dbname=atatek',
    'root', '' );
session_start();

date_default_timezone_set('Asia/Almaty');

if(!$_SESSION['user_id']){
    header('Location: auth/login');
}
?>