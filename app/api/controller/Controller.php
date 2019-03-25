<?php

namespace app\api\controller;

use frame\core\Response;
use frame\core\Request;


class Controller
{
    protected $request;
    protected $params;
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
        if (preg_match('/^[a-zA-Z0-9\/]+$/', $this->request->get('pathinfo')))
            throw new \Exception('非法字符');
    }

    /**
     * 接参数
     */
    protected function receive()
    {
        $method = $this->request->get('method');

        $params = $this->request->get('params');


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