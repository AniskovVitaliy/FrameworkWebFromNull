<?php

namespace core\base\controller;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController extends BaseController
{
    use Singleton;

   protected $routes;

   private function __construct(){

       /*Получаем адрусную строку с помощью супер глобального массива*/
       $adress_str = $_SERVER['REQUEST_URI'];

       /*Удаляет из адресной строки строку параметров*/
       if($_SERVER['QUERY_STRING']){
           $adress_str = substr($adress_str, 0, strpos($adress_str, $_SERVER['QUERY_STRING']) - 1);
       }

       /*Устанавливаем дирректорию из который был вызван метод маршрутизации*/
       $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));

       /* Проверка на корректность дирректории */
       if($path === PATH){

           //Проверка на слеш в конце строки, если таковой имеется то удаляет, и перезагружает страницу
           if(strrpos($adress_str, '/') === strlen($adress_str) - 1 && strrpos($adress_str, PATH) !== strlen(PATH) - 1) {
               $this->redirect(rtrim($adress_str, '/'), 301);
           }

           //Загружает массив настрок "routes"
           $this->routes = Settings::get('routes');
           if(!$this->routes) throw new RouteException('Отсутсвуют маршруты в базовых настройках', 1);

           //Разбивает адресную строку на массив
           $url = explode('/', substr($adress_str, strlen(PATH)));

           //Если Admin
           if($url[0] && $url[0] === $this->routes['admin']['alias']){

               //Удаление admin из массива
               array_shift($url);

               //Проверка и подключение плагина
               if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){

                   $plugin = array_shift($url);

                   $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');

                   if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){

                       $pluginSettings = str_replace('/', '\\', $pluginSettings);
                       $this->routes = $pluginSettings::get('routes');

                   }

                   $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                   $dir = str_replace('//', '/', $dir);

                   $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;

                   $hrUrl = $this->routes['plugins']['hrUrl'];

                   $route = 'plugins';

               }else{
                   $this->controller = $this->routes['admin']['path'];

                   $hrUrl = $this->routes['admin']['hrUrl'];

                   $route = 'admin';
               }

           }else{

               $hrUrl = $this->routes['user']['hrUrl'];

               $this->controller = $this->routes['user']['path'];

               $route = 'user';

           }

           //Определяет контроллер и методы
           $this->createRoute($route, $url);

           //Определяет параметры $this->parameters
           if($url[1]){
               $count = count($url);
               $key = '';

               if(!$hrUrl){
                   $i = 1;
               }else{
                   $this->parameters['alias'] = $url[1];
                   $i = 2;
               }

               for ( ; $i < $count; $i++){
                   if(!$key){
                       $key = $url[$i];
                       $this->parameters[$key] = '';
                   }else{
                       $this->parameters[$key] = $url[$i];
                       $key = '';
                   }
               }
           }

       }else{
           throw new RouteException('Не коректная дирректория сайта', 1);
       }
   }

    /**
     * Определяет контроллер и методы
     *
     * @param $var
     * @param $arr
     */
   private function createRoute(string $var,array $arr){
       $route = [];

       if(!empty($arr[0])){
           if($this->routes[$var]['routes'][$arr[0]]){
               $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

               $this->controller .= ucfirst($route[0].'Controller');
           }else{
               $this->controller .= ucfirst($arr[0].'Controller');
           }
       }else{
           $this->controller .= $this->routes['default']['controller'];
       }

       $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
       $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];
   }

}