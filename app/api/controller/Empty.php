<?php
/**
 * 默认控制器
 */

namespace app\api\controller;


use frame\db\cache\My_Redis;
use frame\db\My_Elasticsearch;
use frame\db\cache\My_Memcached;
use frame\db\My_Mysqli;


/**
 * Class My_Empty
 * @package app\api\controller
 */
class My_Empty extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function output()
    {
        $m = My_Redis::getInstance();


        $this->response['code'] = 404;
        $this->response['message'] = '乖，想撬锁？没门儿！';

        $this->jsonReturn();
    }
}