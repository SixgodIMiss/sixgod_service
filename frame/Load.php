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


use frame\core\Request;


/**
 * 加载类
 * Class Loader
 * @package frame
 */
class Loader
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public static function run()
    {
        // 加载文件
        self::load();

        // 路由
        $request = new Request();
        return $request->route();
    }

    protected static function load()
    {
        self::loadFrame();

        self::loadVendor();

        self::loadApp();
    }

    /**
     * 加载frame
     */
    protected static function loadFrame()
    {
        require_once __DIR__ . '/core/Helper.php';
        require_once __DIR__ . '/core/Config.php';

        // 加载核心
        load_file(my_scandir(FRAME_CORE_PATH));

        // DB
        load_file(my_scandir(FRAME_DB_PATH));

        // 扩展
        load_file(my_scandir(FRAME_EXTEND_PATH));
    }

    /**
     * 加载vendor
     */
    protected static function loadVendor()
    {
        include VENDOR_PATH . '/autoload.php';
    }

    /**
     * 加载APP
     */
    protected static function loadApp()
    {
        load_file(my_scandir(API_PATH));
    }

    /**
     * 自动加载类
     */
    protected function autoLoad()
    {
        // spl_autoload_register(function($class_name){
        //     require_once PROJECT_PATH .'\\'. $class_name . '.php';
        //     var_dump(PROJECT_PATH .'\\'. $class_name . '.php');
        // });
    }

}

Loader::run();