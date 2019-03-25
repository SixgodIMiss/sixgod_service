<?php
/**
 * Author: Sixgod
 * Datetime: 2019-03-23 15:12
 * Description: 此处应用适配器模式去适配 Memcached | Memcache 再议
 * Mark:
 */

namespace frame\db\cache;


use frame\core\My_Config;

class My_Memcached
{
    /**
     * 服务集群
     * /bin/memcached -d -p 11211 -m 1024 -l 127.0.0.1
     * /bin/memcached -d -p 11212 -m 1024 -l 127.0.0.1
     * /bin/memcached -d -p 11213 -m 1024 -l 127.0.0.1
     */

    protected $client;
    private static $_instance = [];

    /**
     * 区分 Memcached Memcache
     * Mem constructor.
     * @param mixed $config
     * @throws \Exception
     */
    private function __construct($config)
    {
        if (extension_loaded('Memcached') && class_exists('Memcached')) {
            $this->client = new \Memcached();

            // 连接集群
            foreach ($config as $c) {
                $this->client->addServer($c['host'], $c['port']);
            }

        } elseif (extension_loaded('Memcache') && class_exists('Memcache')) {
            $this->client = new \Memcache();
        } else {
            throw new \Exception('Memcached or Memcache not found');
        }
    }

    /**
     * @param string $cache
     * @return My_Memcached 此时需要封装通用方法
     * @throws \Exception
     */
    public static function getInstance($cache = 'default')
    {
        if (! arr_get(self::$_instance, $cache, false) instanceof self) {
            $config = My_Config::get('db', 'memcached', $cache);
            if (empty(arr_get($config, 'host', ''))) throw new \Exception('Memcached no host');

            self::$_instance[$cache] = new self($config);
        }

        return self::$_instance[$cache];
    }
}