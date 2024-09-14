<?php
require "../php/db.php";

$check = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
if($check->admin == 0){
    header('location: ../');
}

$user = R::findOne('users', 'id = ?', [$_GET['id']]);
$from = R::findOne('users', 'id = ?', [$user->fromreg]);
$data = $_POST;
if(isset($data['giveRu'])){
    foreach ($data['rods'] as $key){
        $moderator = R::dispense('moderator');
        $moderator->user = $user->id;
        $moderator->rode = $key;
        $moderator->created_by = $_SESSION['user_id'];
        $moderator->created_at = time();
        R::store($moderator);
    }
    header('Location: setmoderator.php?id=' . $_GET['id']);
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
<div class="atatek container">
    <div class="row  justify-content-end">
        <div class="col-10">
            <div class="mt-5 row">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Модератор <?= $user->name . " " . $user->surname?></h3>

                    </div>
                    <div class="card-body">
                        <h6>Разрешенные роды</h6><br>
                        <?php if(R::findOne('moderator', 'user = ?', [$user->id])):?>
                            <div class="" id="info">
                                <?php
                                $rods = R::findAll('moderator', 'user = ?', [$user->id]);
                                foreach ($rods as $key){
                                    $treeData = [];
                                    $child = R::findOne('tree', 'item_id = ?', [$key->rode]);
                                    while ($child) {
                                        $treeData[] = $child->name;

                                        // Проверяем, есть ли родитель
                                        if ($child->parent_id == null) {
                                            break;
                                        }

                                        // Получаем родителя
                                        $child = R::findOne('tree', 'item_id = ?', [$child->parent_id]);
                                    }
                                    $treeTrueData = array_reverse($treeData);
                                    $ancestor_count = count($treeTrueData);
                                    $current_index = 0;
                                    foreach ($treeTrueData as $key){
                                        echo $key;
                                        if (++$current_index < $ancestor_count) {
                                            echo " &#8594; ";
                                        }
                                    }
                                    echo "<br><br>";
                                }

                                ?>
                                <hr>
                                <div class="text-end">
                                    <button id="start" class="btn btn-secondary">Добавить еще</button>

                                </div>
                            </div>
                            <div class="assign" id="assign" style="display: none">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="">Жүз:</label>
                                        <select name="juz" id="step1" required class="form-control">
                                            <option value="">Таңдаңыз</option>
                                            <option value="1">Ұлы жүз</option>
                                            <option value="2">Орта жүз</option>
                                            <option value="3">Кіші жүз</option>
                                            <option value="4">Жүзден тыс</option>
                                        </select>
                                    </div>
                                    <div class="col text-start">
                                        <label for="">Жүздің ішінде:</label>
                                        <select name="onjuz" id="step2" required class="form-control">
                                            <option value=''>Таңдаңыз</option>
                                        </select>
                                    </div>
                                    <div class="col abm-standart text-start" id="needhide">
                                        <label for="" id="onru">Руыңыз:</label>
                                        <input type="text" id="name-search" class="form-control">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button id="searchBtn" class="btn btn-secondary">Искать</button>
                                </div>
                            </div>
                            <div id="form-ru">
                            </div>
                        <?php else:?>
                            <div class="text-center" id="info">
                                <div class="text-danger mb-4 mt-4">
                                    У этого модератора пока нет назначенных Ру.
                                </div>
                                <a id="start" class="btn btn-secondary">Назначить Ру</a>
                            </div>
                            <div class="assign" id="assign" style="display: none">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="">Жүз:</label>
                                        <select name="juz" id="step1" required class="form-control">
                                            <option value="">Таңдаңыз</option>
                                            <option value="1">Ұлы жүз</option>
                                            <option value="2">Орта жүз</option>
                                            <option value="3">Кіші жүз</option>
                                            <option value="4">Жүзден тыс</option>
                                        </select>
                                    </div>
                                    <div class="col text-start">
                                        <label for="">Жүздің ішінде:</label>
                                        <select name="onjuz" id="step2" required class="form-control">
                                            <option value=''>Таңдаңыз</option>
                                        </select>
                                    </div>
                                    <div class="col abm-standart text-start" id="needhide">
                                        <label for="" id="onru">Руыңыз:</label>
                                        <input type="text" id="name-search" class="form-control">
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button id="searchBtn" class="btn btn-secondary">Искать</button>
                                </div>
                            </div>
                            <div id="form-ru">
                            </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Право модератора</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="moderator-rules">

                                    <form action="" method="post">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="createTree" id="r1" checked>
                                            <label class="form-check-label" for="r1">
                                                Добавить данные для общей базы данных
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="updateTree" id="r2" checked>
                                            <label class="form-check-label" for="r2">
                                                Редактировать данные из общей базы данных
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="deleteTree" id="r3">
                                            <label class="form-check-label" for="r3">
                                                Удалить данные из общей базы данных
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="confirmSubscribe" id="r4">
                                            <label class="form-check-label" for="r4">
                                                Дать подписку для пользователей
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="allowRead" id="r5" checked>
                                            <label class="form-check-label" for="r5">
                                                Предоставить пользователю доступ к дереву жузов
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="unbanUser" id="r6">
                                            <label class="form-check-label" for="r6">
                                                Разблокировать аккаунт пользователя
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="banUser" id="r7">
                                            <label class="form-check-label" for="r7">
                                                Заблокировать аккаунт пользователя
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="deleteUser" id="r8">
                                            <label class="form-check-label" for="r8">
                                                Удалить аккаунт пользователя
                                            </label>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button name="giverules" class="btn btn-success">Назначить модератора</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="loader" style="position: fixed; display: flex; width: 100%; top: 0; left: 0; height: 100vh; justify-content: center; align-items: center; z-index: 1000; background: rgba(44,44,44,0.66)" id="loader">
    <div class="spinner-border atatek-color" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>

    $(document).ready(function (){
        $(document).ready(function(){
            $('#loader').hide();
        })
        $('#start').on('click', function(){
            $('#info').hide();
            $('#assign').show();
        })
        $('#step1').on('change', function(){
            $('#resSub').hide();
            $('#searchBtn').show();
            $('#loader').show();
            $.ajax({
                url: '../php/api/step1.php',
                method: 'POST',
                data: {
                    id: $(this).val()
                },
                success: function(options){
                    $('#step2').html('');
                    $('#step2').html(options);
                    $('#loader').hide();
                }
            })
        })
        $('#step2').on('change', function(){
            var juz = $('#step2').val();
            $.ajax({
                url: '../php/api/step2.php',
                method: 'POST',
                data: {
                    id: juz
                },
                success: function (ans){
                    console.log(ans);
                    $('#onru').text(ans);
                }
            })
        })
        $('#searchBtn').on('click', function(){
            let text = $('#onru').text();
            $('#loader').show();
            $.ajax({
                url: '../../php/api/searchchek.php',
                method: 'POST',
                data: {
                    name: $('#name-search').val(),
                    start: $('#step1').val(),
                    secondid: $('#step2').val()
                },
                success: function(options){
                    console.log(options);
                    $('#form-ru').html('');
                    $('#form-ru').html(options);
                    $('#loader').hide();
                    $('#assign').hide();
                }
            })
        })

    })
</script>
</body>
</html>