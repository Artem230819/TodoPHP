<?php
require('./init.php');
require __DIR__ . '/vendor/autoload.php';
require_once('./helpers.php');
require_once('./function.php');
$title = "Дела в порядке";
$contentTitle = "Дела в порядке";

if (date("H:i", time()) == '00:00') {
    require_once('./notify.php');
}


if (isset($_SESSION['user'])) {
    $show_complete_tasks = 0;
    require_once('./queries.php'); // получаем все запросы
    $zeroing(); // function.php функция их файла удаляет все теги и пробелы POST и GET
    // перебор массива и вывод определенных проектов
    if (!isset($_GET['id'])) { // если в глобал массиве есть айди, то
    } else {
        $id = $_GET['id']; // создаем переменную
        $data = null; // создаем пустую переменную
        foreach ($data_task as $value) {
            if ($value['categories_id'] !== (int)$_GET['id']) {
                continue;
            } // перебераем массив и сравниваем его с айди из глобал массива
            $data[] = $value; // добавляем в пустой массив все элементы при каждой итерации
        }
        $data_task = $data; // перезатираем массив
        if ($data === null) {
            header('Location: /');
            exit();
        }
    }
    if (!empty($_GET['search'])) { // проверка на присутствие запроса на поиск
        $search = $_GET['search'];
        $sql = "SELECT file, categories_id, task, status, DATE_FORMAT( date_completion , '%d.%m.%Y') as date_completion FROM tasks
                WHERE user_id = '$user_id' AND MATCH(task) AGAINST(?)";
        $stmt = db_get_prepare_stmt($link, $sql, [$search]);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $data_task = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    if (!empty($_POST['mark'])) { // проверка на присутствие запроса на завершение задачи
        $mark = $_POST['mark'];
        $sql = "UPDATE tasks
                SET status = CASE
                    WHEN status = 1 THEN 0
                    WHEN status = 0 THEN 1
                END
              WHERE `id` = ?";
        $stmt = db_get_prepare_stmt($link, $sql, [$mark]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header('Location: /');
        } else {
            print ("Ошибка отправки данных: " . mysqli_error($link));
            exit();
        }
    }
    if (!empty($_GET['taskFilter'])) { // если передан фильтер,то
        $filter = $_GET['taskFilter']; // создаем переменную
        $data = array(); // создаем пустую переменную
        foreach ($data_task as $value) {
            if ($filter === 'today' && $resultDay($value, 86400) < 0 && $resultDay($value, 86400) > -1) {
                $data[] = $value; // создаем пустой массив и добавляем в него все элементы при каждой итерации
            } elseif ($filter === 'tomorrow' && $resultDay($value, 86400) > 0 && $resultDay($value, 86400) < 1) {
                $data[] = $value; // создаем пустой массив и добавляем в него все элементы при каждой итерации

            } elseif ($filter === 'overdue' && $resultDay($value, 86400) < -1) {
                $data[] = $value; // создаем пустой массив и добавляем в него все элементы при каждой итерации
            }
        }
        $data_task = $data; // перезатираем массив
    }
    if (isset($_GET['show_completed'])) {
        $_GET['show_completed'] === '1' ? $show_complete_tasks = '1' : $show_complete_tasks = '0';
    }
    $content = include_template('main.php', [
        'show_complete_tasks' => $show_complete_tasks,
        'data_project' => $data_project,
        'data_task' => $data_task,
        'resultDay' => $resultDay
    ]);

    print include_template('layout.php', [
        'content' => $content,
        'userName' => $userName,
        'title' => $title,
        'contentTitle' => $contentTitle
    ]);
} else {
    $content = include_template('guest.php', []);

    print include_template('layout.php', [
        'content' => $content,
        'title' => $title,
        'contentTitle' => $contentTitle,
        'userName' => $userName,
    ]);
}
