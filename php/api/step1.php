<option value="">Таңдаңыз</option>
<?php
$id = $_POST['id'];
$url = 'https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_cached_childnew_get&nodeid=14&id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

if (is_array($data)) {
// Сохраняем полученные данные в базе и формируем ответ
    foreach ($data as $item) {
        ?>
        <option value="<?= $item['id']?>" data-name="<?= $item['name']?>"><?= $item['name']?></option>
        <?php
    }
}
?>