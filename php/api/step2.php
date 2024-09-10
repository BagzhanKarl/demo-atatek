<?php
require "../db.php";
$tree = R::findOne('tree', 'item_id = ?', [$_POST['id']]);
echo $tree->name . " ішінде:";
?>