<?php
require "../auth/db.php";

$shezhire = R::dispense('shezhire');
$shezhire->start = 771;
$shezhire->name = 'Жарты - Мұсылманбай';
$shezhire->created_at = time();
$shezhire->itemid = 779;

R::store($shezhire);
?>