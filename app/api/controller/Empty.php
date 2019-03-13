<?php
/**
 * 默认控制器
 */

namespace app\api\controller;

use frame\db\My_Mysqli;

class My_Empty extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function output()
    {
        $m = My_Mysqli::getInstance();
        var_dump($m->fetchSql(false)->getRow('wp_posts', 'post_author, post_title', [
            ['id', 'OR', [
                ['id', 'between', [1, 10]]
            ]]
        ]));exit;

        $this->response['code'] = 404;
        $this->response['message'] = '乖，想撬锁？没门儿！';

        $this->jsonReturn();
    }
}