<?php
require 'vendor/autoload.php';

use MiniUpload\VkApi as VkApi;
use MiniUpload\Helpers as Helpers;

$vkApi = new VkApi();
$helpers = new Helpers();

//Парсим JSON
$data = json_decode(file_get_contents('php://input'), true);
global $vkApi;
global $helpers;

//Проверяем, что находится в поле type
switch ($data['type']) {
    //Если это уведомление для подтверждения адреса сервера
    case 'confirmation':
        //...отправляем строку для подтверждения адреса
        echo $vkApi::CALLBACK_API_CONFIRMATION_TOKEN;
        break;

    //Если это уведомление о новом сообщении
    case 'message_new':
        //Считываем информацию
        $message = $data['object'];
        //Читаем id
        $peer_id = empty($message['peer_id']) ? $message['user_id'] : $message['peer_id'];
        $text = $message['text']; // Текст сообщения
        $arr = array('Joker', 'Джокер', 'джокер', 'joker'); // Варианты кодовой фразы
        $response = $vkApi->get_users($peer_id); // Читаем информацию о пользователе
        $user_name = $response[0]['first_name']; // Имя пользователя
        // Ответ на кодовую фразу
        $msg = "Привет, " . $user_name . "!
Хочешь узнать, за что \"Джокер\" получил \"Золотого льва\" на Венецианском" .
"кинофестивале, и за что Хоакину Фениксу пророчат \"Оскар\"?
Я напомню тебе о выходе фильма в день премьеры!";
        // Если сообщение содержит кодовую фразу
        if (in_array($text, $arr)) {
            $vkApi->send_message($peer_id, $msg); // Отправляем ответ
            $link = $helpers->db(); // Подключаемся к базе данных
            $query = mysqli_query($link, "SELECT user_id FROM users WHERE user_id = '$peer_id'"); // Проверяем наличие в базе
            $str = mysqli_fetch_array($query);
            // Если пользователя нет в базе данных
            if (empty($str['user_id']))
                // Заносим в базу данных
                $insert = mysqli_query($link, "INSERT INTO users VALUES ('$peer_id', '$user_name')");
        }
        //Возвращаем "ok" серверу Callback API
        echo "ok";
        break;

    //При любом уведомлении по типу message_reply
    default:
        //Возвращаем "ok" серверу Callback API
        echo "ok";
        break;

}



