<?php

namespace app\api\controller;

use frame\Response;


class Controller
{
    protected $result = [
        'index' => 1
    ];

    protected function __construct()
    {
        
    }

    public function jsonReturn()
    {
        echo Response::response($this->result);

        // 提高页面响应
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }
}