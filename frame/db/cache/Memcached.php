<?php
/**
 * Author: Sixgod
 * Datetime: 2019/3/15 10:49
 * Description:
 * Mark:
 */

namespace frame\db\cache;


use frame\core\My_Config;


// 此处应用适配器模式操作 再议
if (extension_loaded('Memcached') && class_exists('Memcached')) {
    $memcached = new \Memcached();
} elseif (extension_loaded('Memcache') && class_exists('Memcache')) {

} else {
    throw new \Exception('Memcached not found');
}

class My_Memcached
{
    /**
     * 集群
     * /bin/memcached -d -p 11211 -m 1024 -l 127.0.0.1
     * /bin/memcached -d -p 11212 -m 1024 -l 127.0.0.1
     * /bin/memcached -d -p 11213 -m 1024 -l 127.0.0.1
     */

    private $memcached;
    private static $_instance = [];

    private function __construct($config)
    {
        $this->memcached = new \Memcached();
        // 连接集群
        foreach ($config as $c) {
            $this->memcached->addServer($c['host'], $c['port']);
        }

        print_r($this->getStats());exit;
    }

    public static function getInstance($cache = 'default')
    {
        if (! arr_get(self::$_instance, $cache, false) instanceof self) {
            $config = My_Config::get('db', 'memcached', $cache);

            self::$_instance[$cache] = new self($config);
        }

        return self::$_instance[$cache];
    }

    public function getStats()
    {
        return $this->memcached->getStats();
    }
}
