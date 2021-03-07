<?php
require_once ('./helpers.php');
require ('./init.php');
$title = "Добавление задачи";
$contentTitle = "Добавление задачи";
if (isset($_SESSION['user'])) {
    require('./queries.php'); // получаем все запросы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $zeroing(); //удаляет все теги и пробелы POST и GET
        $task = $_POST;
        // валидация формы
        if (empty($task['task'])) {
            $error['task'] = 'Введите название задачи';
        }
        if (empty($task['date_completion'])) {
            $error['date_completion'] = 'укажите дату';
        }
        elseif (strtotime($_POST['date_completion']) < strtotime("last day")) {
            $error['date_completion'] = "Указанная дата уже прошла";
        }
        if (isset($_POST['categories_id'])) {
            if (!in_array($_POST['categories_id'], array_column($data_project, 'id'))) { // проверяем на наличие айдишника проекта
                $error['categories_id'] = 'проекта не существует';
            }
        } else {
            $error['categories_id'] = "добавьте проект";
        }
        // отправка формы
        if (empty($error)) {
            $fileName = null;
            if (!empty($_FILES['file']['name'])){
                $dir = uniqid(); // добавляем в переменную уникальное название
                $dirUrl = mkdir(__DIR__ . '/uploads/' . $dir); // создаем папку с названием из переменной dir
                $fileUrl = __DIR__ . '/uploads/' . $dir . "/"; // сохраняем url этой папки
                $fileName = $_FILES['file']['name']; // сохраняем название
                move_uploaded_file($_FILES['file']['tmp_name'], $fileUrl . $fileName); // перемещаем файл
                $fileUrl = explode('/', $fileUrl); // url делим на массив
                $fileUrl = implode('/', array_slice($fileUrl, -3)); // с этого массива берем только элементы url, и превращаем его в строку
                $fileName = "/" . $fileUrl . $fileName; // помещаем в переменную путь к файлу
            }
            $task['path'] = $fileName; // заменяем название
            $sql = "INSERT INTO tasks (task, categories_id, date_completion, file, user_id) VALUES

         (?, ?, ?, ?, '$user_id');";
            $stmt = db_get_prepare_stmt($link, $sql, $task);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                header("Location:/");
            } else {
                print ("Ошибка отправки данных: " . mysqli_error($link));
                exit();
            }
        }
    }
    $content = include_template('form-task.php', [
        'data_project' => $data_project,
        'error' => $error
    ]);
    print include_template('layout.php', [
        'content' => $content,
        'userName' => $userName,
        'title' => $title,
        'contentTitle' => $contentTitle
    ]);
}else{
    header('Location: /error404/');
    exit();
}
