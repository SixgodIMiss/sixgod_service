<?php

namespace frame\core;


class My_Config
{
    private static $config = [];

    public static function init()
    {
        self::define();
        self::customizeConfig();
    }

    /**
     * define定义
     */
    private static function define()
    {
        // 项目路径
        defined('PROJECT_PATH') OR define('PROJECT_PATH', str_replace('\\', '/', dirname(__DIR__, 2)));
        // 框架
        defined('FRAME_PATH')   OR define('FRAME_PATH', PROJECT_PATH .'/frame');
        // 框架核心
        defined('FRAME_CORE_PATH')   OR define('FRAME_CORE_PATH', FRAME_PATH .'/core');
        // 框架DB
        defined('FRAME_DB_PATH')   OR define('FRAME_DB_PATH', FRAME_PATH .'/db');
        // 框架扩展
        defined('FRAME_EXTEND_PATH')   OR define('FRAME_EXTEND_PATH', FRAME_PATH .'/extend');
        // 项目配置
        defined('CONFIG_PATH')  OR define('CONFIG_PATH', PROJECT_PATH .'/config');
        // vendor
        defined('VENDOR_PATH')  OR define('VENDOR_PATH', PROJECT_PATH .'/vendor');
        // 日志
        defined('LOG_PATH')     OR define('LOG_PATH', PROJECT_PATH .'/wow/log');
        // API
        defined('API_PATH')     OR define('API_PATH', PROJECT_PATH .'/app/api');
        // 线上线下
        defined('ONLINE')       OR define('ONLINE', 'api.sixgodimiss.com' == $_SERVER['HTTP_HOST'] ? true : false);
    }

    /**
     * 项目自定义配置
     */
    private static function customizeConfig()
    {
        // 项目配置
        self::$config = include_once(CONFIG_PATH .'/config.php');
    }

    /**
     * 获取参数
     * @return array|mixed|string
     * @throws \Exception
     */
    public static function get()
    {
        $args = func_get_args();
        $config = self::$config;

        foreach ($args as $v) {
            if ($config) {
                $config = arr_get($config, $v, false);
            } else {
                throw new \Exception('配置参数查找失败');
            }
        }

        return $config;
    }
}

My_Config::init();