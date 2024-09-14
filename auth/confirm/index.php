<?php
require "../db.php";
$data = $_POST;
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
    <link rel="stylesheet" href="../main.css?v=1">
</head>
<body>
<div class="container">
    <div class="auth_block">
        <div class="auth_img">
            <img src="../image%201.png" width="502" alt="">
        </div>
        <div class="auth_form">
            <div>
                <h3 class="logo mb-4">Ататек</h3>
                <h2 class="mb-4">Тіркелу</h2>

                <div class="text-center text-secondary mb-4">
                    Сіздің нөміріңізге растау коды бар WhatsApp хабарламасы жіберілді
                </div>

                <div class="abm-standart">
                    <input type="text" id="code" placeholder="000000" class="form-control">
                </div>

                <div>
                    <button onclick="sendCode()" class="btn btn-primary w-100">Тіркелуді аяқтау</button>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <div id="timer">60</div>
                    <div><a class="a-link recode" onclick="resendCode()">Кодты қайта жіберу</a></div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="loader" style="display: none" id="loader">
    <div class="spinner-border atatek-color" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/imask/7.6.1/imask.min.js" integrity="sha512-+3RJc0aLDkj0plGNnrqlTwCCyMmDCV1fSYqXw4m+OczX09Pas5A/U+V3pFwrSyoC1svzDy40Q9RU/85yb/7D2A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2.min.css">
<script>
    function createTree(){
        $.ajax({
            url: "../../php/api/confirmTree.php",
            method: "POST",
            data: {
                status: 'start',
            },
            success: function (response){
                console.log('Get');
            }
        })
    }
    function loadTime(){
        var timer = 60;
        var interval = setInterval(function() {
            timer--;
            $('#timer').text(timer);
            if (timer <= 0) {
                clearInterval(interval);
                $('.recode').removeClass('disabled');
                $('.recode').addClass('resend');
                $('#timer').text('Время вышло!');
            }
        }, 1000);
    }

    function sendCode(){
        let code = $('#code').val();
        $.ajax({
            url: '../../php/verify/timecode.php',
            method: 'POST',
            data: {
                code: code,
            },
            success: function (answere){
                console.log(answere);
                if(answere == true){
                    window.location.href = '../../index.php';
                }
                else{
                    sweetAlert('Ошибка', 'Ваш код не правильно или срок действие утек', 'error', 'Ок');
                }
            }
        })
    }
    function resendCode(){
        $('#loader').show();

        $.ajax({
            url: '../../php/verify/resend.php',
            method: 'POST',
            data: {
                code: "code",
            },
            success: function (answere){
                loadTime();
                console.log(answere);
                $('#loader').hide();
            }
        })
    }
    function sweetAlert(error, text, icon, btn){
        Swal.fire({
            title: error,
            text: text,
            icon: icon,
            confirmButtonText: btn
        })
    }
    $(document).ready(function() {
        loadTime();
        $('.recode').on('click', function (){
            resendCode();
        })
    });
</script>

</body>
</html>