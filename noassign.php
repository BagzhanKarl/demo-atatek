<?php
require "php/db.php";
$profile = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Ататек</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css?v=<?= time()?>">
</head>
<body>
<div class="head-cont">
    <div class="header">
        <div class="header-container">
            <div class="d-flex gap-5 align-items-center">
                <div class="p-2 brand">ATATEK</div>
                <div class="p-2 nav d-flex gap-5">
                    <a href="index.php" class="nav_links">Шежіре</a>
                    <a href="my.php" class="nav_links"><?=R::findOne('tree', 'item_id = ?', [$profile->ru])->name?></a>
                    <a href="" class="nav_links">Менің әулетім</a>
                    <a href="" class="nav_links"><?=R::findOne('tree', 'item_id = ?', [$profile->ru])->name?> жаңалықтары</a>
                    <a href="" class="nav_links">Статистика</a>
                </div>
                <div class="ms-auto p-2">
                    <a href="profile.php">
                        <img src="images/avatar.png" width="55" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="main" style="width: 100%; height: 50vh; top: 150px; left: 0; position: fixed;display:flex; flex-direction: column; align-items: center; justify-content: center">
    <h3 class="h3">Сіздің тарифтік жоспарыңыз: <b><?=R::findOne('roles', 'id = ?', [$profile->role])->name?></b>!</h3>
    <div class="h4">Сіз бұл бетті модератордың рұқсатынсыз немесе басқа тариф жоспарын қоспай қарай алмайсыз</div>
    <div class="d-flex gap-3 align-items-center mt-5">
        <a href="" class="btn btn-secondary">Модератормен байланысу</a>
        <a href="" class="btn btn-success">Басқа тариф қосу</a>
    </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/3.0.10/go.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>


</body>
</html>