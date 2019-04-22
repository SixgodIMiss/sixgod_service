<?php

//namespace frame\core\helper;

/**
 * 遍历文件夹
 * @param string $path 绝对路径
 * @return array
 */
function my_scandir($path) 
{
    $files = [];
    if (is_dir($path)) {
        if ($handle = opendir($path)) {
            while (($file = readdir($handle)) !== false) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        $files[] = my_scandir($path . '/' . $file);
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
 * @param array $arr
 * @param string $key
 * @param string $default
 * @return mixed|string
 */
function arr_get(array $arr, $key = '', $default = '')
{
    return isset($arr[$key]) && $arr[$key] ? $arr[$key] : $default;
}

/**
 * 加载文件
 * @param array $file
 * @param string $type
 */
function load_file($file, $type = 'require_once')
{
    if (is_array($file)) {
        foreach ($file as $f) {
            load_file($f);
        }
    } else {
        switch ($type) {
            case 'require_once':
                require_once $file;
                break;
            case 'require':
                require $file;
                break;
            case 'include_once':
                include_once $file;
                break;
            case 'include':
                include $file;
                break;
            default:
                require_once $file;
                break;
        }
    }
}

/**
 * 替换 range 函数，无限流循环
 * foreach (xrange(1, 1000000) as $num) {
 *  echo $num, "\n";
 * }
 * @param int $start
 * @param int $numbers
 * @param int $step
 * @return Generator
 */
function xrange($start = 0, $numbers = 0, $step = 1)
{
    for ($i = $start; $i <= $numbers; $i += $step) {
        yield $i;
    }
}