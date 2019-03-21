<?php
/**
 * Author: Sixgod
 * Datetime: 2019/3/21 17:09
 * Description:
 * Mark:
 */

namespace frame\db\cache;


use frame\core\My_Config;

class My_Redis
{
    protected $client;
    private static $_instance = [];

    /**
     * My_Redis constructor.
     * @param $config
     */
    protected function __construct($config)
    {
        $this->client = new \Redis();
        $this->client->pconnect($config['host'], $config['port']);
        $this->client->auth($config['password']);

    }

    /**
     * @param string $db 这个地方要注意 是以链接为key 还是以数据库名为key
     * @throws \Exception
     */
    public static function getInstance($db = 'default')
    {
        if (! arr_get(self::$_instance, $db, null) instanceof self) {
            $config = My_Config::get('db', 'redis', 0);

            self::$_instance[$db] = new self($config);
        }
    }
}