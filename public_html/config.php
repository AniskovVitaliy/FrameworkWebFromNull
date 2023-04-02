<?php
/*Файл настроек сервера*/
/*Проверяем константу из файла index.php*/
defined('VG_ACCESS') or die ('Access denied');

/*Полный пусть к сайту*/
const SITE_URL = 'http://cpa.fvds.ru';
/*Корень пути к сайту*/
const PATH = '/';

/*Подключение веб сервера*/
const HOST = 'localhost';
//const HOST = 'framework.com';
const USER = 'root';
const PASS = '';
const DB_NAME = 'im';

