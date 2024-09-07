<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']); // Получаем значение из формы

    // Формируем первый запрос для поиска ребенка
    $search_url = "https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_mobile_child_dad_search&child=" . urlencode($name);

    // Отправляем первый запрос и получаем ответ
    $search_response = file_get_contents($search_url);
    $search_data = json_decode($search_response, true);

    // Проверяем, что пришли данные
    if (!empty($search_data)) {
        // Ограничиваем количество до первых 10 записей
        $limited_results = array_slice($search_data, 0, 100);

        // Для каждого элемента создаем отдельный div
        foreach ($limited_results as $result) {
            $id = $result['id'];
            $ancestor_url = "https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_mobile_get_ancestors&id=" . urlencode($id);

            // Отправляем второй запрос и получаем ответ
            $ancestor_response = file_get_contents($ancestor_url);
            $ancestor_data = json_decode($ancestor_response, true);
            $check = array_reverse($ancestor_data);
            if($check[1]['id'] != 1){
                continue;
            }
            if(count($ancestor_data) > 8){
                continue;
            }
            echo '<div>';
            foreach (array_reverse($ancestor_data) as $ancestor) {

                echo '<div data-id="' . htmlspecialchars($ancestor['id']) . '">' . htmlspecialchars($ancestor['name']) . '</div>';
            }
            echo '</div><br>'; // Разделяем блоки предков
        }
    } else {
        echo 'Ничего не найдено.';
    }
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="name" placeholder="Есімі">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Іздеу</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
