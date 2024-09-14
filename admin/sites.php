<?php
require "../php/db.php";

$check = R::findOne('users', 'id = ?', [$_SESSION['user_id']]);
if($check->admin == 0){
    header('location: ../');
}

$rootDirectory = '/var/www/sites/';
$currentDirectory = isset($_GET['dir']) ? $_GET['dir'] : $rootDirectory;

if (strpos(realpath($currentDirectory), realpath($rootDirectory)) !== 0) {
    $currentDirectory = $rootDirectory;
}

// Создание новой папки в текущей директории
if (isset($_POST['create_folder']) && !empty($_POST['folder_name'])) {
    $newFolder = $currentDirectory . '/' . basename($_POST['folder_name']);
    if (!file_exists($newFolder)) {
        mkdir($newFolder, 0777, true);
    }
}

// Удаление файла или папки
if (isset($_POST['delete'])) {
    $itemToDelete = $currentDirectory . '/' . $_POST['delete'];
    if (is_dir($itemToDelete)) {
        rmdir($itemToDelete);  // Удалить папку (только если она пустая)
    } elseif (is_file($itemToDelete)) {
        unlink($itemToDelete);  // Удалить файл
    }
}

// Загрузка файлов в текущую директорию
if (isset($_FILES['file'])) {
    $uploadPath = $currentDirectory . '/' . basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath);
}

// Получение списка файлов и папок
function listDirectoryContents($directory) {
    return array_diff(scandir($directory), array('.', '..'));
}

$items = listDirectoryContents($currentDirectory);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пользователи</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css?v=<?=time()?>">
</head>
<body>
<div class="col-2 nav">
    <div class="brand-logo text-center">
        <div class="h3">ATATEK</div>
    </div>
    <div class="space"></div>
    <ul class="nav-links mt-5">
        <li class="d-flex align-items-center gap-3"><ion-icon name="home-outline"></ion-icon><a href="index.php">Главная</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="settings-outline"></ion-icon><a href="site-settings.php">Настройки сайта</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="people-outline"></ion-icon><a href="users.php">Пользователи</a></li>
        <li class="d-flex align-items-center gap-3 active"><ion-icon name="albums-outline"></ion-icon><a href="sites.php">Сайты</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="recording-outline"></ion-icon><a href="tikets.php">Тикеты</a></li>
        <li class="d-flex align-items-center gap-3"><ion-icon name="newspaper-outline"></ion-icon><a href="subsriptions.php">Подписки</a></li>
    </ul>
</div>
<div class="atatek">
    <div class="row  justify-content-end">
        <div class="col-10">
            <div class="mt-5 row">
                <div class="card">
                    <div class="card-header">
                        <h3>Сайты</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <p><strong>Current Directory:</strong> <?php echo realpath($currentDirectory); ?></p>
                                <!-- Ссылка для возврата в предыдущую папку -->
                                <?php if ($currentDirectory !== $rootDirectory): ?>
                                    <a href="?dir=<?php echo dirname($currentDirectory); ?>" class="btn btn-secondary mb-3">Back</a>
                                <?php endif; ?>
                            </div>

                            <form method="POST" class="mb-3">
                                <div class="mb-3">
                                    <label for="folder_name" class="form-label">Создать папку</label>
                                    <input type="text" class="form-control" id="folder_name" name="folder_name" placeholder="Название" required>
                                </div>
                                <button type="submit" name="create_folder" class="btn btn-primary">Создать</button>
                            </form>
                            <form method="POST" enctype="multipart/form-data" class="mb-3">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Загрузить файл</label>
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                                <button type="submit" class="btn btn-success">Загрузить</button>
                            </form>

                            <h2>Файлы:</h2>
                            <ul class="list-group">
                                <?php foreach ($items as $item):
                                    $itemPath = $currentDirectory . '/' . $item;
                                    $isFolder = is_dir($itemPath);
                                    ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <!-- Переход в папку -->
                                        <?php if ($isFolder): ?>
                                            <a href="?dir=<?php echo urlencode($itemPath); ?>" class="text-primary"><?php echo $item; ?></a>
                                        <?php else: ?>
                                            <span><?php echo $item; ?></span>
                                        <?php endif; ?>

                                        <!-- Кнопка удаления -->
                                        <form method="POST" class="mb-0">
                                            <input type="hidden" name="delete" value="<?php echo $item; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>