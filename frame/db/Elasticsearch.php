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
use mysql_xdevapi\Exception;

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
    private $index;    // 索引
    private $params = [];   // 查询参数
    private $result = [];   // 查询结果

    private static $_instance = [];

    /**
     * My_Elasticsearch constructor.
     * @param $index
     * @throws \Exception
     */
    private function __construct($index)
    {
        $hosts = My_Config::get('db', 'es');
        $this->client = ClientBuilder::create()->setHosts($hosts)->build();

        $this->host = $hosts;
        $this->index = $index;
        $this->params = [
            'index' => $this->index,
            'type'  => '_doc'
        ];
    }

    /**
     * @param string $index
     * @return self
     * @throws \Exception
     */
    public static function getInstance($index = 'customer')
    {
        if (! arr_get(self::$_instance, $index, false) instanceof self) {
            self::$_instance[$index] = new self($index);
        }

        return self::$_instance[$index];
    }

    /**
     * 查询
     * @param $params
     * @return array
     */
    public function search($params)
    {
        if (is_array($params)) {
            $search = $this->searchMulti($params);
        } elseif (!empty($params)) {
            $search = $this->searchId($params);
        } else {
            $search = [];
        }

        return $this->handle($search);
    }

    /**
     * 根据 ID 查数据
     * @param string $id
     * @return array
     */
    protected function searchId($id = '')
    {
        $params = $this->params;
        $params['id'] = $id;
        return $this->client->get($params);
    }

    /**
     * 根据查询条件查
     * @param array $condition
     * @return array
     */
    protected function searchMulti($condition = [])
    {
        $params = $this->params;
        $params['body'] = $condition;
        return $this->client->search($params);
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