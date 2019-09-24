<?php

namespace MiniUpload;

class Helpers
{

    function db(){
        $link = mysqli_connect('localhost', 'root', 'Pw85BaaO', 'joker')
        or die("Ошибка " . mysqli_error($link));
        mysqli_set_charset($link, "utf8");
        return $link;
    }


}