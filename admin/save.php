<?php
// Определение корневой папки
$rootDirectory = '../sites/';
$currentDirectory = isset($_GET['dir']) ? $_GET['dir'] : $rootDirectory;

// Проверка на попытку выхода за пределы корневой директории
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>File Manager</h1>

    <!-- Путь к текущей директории -->
    <p><strong>Current Directory:</strong> <?php echo realpath($currentDirectory); ?></p>

    <!-- Ссылка для возврата в предыдущую папку -->
    <?php if ($currentDirectory !== $rootDirectory): ?>
        <a href="?dir=<?php echo dirname($currentDirectory); ?>" class="btn btn-secondary mb-3">Back</a>
    <?php endif; ?>

    <!-- Форма для создания новой папки -->
    <form method="POST" class="mb-3">
        <div class="mb-3">
            <label for="folder_name" class="form-label">Create New Folder</label>
            <input type="text" class="form-control" id="folder_name" name="folder_name" placeholder="Folder name" required>
        </div>
        <button type="submit" name="create_folder" class="btn btn-primary">Create Folder</button>
    </form>

    <!-- Форма для загрузки нового файла -->
    <form method="POST" enctype="multipart/form-data" class="mb-3">
        <div class="mb-3">
            <label for="file" class="form-label">Upload File</label>
            <input type="file" class="form-control" name="file" required>
        </div>
        <button type="submit" class="btn btn-success">Upload File</button>
    </form>

    <!-- Список файлов и папок -->
    <h2>Contents:</h2>
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
</body>
</html>
