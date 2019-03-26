<?php

namespace app\api\controller;

use frame\core\Log;
use frame\core\My_Config;
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
        if (preg_match('/'. My_Config::get('app', 'route', 'reg') .'/', $this->request->get('queryString')))
            throw new \Exception('非法字符');
    }

    /**
     * 接参数
     */
    protected function receive()
    {
        // 设置日志
        Log::setAccessLog('date', date('Y-m-d_H:i:s'));
        Log::setAccessLog('ip', $this->request->get('clientIp'));
        Log::setAccessLog('url', $this->request->get('domain') . $this->request->get('uri') .' '. $this->request->get('method'));
        Log::setAccessLog('params', $this->request->get('params'));
    }

    public function success()
    {
        $this->response['code'] = 200;
        $this->response['message'] = 'Success';
    }

    /**
     * 返回 json 数据
     */
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
        // 存储日志
        Log::setAccessLog('response', $this->response);
        Log::storeAccessLog();
    }
}