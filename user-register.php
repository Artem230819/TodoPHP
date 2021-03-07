<?php
require_once('./helpers.php');
require_once('./init.php'); // получаем логин, хост и т.д
require_once('./function.php'); // получаем велосипедные функции
$title = "Регистрация";
$contentTitle = "Регистрация";


if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $zeroing(); //удаляет все теги и пробелы POST и GET
    $sql = "SELECT email FROM users";
    $result = mysqli_query($link, $sql);
    if (!$result) {
        print ("Ошибка запроса: " . mysqli_error($link));
        exit();
    }
    $email = mysqli_fetch_all($result, MYSQLI_NUM);
    if (empty($_POST['email'])) {
        $error['email'] = "Это поле нужно заполнить";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'E-mail введён некорректно';
    } elseif (in_array(array($_POST['email']), $email)) {
        $error['email'] = 'E-mail уже зарегистрирован';
    }
    if (empty($_POST['password'])) {
        $error['password'] = "Это поле нужно заполнить";
    }

    if (empty($_POST['name'])) {
        $error['name'] = "Это поле нужно заполнить";
    }

    if (empty($error)) {
        $sql = 'INSERT INTO users (email, name, password) VALUES (?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, [$_POST['email'], $_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT)]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            $email = $_POST['email'];
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($link, $sql);
            if ($result) {
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit();
            }
            {
                print ("Ошибка запроса: " . mysqli_error($link));
                exit();
            }
        } else {
            print ("Ошибка отправки данных: " . mysqli_error($link));
            exit();
        }
    }
}

print include_template('register.php', [
    'error' => $error,
    'title' => $title,
    'contentTitle' => $contentTitle
]);

