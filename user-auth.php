<?php
require_once('./helpers.php');
require_once('./init.php'); // получаем логин, хост и т.д
require_once('./function.php'); // получаем велосипедные функции
$title = "Авторизация";
$contentTitle = "Авторизация";


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
    $users = mysqli_fetch_all($result, MYSQLI_NUM);
    if (empty($_POST['email'])) {
        $error['email'] = "Это поле нужно заполнить";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $error['email'] = 'E-mail введён некорректно';
    } elseif (!in_array(array($_POST['email']), $users, true)) {
        $error['email'] = 'E-mail не найден';
    } else {
        $email = $_POST['email'];
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($link, $sql);
        if ($result) {
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if (!password_verify($_POST['password'], $user['password'])) {
                $error['password'] = "Неверный пароль";
            } else {
                $_SESSION['user'] = $user;
                header("Location: index.php");
                exit();
            }
        } else {
            print ("Ошибка запроса: " . mysqli_error($link));
            exit();
        }
        if (empty($_POST['password'])) {
            $error['password'] = "Это поле нужно заполнить";
        }
    }
}

print include_template('auth.php', [
    'error' => $error,
    'title' => $title,
    'contentTitle' => $contentTitle
]);
