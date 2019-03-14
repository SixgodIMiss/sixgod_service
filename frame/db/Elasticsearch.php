<?php
/**
 * User: Sixgod
 * Datetime: 2019/3/14 13:40
 * Description:
 * Mark:
 */

namespace frame\db;

use Elasticsearch\ClientBuilder;
use function frame\arr_get;
use frame\My_Config;

class My_Elasticsearch
{
    private $host;
    private $client;
    private $config = [
        'ignore'  => [400, 404],           // 忽略异常
        'timeout' => 10,                   // 请求时间
        'connect_timeout' => 2,            // 连接超时
        'future'  => 'lazy',               // 异步请求 例: $get = $client->get($params); $result = $get->wait();
        'verify'  => 'path/to/cacert.pem', // 加密
        'verbose' => true,                 // 返回出数据外的详细输出
    ];
    private $index;

    private static $_instance = [];

    private function __construct($index)
    {
        $hosts = My_Config::get('db', 'es');
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();

        $this->host = $hosts;
        $this->index = $index;
    }

    /**
     * @param string $index
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance($index = 'company')
    {
        if (! arr_get(self::$_instance, $index, false) instanceof self) {
            self::$_instance[$index] = new self($index);
        }

        return self::$_instance[$index];
    }
}