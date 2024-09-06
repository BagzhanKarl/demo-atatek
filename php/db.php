<?php
require "db/rb-mysql.php";
R::setup( 'mysql:host=192.168.1.108;dbname=atatek',
    'root', '' );
session_start();

date_default_timezone_set('Asia/Almaty');
?>