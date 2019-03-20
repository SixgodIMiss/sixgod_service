<?php
/**
 * 默认控制器
 */

namespace app\api\controller;


use frame\db\My_Elasticsearch;

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
        $es = My_Elasticsearch::getInstance('customer');
        var_dump($es->insert([
            'name' => 'J'
        ]));exit;

        $this->response['code'] = 404;
        $this->response['message'] = '乖，想撬锁？没门儿！';

        $this->jsonReturn();
    }
}