<?php
/**
 * Author: Sixgod
 * Datetime: 2019-03-23 19:25
 * Description:
 * Mark:
 */

namespace frame\extend\ali;


use frame\core\My_Config;
use OSS\OssClient;

class My_Oss
{
    private $client;
    private static $_instance;
    public static $DEFAULT_BUCKET = '';
    private $bucket = '';
    private $sourcePath = '';

    /**
     * My_Oss constructor.
     * @param $oss
     * @throws \OSS\Core\OssException
     */
    private function __construct($oss)
    {
        $this->client = new OssClient($oss['accessKeyId'], $oss['accessKeySecret'], $oss['endpoint']);
    }

    /**
     * @param string $oss
     * @return self
     * @throws \Exception
     * @throws \OSS\Core\OssException
     */
    public static function getInstance($oss = 'ali')
    {
        if (! arr_get(self::$_instance, $oss, null) instanceof self) {
            $config = My_Config::get($oss, 'oss');
            if (empty(arr_get($config, 'endpoint', ''))) throw new \Exception('Oss no endpoint');

            self::$_instance[$oss] = new self($config);
        }

        return self::$_instance[$oss];
    }

    /**
     * 设置 bucket
     * @param null $bucket
     * @return $this
     */
    public function bucket($bucket = null)
    {
        $this->bucket = $bucket ? $bucket : self::$DEFAULT_BUCKET;
        return $this;
    }

    /**
     * 文件路径
     * @param string $filename
     * @param int $type
     * @return $this
     */
    public function path($filename = '', $type = 1)
    {
        switch ($type)
        {
            case 1:
                $this->sourcePath = 'hello/'. $filename;
                break;
            default:
                $this->sourcePath = $filename;
                break;
        }

        return $this;
    }

    /**
     * 获取资源
     * @return string
     * @throws \Exception
     * @throws \OSS\Core\OssException
     */
    public function getSource()
    {
        $timeout = 3600;
        $options = [];
        // 获取图片所需参数
//        $options = [OssClient::OSS_PROCESS => "image/resize,m_lfit,h_1920,w_1080"];

        return $this->client->signUrl($this->bucket, $this->sourcePath, $timeout, 'GET', $options);
    }

    /**
     * 上传文件
     * @param string $localFilepath
     * @return null
     * @throws \OSS\Core\OssException
     */
    public function upload($localFilepath = '')
    {
        if (!file_exists($localFilepath))
            throw new \Exception('oss上传文件不存在');

        return $this->client->uploadFile($this->bucket, $this->sourcePath, $localFilepath);
    }
}

// 测试用法
// $oss = My_Oss::getInstance();
// $oss->bucket()->path()->getSource();