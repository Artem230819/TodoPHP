<?php
session_start();
require_once ('config/db.php');
$link = mysqli_connect($db['host'],$db['login'],$db['password'],$db['dataBase']);
mysqli_set_charset($link, 'utf8');
if (!$link) {
    print ("Ошибка подключения: ". mysqli_connect_error($link));
}
$error =[];
$user_id = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
$userName = isset($_SESSION['user']) ? $_SESSION['user']['name'] : null;
