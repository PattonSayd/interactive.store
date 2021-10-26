<?php

namespace core\base\settings;

class Settings
{
   
    static private $_instance;

    # put a slash(/) at the end of the path
    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => [

            ]
        ],
        'settings' => [
            'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => ''
        ],
        'user' =>[
            'path'=> 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [
                'hello' => 'index/walli/kenny',
            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];
    
    private $templates = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keywolds']
    ];
 
    public static function get($property)
    {
        return self::instance()->$property;  
    }

    static public function instance()
    {
        if(self::$_instance instanceof self)
            return self::$_instance;

        return self::$_instance = new self;

    }

    public function clueProperties($class)
    {
        $properties = [];

        foreach ($this as $name => $item) {
            
            $property = $class::get($name);

            $properties[$name] = $property;

            if (is_array($item) && is_array($property)) {

                $properties[$name] = $this->arrayMergeRecursive($this->$name , $property);

                continue;
            }

            if(!$property){
                
                $properties[$name] = $this->$name;
            }
        }
        return  $properties;
    }    

    public function arrayMergeRecursive(){

        $arrays = func_get_args();  
#          0:                 1:
        $base = array_shift($arrays);

        foreach ($arrays as $array) {

            foreach ($array as $key => $value) {

                if (is_array($value) && is_array($base[$key]))
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
               
                else {
                    if(is_int($key)) {

                        if (!in_array($value, $base)) 
                            array_push($base, $value);

                        continue;
                    }
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }


    private function __construct(){}
    private function clone(){}
}