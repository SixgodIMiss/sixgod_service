<?php
/**
 * User: Sixgod
 * Datetime: 2019/3/14 13:40
 * Description:
 * Mark:
 */

namespace frame\db;

use function frame\arr_get;
use frame\My_Config;

class My_Elasticsearch
{

    private static $_instance = [];

    private function __construct()
    {

    }

    /**
     * @param string $db
     * @return My_Mysqli
     * @throws \Exception
     */
    public static function getInstance($db = 'default')
    {
        if (! arr_get(self::$_instance, $db, false) instanceof self)
        {
            $config = My_Config::get('db', $db);
            self::$_instance[$db] = new self($config['host'], $config['username'], $config['password'], $config['dbname'], $config['port'], null);
        }
        return self::$_instance[$db];
    }
}