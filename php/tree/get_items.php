<?php
require_once "../db.php";
header('Content-type: application/json');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $id = $_GET['id'];
    $itemsData = [];

    // Проверяем, есть ли элементы в базе данных
    $items = R::findAll('tree', 'parent_id = ?', [$id]);
    $parent = R::findOne('tree', 'item_id = ?', [$id]); // Получаем родителя

    if ($items) {
        // Если элементы есть, формируем ответ
        foreach ($items as $item) {
            $info = R::findOne('info', 'item_id = ?', [$item->item_id]);
            if($info){
                $status = 'have';
            }
            else{
                $status = null;
            }
            $itemsData[] = [
                'id' => $item->item_id,
                'name' => $item->name,
                'birth_year' => $item->birth_year,
                'death_year' => $item->death_year,
                'parent_id' => $id,
                'info' => $status
            ];
        }
    } else {
        // Если элементов нет, делаем запрос на внешний источник
        $url = 'https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_cached_childnew_get&nodeid=14&id=' . $id;
        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (is_array($data)) {
            // Сохраняем полученные данные в базе и формируем ответ
            foreach ($data as $item) {
                $record = R::dispense('tree');

                $record->item_id = $item['id'];
                $record->name = $item['name'];
                $record->birth_year = $item['birth_year'];
                $record->death_year = $item['death_year'];
                $record->parent_id = $id;

                R::store($record);
                $info = R::findOne('info', 'item_id = ?', [$record->item_id]);
                if($info){
                    $status = 'have';
                }
                else{
                    $status = null;
                }
                $itemsData[] = [
                    'id' => $item['id'],
                    'name' => $item['name'],
                    'birth_year' => $item['birth_year'],
                    'death_year' => $item['death_year'],
                    'parent_id' => $id,
                    'info' => $status
                ];
            }
        } else {
            // Ошибка при получении или декодировании данных
            $code = [
                "status" => False,
                "version" => 'v1',
                "author" => '@baxa_mk',
                "data" => 'Ошибка получения данных или декодирования JSON.',
            ];
            echo json_encode($code, JSON_PRETTY_PRINT);
            exit;
        }
    }

    // Формируем и отправляем окончательный ответ
    $code = [
        "status" => True,
        "version" => 'v1',
        "author" => '@baxa_mk',
        "data" => $itemsData,
    ];

    echo json_encode($code, JSON_PRETTY_PRINT);
}
?>
