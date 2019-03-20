<?php
/**
 * Created by PhpStorm.
 * User: SixgodIMiss
 * Date: 2019/3/13
 * Time: 10:54
 */

file_exists(__DIR__ . '/databases.php') or die('先创建 databases.php 文件');

return [
    'app' => require_once(__DIR__ . '/app.php'),
    'db'  => require_once(__DIR__ . '/databases.php')
];