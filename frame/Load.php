<?php

namespace frame;

/**
 * 自定义项目配置参数
 */
// 项目根目录
define(PROJECT_PATH, str_replace('\\', '/', __DIR__));
define(FRAME_PATH, __DIR__);
define(CONFIG_PATH, PROJECT_PATH .'/vendor');
define(VENDOR_PATH, PROJECT_PATH .'/config');
define(API_PATH, PROJECT_PATH .'/app/api');

/**
 * frame load
 * 
 * 错误异常
 * 方法类
 * 路由
 * 日志
 * 
 */
require_once __DIR__ .'/ErrorException.php';
require_once __DIR__ .'/Helper.php';
require_once __DIR__ .'/Request.php';

$request = new Request();
// var_dump($request->get('domain'));

/**
 * app load
 * 
 * 控制器
 * 工具类
 * 
 */
load_file(my_scandir(API_PATH));