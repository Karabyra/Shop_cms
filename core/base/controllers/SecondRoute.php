<?php

namespace  core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;
use Exception;

class SecondRoute{
    static private  $_instance;


    private function __clone()
    {
       
    }

    private function __construct()
    {
        printR(self::calc());
    }
    private function calc(){
        $a = 2;
        $b = 3;
        $a + $b;
        return;
    }

    static public function getInstance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        return self::$_instance = new self;
    }

}