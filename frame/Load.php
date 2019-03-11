<?php
/**
 * frame load
 * 
 * 错误异常
 * 方法类
 * 路由
 * 日志
 * 
 */

namespace frame;

require_once __DIR__ .'/ErrorException.php';
require_once __DIR__ .'/Config.php';
require_once __DIR__ .'/Helper.php';
require_once __DIR__ .'/Request.php';
require_once __DIR__ .'/Response.php';

/**
 * app loader
 * 
 * 控制器
 * 工具类
 * 
 */

class Loader
{
    /**
     * 启动
     */
    public static function run()
    {
        // 注册vendor
        self::vendor();

        // 注册控制器
        self::register(my_scandir(API_PATH));

        // 路由
        $request = new Request();
        $request->route();
    }

    /**
     * 加载文件
     * @param array $files 文件数组
     */
    protected static function register($files)
    {
        if (is_array($files)) {
            foreach ($files as $f) {
                self::register($f);
            }
        } else {
            require_once $files;
        }
    }

    /**
     * 自动加载类
     */
    protected static function autoRegister()
    {
        // spl_autoload_register(function($class_name){
        //     require_once PROJECT_PATH .'\\'. $class_name . '.php';
        //     var_dump(PROJECT_PATH .'\\'. $class_name . '.php');
        // });
    }

    /**
     * 加载vendor
     */
    protected static function vendor()
    {
        require_once VENDOR_PATH . '/autoload.php';
    }
}

Loader::run();