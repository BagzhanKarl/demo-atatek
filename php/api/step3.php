<option value="">Таңдаңыз</option>
<?php
$id = $_POST['id'];
require "../../auth/db.php";
$data = R::findAll('shezhire', 'start = ?', [$id]);

foreach ($data as $item) {
    ?>
    <option value="<?= $item['itemid']?>"><?= $item['name']?></option>
    <?
}
?>
