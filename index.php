<?php
/**
 * author: SixgodIMiss
 * date: 2019-03-07
 * For: Tool API
 */

 /**
  * ini_set($varname, $newvalue);
  * http://php.net/manual/zh/book.info.php
  *
  * 先自定义PHP配置参数
  */
// 设置中国时区
date_default_timezone_set('PRC');
error_reporting(E_ERROR | E_PARSE);

/**
 * 加载项目
 */
require __DIR__ .'/frame/Load.php';