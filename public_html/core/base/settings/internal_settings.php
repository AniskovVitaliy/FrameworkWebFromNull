<?php
/*Файл настроек сайта*/

/*Проверяем константу из файла index.php*/
defined('VG_ACCESS') or die ('Access denied');

/*Пусть к шаблонам пользовательской части сайта*/
const TEMPLATE = 'templates/default/';
/*Путь к административной панели сайта*/
const ADMIN_TEMPLATE = 'core/admin/view/';
const UPLOAD_DIR = 'userfiles/';

/*Версия куков сайта*/
const COOKIE_VERSION = '1.0.0';
/*Ключ шифрования*/
const CRYPT_KEY = 'A%D*G-KaPdSgVkXp7w!z%C*F-JaNdRgUq3t6w9z$C&F)J@NcVkYp3s6v9y$B&E)HdSgUkXp2s5v8y/B?JaNdRgUjXn2r5u8x&F)J@NcRfUjWnZr4y$B&E)H@McQfTjWm';
/*Таймер бездействия*/
const COOKIE_TIME = 60;
/*Время бана для пользователя который ввел неверный пароль*/
const BLOCK_TIME = 3;

/*Постраничная навигация*/
const QTY = 8;
/*Ссылки*/
const QTY_LINKS = 3;

/*Константы для хранения пусти к css и js файлам*/
const ADMIN_CSS_JS = [
    'styles' => ['css/main.css'],
    'scripts' => ['js/frameworkfunctions.js', 'js/scripts.js']
];

const USER_CSS_JS = [
    'styles' => [],
    'script' => []
];

use core\base\exceptions\RouteException;

function autoloadMainClasses($class_name){
   /* Заменяем символы */
   $class_name = str_replace('\\', '/', $class_name);
    /*Пытается подключить файл*/
   if(!@include_once $class_name . '.php'){
       /*Если подключение не произойдет выбросит исключение*/
      throw new RouteException('Не верное имя файла для подключения - ' . $class_name);
   }
}

/*Функция для регистрациий функций в загрузчике классов*/
spl_autoload_register('autoloadMainClasses');