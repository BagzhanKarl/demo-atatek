<?php
require "db.php";
$user = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
$tree = R::findOne('tree', 'item_id = ?', [$_POST['id']]);
$info = R::findOne('info', 'item_id = ?', [$_POST['id']]);
?>
<div class="mb-2 d-flex justify-content-between align-items-center">
    <div class="text-block">
        <div class="name"><?= $tree->name?></div>
        <div class="date">
            <?php
            if($tree->birth_year != 0){
                echo $info->birth_year . " ";
            }
            if($tree->death_year != 0){
                echo $info->death_year;
            }
            ?>
        </div>
    </div>
    <div class="img-block">
        <img src="images/flag-outline%201.png" alt="">
    </div>
</div>
<?php if($info):?>
    <div class="mb-3" style="overflow-y: scroll; height: 300px;">
        <? echo nl2br($info->text)?>
    </div>
<?php else:?>
    <div class="mb-3" style="overflow-y: scroll; height: 300px;">
        Біз бұл адам туралы ақпаратты таппадық!
    </div>
<?php endif;?>
<div class="mt-auto">
    <div class="d-flex gap-3">
        <button onclick="$('#canvasAta').hide();" class="btn btn-outline-success w-100">Түсінікті</button>
        <?php if($user->admin == 1):?>
            <a target="_blank" href="admin/addinfo.php?id=<?= $_POST['id']?>" class="btn btn-success w-100">Өзгерту</a>
        <?php endif;?>
    </div>
</div>