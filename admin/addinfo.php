<?php
require "../php/db.php";

$person = R::findOne('tree', 'item_id = ?', [$_GET['id']]);
$personParent = R::findOne('tree', 'item_id = ?', [$person->parent_id]);

$data = $_POST;
if(isset($data['save'])){
    $person->name = $data['name'];
    $person->birth_year = $data['birth_year'];
    $person->death_year = $data['death_year'];

    // Проверяем и сохраняем изображение
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] == 0) {
        $fileTmpPath = $_FILES['icon']['tmp_name'];
        $fileName = $_FILES['icon']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        echo '<pre>';
        print_r($_FILES['icon']);
        echo '</pre>';

        // Проверка расширения
        if (in_array($fileExtension, $allowedExtensions)) {
            list($width, $height) = getimagesize($fileTmpPath);

            $uploadDir = "../images/icons";
            $destination = $uploadDir . $person->item_id . '.' . $fileExtension;

            // Сохранение файла
            if (move_uploaded_file($fileTmpPath, $destination)) {
                $person->icon_path = $destination;  // Сохраняем путь к изображению в объекте
                echo "Иконка успешно загружена.";
            } else {
                echo "Ошибка при загрузке иконки.";
            }
        } else {
            echo "Неподдерживаемый формат файла. Разрешены только jpg, jpeg и png.";
        }
    }

    R::store($person); // Сохраняем изменения в объекте person

    if($data['text'] != ''){
        $info = R::dispense('info');
        $info->item_id = $person->item_id;
        $info->text = $data['text'];
        R::store($info);
    }

//    echo '<script>window.close()</script>';
}

if(isset($data['delete'])){
    R::trash($person);
    echo '<script>window.close()</script>';
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="">Есімі:</label>
                            <input type="text" value="<?= $person->name?>" name="name" required class="form-control">
                        </div>
                        
                        <div class="mb-3 row">
                            <div class="col">
                                <label for="">Туған жылы</label>
                                <input type="text" value="<?= $person->birth_year?>" name="birth_year" class="form-control">
                            </div>
                            <div class="col">
                                <label for="">Өлген жылы</label>
                                <input type="text" value="<?= $person->death_year?>" name="death_year" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="">Әкесі:</label>
                            <input type="text" value="<?= $personParent->name?>" readonly class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="">Иконка</label>
                            <input type="file" name="icon" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for=""><?= $person->name?> туралы ақпарат</label>
                            <textarea name="text" id="" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <button class="btn btn-danger" name="delete">Жою</button>
                                <button class="btn btn-success" name="save">Сақтау</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

</body>
</html>
