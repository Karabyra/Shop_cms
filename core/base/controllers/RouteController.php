<?php

namespace  core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;
use Exception;

class RouteController
{
    static private  $_instance;

    protected $routes;
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    private function __clone()
    {
    }
    static public function getInstance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }
    private function __construct()
    {
       $adressStr = $_SERVER['REQUEST_URI'];
            // Проверка на наличие / в концеcтроки и редирект если он присутствует
       if(strrpos($adressStr,'/') === strlen($adressStr) -1 && strrpos($adressStr,'/') !== 0){
            $this->redirect(rtrim($adressStr,'/'),301);
       }
            //  Обрезаная строка имини виполненого скрипта
       $path = substr($_SERVER['PHP_SELF'],0,strpos($_SERVER['PHP_SELF'],'index.php'));
            // Проверка маршрута с константой и получения класса настроек
       if($path === PATH){
        $this->routes = Settings::get('routes');
            // Exctprion если настройки маршрутов не подключени
        if(!$this->routes)throw new RouteException(' маршрутов не подключени');

        // admin
        if(strpos($adressStr,$this->routes['admin']['alias']) === strlen(PATH)){
            // создание масива маршрутов
            $url = explode('/',substr($adressStr,strlen(PATH . $this->routes['admin']['alias'])+1));
            // проверка на запрос пути к плагину
            if($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])){
            // выкидуем название плагина из масива марутов
                $plugin = array_shift($url);
                // путь к файлу настроек плагина
                $pluginSettings = $this->routes['settings']['path'].ucfirst($plugin . 'Settings');
                // проверка на существование файла
                if(file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')){
                    
                    $pluginSettings = str_replace('/','\\',$pluginSettings);
                    $this->routes = $pluginSettings::get('routes');
                }
                $dir = $this->routes['plugins']['dir']  ?  '/'. $this->routes['plugins']['dir'].'/' : '/';
                $dir = str_replace('//','/',$dir);
                
                $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                $hrUrl = $this->routes['plugins']['hrUrl'];
                $route = 'plugins';


            }else{

                $this->controller = $this->routes['admin']['path'];
                   // human readable url
                $hrUrl = $this->routes['admin']['hrUrl'];
                $route = 'admin';
            }
    
        }else{
         // user
        //  делим адресную строку по / в массив
            $url = explode('/',substr($adressStr,strlen(PATH)));
            // human readable url
            $hrUrl = $this->routes['user']['hrUrl'];
            // подключение пользевательского маршрута
            $this->controller = $this->routes['user']['path'];
            $route = 'user';
        }

        $this->createRoute($route,$url);


        if($url[1]){
            // количество елементов масива
            $count = count($url);
            $key = '';
            if(!$hrUrl){
                $i = 1;
            }else{
                $this->parameters['alias'] = $url[1];
                $i = 2;
            }
            for( ; $i<$count; $i++){
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
           try{
                throw new \Exception('не коректная диектория');
           }
           catch(RouteException $e){
                exit($e->getMessage());
           }
       }
    }

    private function createRoute(string $var, array $arr )
    {
        $route = [];
        if(!empty($arr[0])){
            // проверка на наличие маршрута
            if($this->routes[$var]['routes']){
            // добавление маршрута в масив route
                $route = explode('/',$this->routes[$var]['routes'][$arr[0]]);
                $this->controller .= ucfirst($route[0].'indexController');
            }else{
                    $this->controller .= ucfirst($route[0].'indexController');
            }
        }else{
            $this->controller .= $this->routes['default']['controller'];
        }
        $this->inputMethod = $route[1] ? $route[1] : $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ? $route[2] : $this->routes['default']['outputMethod'];

        return;
        
    }

}

