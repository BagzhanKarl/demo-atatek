<?php
require "../db.php";
$data = $_POST;

$errors = [];
$error = '';

if(isset($data['signup'])){
    $user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
    $user->persontype = $data['type'];
    $user->updated_at = time();
    R::store($user);

    $verify = R::dispense('verify');
    $verify->user = $user;
    $verify->code = rand(100000, 999999);
    $verify->time = time() + 360;
    R::store($verify);

    header('Location: ../confirm');
}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Atatek || Тіркелу</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="sweetalert2.min.css">
    <link rel="stylesheet" href="../main.css">
</head>
<body>
<div class="container">
    <div class="auth_block">
        <div class="auth_img">
            <img src="../image%201.png" width="502" alt="">
        </div>
        <div class="auth_form">
            <form method="POST" class="needs-validation" novalidate>
                <h3 class="logo mb-4">Ататек</h3>
                <h2 class="mb-4">Тіркелу</h2>
                <?= $error?>
                <div class="row abm-standart">
                    <div class="col">
                        <select name="" id="step1" class="form-control">
                            <option value="">Таңдаңыз</option>
                            <option value="1">Ұлы жүз</option>
                            <option value="2">Орта жүз</option>
                            <option value="3">Кіші жүз</option>
                            <option value="4">Жүзден тыс</option>
                        </select>
                    </div>
                    <div class="col">
                        <select name="" id="step2" class="form-control"></select>
                    </div>
                </div>
                <div class="abm-standart">
                    <select name="" id="step3" class="form-control"></select>
                </div>
                <div class="abm-standart">
                    <select name="type" id="step4" class="form-control"></select>
                </div>
                <div>
                    <button name="signup" class="btn btn-primary w-100">Жалғастыру</button>
                </div>

            </form>
        </div>
    </div>
</div>
<div class="loader" id="loader">
    <div class="spinner-border atatek-color" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/7.6.1/imask.min.js" integrity="sha512-+3RJc0aLDkj0plGNnrqlTwCCyMmDCV1fSYqXw4m+OczX09Pas5A/U+V3pFwrSyoC1svzDy40Q9RU/85yb/7D2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script !src="">
    $(document).ready(function (){
        $(document).ready(function(){
            $('#loader').hide();
        })
        $('#step1').on('change', function(){
            $('#loader').show();
            $.ajax({
                url: '../../php/api/step1.php',
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
            $('#loader').show();
            $.ajax({
                url: '../../php/api/step2.php',
                method: 'POST',
                data: {
                    id: $(this).val()
                },
                success: function(options){
                    $('#step3').html('');
                    $('#step3').html(options);
                    $('#loader').hide();
                }
            })
        })
        $('#step3').on('change', function(){
            $('#loader').show();
            $.ajax({
                url: '../../php/api/step3.php',
                method: 'POST',
                data: {
                    id: $(this).val()
                },
                success: function(options){
                    $('#step4').html('');
                    $('#step4').html(options);
                    $('#loader').hide();
                }
            })
        })
    })
</script>

</body>
</html>