<?php

namespace MiniUpload;

use Imagick;
use ImagickDraw;

class VkGraphics
{

    // Функция смены обложки
    function change_cover(){
        $filmdate = mktime (9, 0, 0, 10, 3, 2019); // Дата премьеры
        $int = $filmdate - time(); // Разница между датой премьеры и нынешней датой
        $days = ($int - ($int % 86400)) / 86400; // Считаем дни
        $hours = (($int % 86400) - ($int % 3600)) / 3600; // Считаем часы
        $minutes = (($int % 3600) - ($int % 60)) / 60; // Считаем минуты
        $oblozhka = new Imagick("images/oblozhka.jpg"); // Фон обложки
//        $days_mask = new Imagick($this->clock[$days]);
        $days_mask = new Imagick('images/clock/'. $days .'min.png');
        $days_mask->flopImage();
        $hours_mask = new Imagick('images/clock/'. ($hours * 2.5) .'min.png');
        $minutes_mask = new Imagick('images/clock/'. $minutes .'min.png');
        $draw = new ImagickDraw();
        $draw->setFillColor('rgb(255, 255, 255)'); // Цвет шрифта
        $draw->setFontSize(50); // Размер шрифта
        $draw->setFont("fonts/Swiss 721 Bold Condensed BT.ttf"); // Выставляем шрифт
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER); // Выравниваем текст по центру
        $oblozhka->annotateImage($draw, 1112, 210, 0, $days); // Добавляем текст на обложку
        $oblozhka->annotateImage($draw, 1212, 210, 0, $hours); // Добавляем текст на обложку
        $oblozhka->annotateImage($draw, 1315, 210, 0, $minutes); // Добавляем текст на обложку
        $oblozhka->compositeImage($days_mask, Imagick::COMPOSITE_OVER, 1065, 145);
        $oblozhka->compositeImage($hours_mask, Imagick::COMPOSITE_OVER, 1170, 145);
        $oblozhka->compositeImage($minutes_mask, Imagick::COMPOSITE_OVER, 1270, 145);
        $oblozhka->writeImage('images/upload.png'); // Сохраняем изображение
        $oblozhka->clear();
    }
}