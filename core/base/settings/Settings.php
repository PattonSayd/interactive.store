<?php

namespace core\base\settings;

use core\base\controller\Singleton;

class Settings
{
    use Singleton;

    # put a slash(/) at the end of the PATH
    private $routes = [
        'admin' => [
            'alias' => 'admin',
            'path' => 'core/admin/controller/',
            'hrUrl' => false,
            'routes' => [
                 'dash' => 'info' # url .../dash   connected InfoController
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
            'path'=> 'core/user/controller/',
            'hrUrl' => true,
            'routes' => [
                'hello' => 'info/page/first', # url = hello, controller = InfoController, inputMethod = hello, outputMethod = first
            ]
        ],
        'default' => [
            'controller' => 'IndexController',
            'inputMethod' => 'inputData',
            'outputMethod' => 'outputData'
        ]
    ];
    
    private $defaultTable = 'users';

    private $extension = 'core/admin/extension/';

    private $templates = [
        'text' => ['name', 'phone', 'address'],
        'textarea' => ['content', 'keywolds']
    ];

    private $projectTable = [
        'users' => ['name' => 'Пользователи', 'icon' => 'icon-user'],
        'likes' => ['name' => 'Реакции', 'icon' => 'icon-stars']
    ];

    private $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img', 'gallery_img' , 'new_gallery_img'],
        'vg-content' => ['content'],
    ];

    private $translate = [
        'name' => ['Название', 'Не болee 100 символов'],
        'content' => ['Описание', 'Не менее 50 символов'],
        'visible' => ['Видимость', 'Поумолчанию: показать'],
        'keywords' => ['Ключевые слова', 'Не менее 50 символов'],
        'filters' => ['Фильтры'],
        'menu_position' => ['Позиция меню'],
        'parent_id' => ['Родительская позиция'],
        'img' => ['Фоновый рисунок'],
        'gallery_img' => ['Галерея'],
        'new_gallery_img' => ['Новая галерея'],
    ];
    
    

    
 
    public static function get($property)
    {
        return self::instance()->$property;  
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
}