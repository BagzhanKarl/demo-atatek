<?php
require "../php/db.php";

$check = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
if($check->admin == 0){
    header('location: ../');
}

$user = R::findOne('users', 'id = ?', [$_GET['id']]);
$from = R::findOne('users', 'id = ?', [$user->fromreg]);
$data = $_POST;
if(isset($data['save'])){
    foreach ($data as $key => $value){
        $user->$key = $value;
    }
    $user->updated_at = time();
    R::store($user);

    if($data['role'] == 4){
        header('Location: setmoderator.php?id=' . $_GET['id']);
    }
    else{
        header('Location: user.php?id=' . $_GET['id']);
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пользователи</title>
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
        <li class="d-flex align-items-center gap-3"><ion-icon name="settings-outline"></ion-icon><a href="site-settings.php">Настройки сайта</a></li>
        <li class="d-flex align-items-center gap-3 active"><ion-icon name="people-outline"></ion-icon><a href="users.php">Пользователи</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="albums-outline"></ion-icon><a href="">База данных</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="recording-outline"></ion-icon><a href="tikets.php">Тикеты</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="newspaper-outline"></ion-icon><a href="subsriptions.php">Подписки</a></li>
    </ul>
</div>
<div class="atatek">
    <div class="row  justify-content-end">
        <div class="col-10">
            <div class="mt-5 row">
                <div class="card">
                    <div class="card-header">
                        <h3>Пользователи</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="" method="POST">
                                    <div class="row">
                                        <div class="mb-3 col">
                                            <label for="">Имя</label>
                                            <input type="text" placeholder="" name="name" value="<?= $user->name?>" class="form-control">
                                        </div>
                                        <div class="mb-3 col">
                                            <label for="">Фамилия</label>
                                            <input type="text" placeholder="" name="surname" value="<?= $user->surname?>" class="form-control">
                                        </div>
                                        <div class="mb-3 col">
                                            <label for="">Отчество</label>
                                            <input type="text" placeholder="" name="lastname" value="<?= $user->lastname?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="mb-3 col">
                                        <label for="">Город</label>
                                        <input type="text" placeholder="" name="city" value="<?= $user->city?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <div class="row">
                                            <div class="col">
                                                <label for="">Жүз:</label>
                                                <input type="text" readonly value="<?= R::findOne('tree', 'item_id = ?', [$user->juz])->name?>" class="form-control">
                                            </div>
                                            <div class="col">
                                                <label for="">Жүздің ішінде:</label>
                                                <input type="text" readonly value="<?= R::findOne('tree', 'item_id = ?', [$user->onjuz])->name?>" class="form-control">
                                            </div>
                                            <div class="col">
                                                <label for="">Руы:</label>
                                                <input type="text" readonly value="<?= R::findOne('tree', 'item_id = ?', [$user->ru])->name?>" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3 col">
                                        <label for="">Роль</label>
                                        <select name="role" class="form-control">
                                            <?php
                                            $roles = R::findAll('roles');

                                            foreach ($roles as $role){
                                                if($user->role == $role->id){
                                                    ?>
                                                    <option value="<?= $role->id?>" selected><?= $role->name?></option>
                                                    <?

                                                }
                                                else{
                                                    ?>
                                                    <option value="<?= $role->id?>"><?= $role->name?></option>
                                                    <?
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3 text-end">
                                        <button name="save" class="btn btn-success">Сохранить</button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-4">
                                    <p class="m-2">
                                        <b>Дата регистраций: </b>
                                    </p>
                                    <p class="m-2">
                                        <?= date('d.m.Y H:i', $user->created_at)?>
                                    </p>
                                </div>
                                <div class="d-flex gap-4 mt-2">
                                    <p class="m-2">
                                        <b>Зарегистрировался по ссылке: </b>
                                    </p>
                                    <p class="m-2">
                                        <a href="user.php?id=<?= $user->fromreg?>"><?= $from->name . " " . $from->surname?></a>
                                    </p>
                                </div>
                                <div class="d-flex gap-4 mt-2">
                                    <p class="m-2">
                                        <b>Количество человек, зарегистрировавшихся по реферальной ссылке этого человека: </b>
                                    </p>
                                    <p class="m-2">
                                        <?= count(R::findAll('users', 'fromreg = ?', [$user->id]))?>
                                    </p>
                                </div>
                            </div>
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