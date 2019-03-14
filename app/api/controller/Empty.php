<?php
/**
 * 默认控制器
 */

namespace app\api\controller;


use frame\db\My_Elasticsearch;

class My_Empty extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function output()
    {
        $es = My_Elasticsearch::getInstance('company');
        var_dump($es);exit;

        $this->response['code'] = 404;
        $this->response['message'] = '乖，想撬锁？没门儿！';

        $this->jsonReturn();
    }
}