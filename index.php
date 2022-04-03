<?php
use core\base\exceptions\RouteException;
use core\base\controllers\RouteController;
use core\base\controllers\SecondRoute;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;


define('VG_ACCESS', true);


header('Content-Type:text/html;charset=utf-8');

session_start();

require_once 'config.php';
require_once 'core/base/settings/internal_settings.php';
require_once 'libraries/print.php';



try
{
   $a = SecondRoute::getInstance();
   printR($a);
}
catch (RouteException $e)
{
    exit($e->getMessage());
}

