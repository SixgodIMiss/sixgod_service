<?php

namespace frame;

/**
 * 遍历文件夹
 * @param $path string 绝对路径
 */
function my_scandir($path) 
{
    $files = [];
    if (is_dir($path)) {
        if ($handle = opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        $files[$file] = my_scandir($path . '/' . $file);
                    } else {
                        array_push($files, $path . '/' . $file);
                    }
                }
            }
        }
        closedir($handle);
    }
    return $files;
}

/**
 * 加载文件
 * @param $files array() 文件数组
 */
function load_file($files)
{
    if (is_array($files)) {
        foreach ($files as $f) {
            load_file($f);
        }
    } else {
        require_once $files;
    }
}

/**
 * 获取数组元素
 */
function arr_get(array $arr, $key, $default = '')
{
    return isset($arr[$key]) && $arr[$key] ? $arr[$key] : $default;
}