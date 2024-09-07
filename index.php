<?php
require "php/db.php";
$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
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
    <link rel="stylesheet" href="css/main.css?v=5">
</head>
<body>
    <div class="head-cont">
        <div class="header">
            <div class="header-container">
                <div class="d-flex gap-5 align-items-center">
                    <div class="p-2 brand">ATATEK</div>
                    <div class="p-2 nav d-flex gap-5">
                        <a href="" class="nav_links active">Шежіре</a>
                        <a href="" class="nav_links">Жарты</a>
                        <a href="" class="nav_links">Менің әулетім</a>
                        <a href="" class="nav_links">Жарты жаңалықтары</a>
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
    <div id="sample">
        <div id="myDiagramDiv" class="samplepage"></div>
    </div>
    <div class="canvas" style="display: none">
        <div class="block h-100">
            <div class="d-flex flex-column h-100">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <div class="text-block">
                        <div class="name">АЛАШ</div>
                        <div class="date">2022-2024</div>
                    </div>
                    <div class="img-block">
                        <img src="images/flag-outline%201.png" alt="">
                    </div>
                </div>
                <div class="mb-3">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam commodi ex explicabo
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam commodi ex explicabo
                </div>
                <div class="mt-auto">
                    <div class="d-flex gap-3">
                        <button class="btn btn-outline-success w-100">Түсінікті</button>
                        <?php if($user->admin == 1):?>
                        <button class="btn btn-success w-100">Өзгерту</button>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/3.0.10/go.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script>
        function data(data){
            console.log(data);
        }
    </script>
    <script src="js/main.js?v=4"></script>

</body>
</html>