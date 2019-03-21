<?php

namespace app\api\controller;

use frame\core\Response;
use frame\core\Request;


class Controller
{
    protected $request;
    protected $response = [
        'code' => 500,
        'message' => 'ServerException',
        'data' => []
    ];

    protected function __construct()
    {
        $this->request = new Request();
        $this->securityAccess();
        $this->receive();
    }

    /**
     * 反爬 加解密 过滤···
     */
    protected function securityAccess()
    {
        
    }

    /**
     * 接参数
     */
    protected function receive()
    {

    }

    public function success()
    {
        $this->response['code'] = 200;
        $this->response['message'] = 'Success';
    }

    public function jsonReturn()
    {
        echo Response::response($this->response);

        // 提高页面响应
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        $this->end();
    }

    /**
     * 一般用作写日志
     */
    protected function end()
    {

    }
}