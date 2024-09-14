<?php
require "php/db.php";
unset($_SESSION['user_id']);

//Перебрасываем после выхода
header('Location: https://land.atatek.kz');
?>