<?php 
/*Определяем константу безопасности*/
define('VG_ACCESS', true);

/*Подключаем кодировку для браузера*/
header('Content-Type:text/html;charset=utf-8');

/*Подключаем сессии*/
session_start();

/* <================ Подключение файлов проекта ===================== */

//Настройки базы данных
require_once 'config.php';
//Общие настройки и функция автозагрузчика классов
require_once 'core/base/settings/internal_settings.php';
//Функции
require_once 'libraries/functions.php';

/* ==================================================================> */

use core\base\exceptions\RouteException;
use core\base\controller\BaseRoute;
use core\base\exceptions\DbException;

try{
   BaseRoute::routeDirection();
}

catch (RouteException $e){
   exit($e->getMessage());
}

catch (DbException $e){
   exit($e->getMessage());
}