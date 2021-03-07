<?php
require_once ('./helpers.php');
require_once ('./init.php'); // получаем логин, хост и т.д
require_once ('./function.php'); // получаем велосипедные функции

// получаем количество одинаковых категорий и сами категории
    $sql = "select c.id, c.name, count(t.categories_id) as count from categories c
            LEFT OUTER join tasks t on c.id = t.categories_id and c.user_id = '$user_id'
            WHERE c.user_id = '$user_id'
            GROUP BY c.id
";
    $data_project = db_retrieval($link, $sql);

    // получаем задачи
    $sql = "select t.id as task_id, t.file, t.categories_id as categories_id, t.task, t.status, DATE_FORMAT( t.date_completion , '%d.%m.%Y') as date_completion from tasks t
inner join users u on u.id = t.user_id and t.user_id = '$user_id'
";
    $data_task = db_retrieval($link, $sql);
if (mysqli_error($link)){
    print ("Ошибка получения данных: ". mysqli_error($link));
    exit();
}
