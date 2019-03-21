<?php
/**
 * User: Sixgod
 * Datetime: 2019/3/14 13:40
 * Description:
 * Mark:
 */

namespace frame\db;

if (class_exists('Elasticsearch\ClientBuilder'))
    throw new \Exception('Elasticsearch not found');

use Elasticsearch\ClientBuilder;
use frame\core\My_Config;

/**
 * Class My_Elasticsearch
 * @package frame\db
 */
class My_Elasticsearch
{
    private $host;      // es地址
    private $config = [
        'ignore'  => [400, 404],           // 忽略异常
        'timeout' => 10,                   // 请求时间
        'connect_timeout' => 2,            // 连接超时
//        'future'  => 'lazy',               // 异步请求 例: $get = $client->get($params); $result = $get->wait();
//        'verify'  => 'path/to/cacert.pem', // 加密
//        'verbose' => true,                 // 返回出数据外的详细输出
    ];
    private $client;   // 客户端
    private $params = [];   // 查询参数
    private $result = [];   // 查询结果

    private static $_instance = [];

    /**
     * My_Elasticsearch constructor.
     * @param $host
     * @throws \Exception
     */
    private function __construct($host)
    {
        $this->client = ClientBuilder::create()->setHosts($host)->build();

        $this->host = $host;
    }

    /**
     * @param string $host
     * @return mixed
     * @throws \Exception
     */
    public static function getInstance($host = 'default')
    {
        if (! arr_get(self::$_instance, $host, false) instanceof self) {
            $config = My_Config::get('db', 'es', $host);

            self::$_instance[$host] = new self($config);
        }

        return self::$_instance[$host];
    }

    /**
     * 查询
     * @param $params
     * @return array
     */
    public function search($params)
    {
        $this->params = $params;
        if (array_keys($params, 'id')) {
            $search = $this->searchMulti();
        } elseif (!empty($params)) {
            $search = $this->searchId();
        } else {
            $search = [];
        }

        return $this->handle($search);
    }

    /**
     * 根据 ID 查数据
     * @return array
     */
    protected function searchId()
    {
        return $this->client->get($this->params);
    }

    /**
     * 根据查询条件查
     * @return array
     */
    protected function searchMulti()
    {
        return $this->client->search($this->params);
    }

    /**
     * 格式化结果
     * @param array $result
     * @return array
     */
    protected function handle($result = [])
    {
        $this->result = $result;
        return $this->result;
    }

    /**
     * 插入数据
     * @param array $data
     * @return bool
     */
    public function insert($data = [])
    {
        if (!empty($data)) {
            $params = $this->params;
            $params['id'] = 1;
            $params['body'] = $data;
            $this->client->indices()->create([
                'index' => 'test'
            ]);
        }
        return true;
    }

    public function create()
    {

    }
}