<?php
/**
 * Узнает сколько времени осталось до завершения задачи
 * @param array $arr Ассоциативный массив с данными для вычисления времени
 * @param integer $divider значение по умолчанию
 * @return int результат вычисления времени
 */
$resultDay = function (&$arr, $divider = 3600){
    $ts = time(); // узнаем юникс тайм
    $time = strtotime($arr['date_completion']); // узнаем юникс тайм даты
    $ts_diff = $time - $ts ; // узнаем сколько осталось секунд
    $ts_diff = $ts_diff / $divider;
    return $ts_diff;
};

/**
 * Удаляет все теги и пробелы в Get и Post запросе
 */
//функция которая удаляет все теги и пробелы
$zeroing = function (){
    $_GET = array_map("strip_tags", $_GET); // удаляем теги при отправки в базу
    $_GET = array_map("trim", $_GET); // удаляем пробелы
    $_POST = array_map("strip_tags", $_POST); // удаляем теги при отправки в базу
    $_POST = array_map("trim", $_POST); // удаляем пробелы
};

/**
 * Создает подготовленное выражение на основе готового SQL запроса и получения данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос
 * @param array $data Данные для вставки
 *
 * @return array Результат полученых данных
 */
function db_retrieval ($link, $sql, $data= []){
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res){
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else{
        print ("Данные не найденны: ". mysqli_error($link));
        exit();
    }
    return $result;
};
