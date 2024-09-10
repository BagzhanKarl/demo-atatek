<?php
$data = $_POST;
$name = htmlspecialchars($data['name']);
$search_url = "https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_mobile_child_dad_search&child=" . urlencode($name);

$search_response = file_get_contents($search_url);
$search_data = json_decode($search_response, true);

if (!empty($search_data)) {
    foreach ($search_data as $result){
        $id = $result['id'];
        $ancestor_url = "https://tumalas.kz/wp-admin/admin-ajax.php?action=tuma_mobile_get_ancestors&id=" . urlencode($id);

        $ancestor_response = file_get_contents($ancestor_url);
        $ancestor_data = json_decode($ancestor_response, true);

        $check = array_reverse($ancestor_data);
        if($check[1]['id'] != $data['start']){
            continue;
        }
        if ($check[2]['id'] != $data['secondid']){
            continue;
        }
        if(count($ancestor_data) > 8){
            continue;
        }
        $ancestor_count = count($ancestor_data);
        $current_index = 0;
        echo "<option value=''>Таңдаңыз</option>";
        echo "<option value='".$id."'>";
        foreach (array_reverse($ancestor_data) as $ancestor) {
            echo $ancestor['name'];
            // Если это не последний элемент, добавляем стрелку
            if (++$current_index < $ancestor_count) {
                echo " &#8594; ";
            }
        }
        echo "</option>";
    }
}