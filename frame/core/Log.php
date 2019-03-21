<?php

namespace frame\core;

class Log
{
    private static $access_log = [
        'date' => '',
        'ip' => '',
        'url' => '', // url+method
        'params' => '', // 传参
        'return' => ''
    ];
    private static $access_path = '';

    /**
     * 设置日志参数
     * @param string $key
     * @param string $value
     */
    public static function setAccessLog($key, $value = '')
    {
        self::$access_path = self::$access_path ? self::$access_path : LOG_PATH . '/access/' . date('Y-m') . '/' . date('d') . '.txt';
        self::$access_log[$key] = $value;
    }

    /**
     * 生成日志格式
     * @return string 
     */
    protected static function formatAccessLog()
    {
        $result = '================================' . PHP_EOL;
        $result .= 'Date: ' . self::$access_log['date'] . PHP_EOL;
        $result .= 'IP: ' . self::$access_log['ip'] . PHP_EOL;
        $result .= 'Url: ' . self::$access_log['url'] . PHP_EOL;
        $result .= 'Params: ' . json_encode(self::$access_log['params'], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        $result .= 'Return: ' . json_encode(self::$access_log['return'], JSON_UNESCAPED_UNICODE);
        $result .= PHP_EOL . PHP_EOL;
        return $result;
    }

    /**
     * 存储日志
     */
    public static function storeAccessLog()
    {
        if (file_exists(dirname(self::$access_path))) {
            mkdir (dirname(self::$access_path), 0755, true);
        }

        file_put_contents(self::$access_path, self::formatAccessLog(), FILE_APPEND);
    }
}