<?php
require "php/db.php";
$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
$profile = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
$tree = R::findOne('tree', 'item_id = ?', [$user->ru]);
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
    <link rel="stylesheet" href="css/main.css?v=6">
</head>
<body>
<div class="head-cont">
    <div class="header">
        <div class="header-container">
            <div class="d-flex gap-5 align-items-center">
                <div class="p-2 brand">ATATEK</div>
                <div class="p-2 nav d-flex gap-5">
                    <a href="index.php" class="nav_links">Шежіре</a>
                    <a href="" class="nav_links"><?=R::findOne('tree', 'item_id = ?', [$profile->ru])->name?></a>
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
<div id="sample">
    <div id="myDiagramDiv" class="samplepage"></div>
</div>
<div class="toast-container position-fixed bottom-0 start-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">ATATEK</strong>
            <small>Қазір</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="usernameinfo">
            Бұл адамның балалары туралы ақпарат жоқ!
        </div>
    </div>
</div>
<div class="canvas" id="canvasAta" style="display: none">
    <div class="block h-100">
        <div class="d-flex flex-column h-100">
            <div id="canvas-data-ajax"></div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gojs/3.0.10/go.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script>
    function openInfo(infoid){
        $('#canvasAta').show();
        $.ajax({
            url: 'php/canvas.php',
            method: 'POST',
            data: {
                id: infoid
            },
            success: function (data){
                $('#canvas-data-ajax').html(data);
            }
        })
        console.log(infoid);
    }
    const familyData = [
        { id: <?= $tree->item_id?>, name: '<?= $tree->name?>', gender: 'M', status: 'king', born: null, death: null, info: null},
    ];
    function noDate(name){
        const toastLiveExample = document.getElementById('liveToast')
        const toast = new bootstrap.Toast(toastLiveExample)
        $('#usernameinfo').text(name + '! Бұл адамның балалары туралы ақпарат жоқ!')
        toast.show()
        setTimeout(() => {
            toast.hide()
        }, 5000)
    }

</script>
<?php if($user->admin == 0):?>
    <script src="js/main.js?v=10"></script>
<?php else:?>
    <script src="js/admin-main.js?v=10"></script>

<?php endif;?>

</body>
</html>