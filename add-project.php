<?php
require_once ('./helpers.php');
require ('./init.php');
$title = "Добавление проекта";
$contentTitle = "Добавление проекта";
if (isset($_SESSION['user'])) {
    require('./queries.php'); // получаем все запросы
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $zeroing(); //удаляет все теги и пробелы POST и GET
        // валидация формы
        if (empty($_POST['project'])) {
            $error['project'] = 'Введите название Проекта';
        } elseif (in_array($_POST['project'], array_column($data_project, 'name'))){
            $error['project'] = 'Такой проект уже есть';
        }
        // отправка формы
        if (empty($error)) {
            $project = $_POST;
            $sql = "INSERT INTO categories (name, user_id) VALUES

         (?, '$user_id');";
            $stmt = db_get_prepare_stmt($link, $sql, $project);
            $res = mysqli_stmt_execute($stmt);
            if ($res) {
                header("Location:/");
                exit();
            } else {
                print ("Ошибка отправки данных: " . mysqli_error($link));
                exit();
            }
        }
    }
    $content = include_template('form-project.php', [
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
