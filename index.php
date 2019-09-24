<?php
require 'vendor/autoload.php';

use MiniUpload\Helpers;
use MiniUpload\VkApi as VkApi;
use MiniUpload\VkGraphics as VkGraphics;
use React\EventLoop\Factory;

$vkApi = new VkApi();
$vkGraphics = new VkGraphics();

$loop = Factory::create();

$loop->addPeriodicTimer(60, function () {
    global $vkApi;
    $helpers = new Helpers();
    $filmdate1 = mktime (9, 0, 0, 10, 3, 2019);
    $int = $filmdate1 - time();
    $days = ($int - ($int % 86400)) / 86400;
    $hours = (($int % 86400) - ($int % 3600)) / 3600;
    $minutes = (($int % 3600) - ($int % 60)) / 60;
    print('До выхода фильма осталось ' . $days . ' дней ' . $hours . ' часов ' . $minutes . ' минут' . '</br>');
    $vkApi->upload_cover(); // Меняем обложку
    $filmdate = mktime (10, 0, 0, 10, 3, 2019); // Дата премьеры
    // Когда наступает дата премьеры
    if ($filmdate < time()) {
        $message = 'Что ж, этот день настал. Сделай счастливое лицо – сегодня "Джокер" наконец выходит на ' .
'экраны России. Ты можешь выбрать сеанс и забронировать билеты на /ссылка будет' .
' позднее/. Приятного просмотра!';
        $link = $helpers->db(); // Подключаемся к базе данных
        $query = mysqli_query($link, 'SELECT * FROM users'); // Получаем список пользователей
        // Идем по списку пользователей
        while ($row = mysqli_fetch_array($query)){
            $vkApi->send_message($row['user_id'], $message); // Отсылаем всем сообщение
        }
    }
});

$loop->run();



