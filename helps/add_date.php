<?php
require "../auth/db.php";

$shezhire = R::dispense('shezhire');
$shezhire->start = 5;
$shezhire->name = 'Сүйменді Таубұзар';
$shezhire->created_at = time();
R::store($shezhire);
?>