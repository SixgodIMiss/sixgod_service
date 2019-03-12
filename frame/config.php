<?php

namespace frame;

/**
 * 自定义项目配置参数
 */
// 项目根目录
define(PROJECT_PATH, str_replace('\\', '/', dirname(__DIR__)));
define(FRAME_PATH, __DIR__);
define(CONFIG_PATH, PROJECT_PATH .'/config');
define(VENDOR_PATH, PROJECT_PATH .'/vendor');
define(LOG_PATH, PROJECT_PATH .'/wow/log');
define(API_PATH, PROJECT_PATH .'/app/api');