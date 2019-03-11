<?php

namespace frame;

class Helper
{
    /**
     * 遍历文件夹
     * @param $path string 绝对路径
     */
    public static function my_scandir($path) 
    {
        $files = [];
        if (is_dir($path)) {
            if ($handle = opendir($path)) {
                while (($file = readdir($handle)) !== false) {
                    if ($file != '.' && $file != '..') {
                        if (is_dir($path . '/' . $file)) {
                            $files[] = self::my_scandir($path . '/' . $file);
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
     * 获取数组元素
     */
    public static function arr_get(array $arr, $key, $default = '')
    {
        return isset($arr[$key]) && $arr[$key] ? $arr[$key] : $default;
    }
}