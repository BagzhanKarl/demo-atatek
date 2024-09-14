<?php
require "../php/db.php";

$settings = R::findOne('settings', 'ORDER BY id DESC');

$check = R::findOne('users', 'id = ?', [$_SESSION['logged_user']]);
if($check->admin == 0){
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
    <title>Настройки сайта</title>
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
        <li class="d-flex align-items-center gap-3"><ion-icon name="home-outline"></ion-icon><a href="index.php">Главная</a></li>
        <li class="d-flex align-items-center gap-3 active"><ion-icon name="settings-outline"></ion-icon><a href="site-settings.php">Настройки сайта</a></li>
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
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="process.php" method="post">
                                <h2>Настройка сайта</h2>
                                <hr>
                                <h5 class="mb-4">Цветовая тема</h5>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="maleBadgeBackground" class="form-label">Фон значка (Ақпарат)</label>
                                        <input type="color" class="form-control form-control-color" id="maleBadgeBackground" name="maleBadgeBackground" value="<?= $settings->maleBadgeBackground?>">
                                    </div>
                                    <div class="col mb-3">
                                        <label for="maleBadgeText" class="form-label">Текст значка (Ақпарат)</label>
                                        <input type="color" class="form-control form-control-color" id="maleBadgeText" name="maleBadgeText" value="<?= $settings->maleBadgeText?>">
                                    </div>

                                    <div class="col mb-3">
                                        <label for="personNodeBackground" class="form-label">Фон блока</label>
                                        <input type="color" class="form-control form-control-color" id="personNodeBackground" name="personNodeBackground" value="<?= $settings->personNodeBackground?>">
                                    </div>
                                    <div class="col mb-3">
                                        <label for="personText" class="form-label">Текст блока (Имя)</label>
                                        <input type="color" class="form-control form-control-color" id="personText" name="personText" value="<?= $settings->personText?>">
                                    </div>
                                    <div class="col mb-3">
                                        <label for="princePrincessBorder" class="form-label">Обводка блока</label>
                                        <input type="color" class="form-control form-control-color" id="princePrincessBorder" name="civilianBorder" value="<?= $settings->civilianBorder?>">
                                    </div>
                                </div>
                                <hr>

                                <h5 class="mb-4">Шрифты</h5>

                                <div class="row mb-4">
                                    <div class="col">
                                        <label for="">Шрифт (Имя)</label>
                                        <input type="text" readonly value="Montseratt" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Стиль шрифта (Имя)</label>
                                        <input type="text" name="mainFontStyle" value="<?= $settings->mainFontStyle?>" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Размер шрифта (Имя)</label>
                                        <input type="text" name="mainFontSize" value="<?= $settings->mainFontSize?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col">
                                        <label for="">Шрифт (Дата)</label>
                                        <input type="text" readonly value="Montseratt" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Стиль шрифта (Дата)</label>
                                        <input type="text" name="dateFontStyle" value="<?= $settings->dateFontStyle?>" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Размер шрифта (Дата)</label>
                                        <input type="text" name="dateFontSize" value="<?= $settings->dateFontSize?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col">
                                        <label for="">Шрифт (Значок)</label>
                                        <input type="text" readonly value="Montseratt" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Стиль шрифта (Значок)</label>
                                        <input type="text" name="bageFontStyle" value="<?= $settings->bageFontStyle?>" class="form-control">
                                    </div>
                                    <div class="col">
                                        <label for="">Размер шрифта (Значок)</label>
                                        <input type="text" name="bageFontSize" value="<?= $settings->bageFontSize?>" class="form-control">
                                    </div>
                                </div>
                                <hr>
                                <h5  class="mb-4">Размеры</h5>
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <label for="">Обводка</label>
                                        <input type="text" name="stroke" value="<?= $settings->stroke?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Радиус</label>
                                        <input type="text" name="radius" value="<?= $settings->radius?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Отсуп между детми</label>
                                        <input type="text" name="nodespace" value="<?= $settings->nodespace?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Отступ между слоями</label>
                                        <input type="text" name="layerspace" value="<?= $settings->layerspace?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-3">
                                        <label for="">Ширина</label>
                                        <input type="text" name="nodeX" value="<?= $settings->nodeX?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Высота</label>
                                        <input type="text" name="nodeY" value="<?= $settings->nodeY?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Ширина текста</label>
                                        <input type="text" name="textX" value="<?= $settings->textX?>" class="form-control">
                                    </div>
                                    <div class="col-3">
                                        <label for="">Высота тектса</label>
                                        <input type="text" name="textY" value="<?= $settings->textY?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <label for="">Отступ сверху текста</label>
                                        <input type="text" name="textTop" value="<?= $settings->textTop?>" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label for="">Отступ сверху дата</label>
                                        <input type="text" name="dateTop" value="<?= $settings->dateTop?>" class="form-control">
                                    </div>
                                </div>
                                <button class="btn btn-success">Сохранить</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>