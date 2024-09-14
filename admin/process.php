<?php
require "../php/db.php";
$data = $_POST;
$settings = R::dispense('settings');
foreach ($data as $key => $value) {
    $settings->$key = $value;
}
$settings->created_at = time();
$settings->created_by = $_SESSION['user_id'];
R::store($settings);
header('Location: site-settings.php');
?>