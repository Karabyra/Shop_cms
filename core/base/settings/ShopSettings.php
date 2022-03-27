<?php


namespace core\base\settings;

use  core\base\settings\Settings;

class ShopSettings
{
    static private $_instance;
    private $baseSettings;
    private $test = [
        'name'=>'vasya'
    ];
    private $templateArr=[
        'text'=>['name','phone','address', 'price','short'],
        'textarea'=>['content','keywords','goods_content']
    ];

    static public function instance()
    {
        if(self::$_instance instanceof self){
            return self::$_instance;
        }
        self::$_instance = new self;
        self::$_instance->baseSettings = Settings::instance();
        $baseProperties = self::$_instance->baseSettings->clueProperties(get_class());
        self::$_instance->setProperty($baseProperties);

        return self::$_instance;
    }
    protected function setProperty($properties)
    {
        if($properties){
            foreach($properties as $name =>$propery ){
                $this->name = $properties;
            }
        }
    }

    static public function get($property)
    {
        return self::instance()->$property;
    }
    private function __clone()
    {
    }
    private function __construct()
    {

    }

}
