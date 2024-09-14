<?php
require "../php/db.php";
$check = R::findOne('users', 'id = ?', [$_SESSION['logged_user']]);
if($check->admin == false){
    header('location: ../');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Главная</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css?v=<?=time()?>">
</head>
<body>
<div class="col-2 nav">
    <div class="brand-logo text-center">
        <div class="h3">ATATEK</div>
    </div>
    <div class="space"></div>
    <ul class="nav-links mt-5">
        <li class="d-flex align-items-center gap-3 active"><ion-icon name="home-outline"></ion-icon><a href="index.php">Главная</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="settings-outline"></ion-icon><a href="site-settings.php">Настройки сайта</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="people-outline"></ion-icon><a href="users.php">Пользователи</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="albums-outline"></ion-icon><a href="">База данных</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="recording-outline"></ion-icon><a href="tikets.php">Тикеты</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="newspaper-outline"></ion-icon><a href="subsriptions.php">Подписки</a></li>
    </ul>
</div>
<div class="atatek">
    <div class="row  justify-content-end">
        <div class="col-10">
            <div class="mt-5 row">

            </div>
        </div>
    </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>