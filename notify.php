<?php
require __DIR__ . '/vendor/autoload.php';
require_once ('./function.php');

$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465))
    ->setUsername('stmt.anoshkin')
    ->setPassword('ziP-KrZ-cMp-Pv7')
    ->setEncryption('SSL')
;

$mailer = new Swift_Mailer($transport);
$from = 'stmt.anoshkin@mail.ru';

$sql_tasks = "SELECT  t.task, t.date_completion, u.name AS user, email FROM tasks t
    LEFT JOIN users u ON u.id = t.user_id WHERE date_completion <= CURDATE() AND status = 0";

$res = mysqli_query($link, $sql_tasks);

if (!$res) {
    echo mysqli_error($link);
    exit();
}

$tasks = mysqli_fetch_all($res, MYSQLI_ASSOC);

if (empty($tasks)) {
    exit();
}
$user = '';
$task = '';
$date = '';
$email = '';
$clientText = '';
$res=array();
foreach ($tasks as $value){ // фильтруем задачи
        if(isset($res[$value["email"]])){
            $res[$value["email"]]["task"].= " и ".$value["task"];
            $res[$value["email"]]["date_completion"].= " и ".$value["date_completion"];
        } else {
            $res[$value["email"]]=$value;
        }
}
foreach ($res as $value){ // пробегаем по задачам
    $user = $value['user'];
    $task = $value['task'];
    $date = $value['date_completion'];
    $email = $value['email'];
    $clientText = <<<MESS
        Уважаемый, <b>{$user}</b> . У вас запланирована задача <b>{$task}</b> на <b>{$date}</b>
MESS;
    $messageToClient = (new Swift_Message("Уведомление от сервиса «Дела в порядке»"))
        ->setFrom([$from => $from])
        ->setTo([$email => $user])
        ->setBody($clientText, 'text/html')
    ;
    $result = $mailer->send($messageToClient);
}

if($result) { // если всё хорошо и письмо было отправлено
    echo '<h1>Спасибо! Ваша заявка была успешно отправлена! Ожидайте обратную связь по указанным контактным данным.</h1>';
}
else { // если произошла ошибка и письмо не было отправлено
    exit();
}
