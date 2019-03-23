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
        if (!extension_loaded('Redis') || !class_exists('Redis'))
            throw new \Exception('Redis not found');

        $this->client = new \Redis();
        $this->client->pconnect($config['host'], $config['port']);
        $this->client->auth($config['auth']);

    }

    /**
     * 这个地方要注意 是以链接为key 还是以数据库名为key
     * @param string $db
     * @return \Redis 此处返回的是原始 Redis 类
     * @throws \Exception
     */
    public static function getInstance($db = 'default')
    {
        if (! arr_get(self::$_instance, $db, null) instanceof self) {
            $config = My_Config::get('db', 'redis', $db);
            self::$_instance[$db] = new self($config);
        }

        return self::$_instance[$db]->client;
    }

}