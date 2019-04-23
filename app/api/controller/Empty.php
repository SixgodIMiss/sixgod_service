<?php
/**
 * 默认控制器
 */

namespace app\api\controller;


use frame\db\cache\My_Redis;
use frame\db\My_Elasticsearch;
use frame\db\cache\My_Memcached;
use frame\db\My_Mysqli;
use frame\extend\MQ\Publisher;
use frame\extend\MQ\Subscriber;
use frame\extend\MQ\test;


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
//        $publish = new Publisher();
//        $publish->connect();
//        $publish->channel();
//        $publish->exchange();
//        $publish->route();
//        $publish->queue();
//        $publish->publish([
//            'key' => 123
//        ]);
//
//        $subscribe = new Subscriber();
//        $subscribe->connect();
//        $subscribe->channel();
//        $subscribe->exchange();
//        $subscribe->route();
//        $subscribe->queue();
//        $subscribe->subscribe();

        $this->response['code'] = 404;
        $this->response['message'] = '乖，想撬锁？没门儿！';
        $this->response['data'] = $this->request->get('params');

        $this->jsonReturn();
    }
}