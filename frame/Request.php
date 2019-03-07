<?php

namespace frame;

class Request
{
    protected $request;
    protected $method;
    protected $url;
    protected $client_ip;
    protected $schema;
    protected $domain;
    protected $pathinfo;
    protected $path;  // 不含后缀
    protected $module;
    protected $controller;
    protected $action;
    protected $params = [];
    protected $session = [];
    protected $file = [];
    protected $cookie = [];
    protected $header = [];

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->client_ip = $this->get_client_ip();
        $this->schema = $_SERVER['REQUEST_SCHEME'];
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->pathinfo = $_SERVER['PATH_INFO'];
        $this->path = preg_replace ('/(\..*)/', '', $this->pathinfo);
        $this->url = $this->domain . $this->path;
        $this->getMCA();
        $this->params = $this->getParams();
        $this->session = $_SESSION;
        $this->cookie = $_COOKIE;
        $this->header = $this->getHeaders();
        $this->file = $_FILES;

        $this->request = [
            'method' => $this->method,
            'client_ip' => $this->client_ip,
            'schema' => $this->schema,
            'domain' => $this->domain,
            'pathinfo' => $this->pathinfo,
            'path' => $this->path,
            'url' => $this->url,
            'module' => $this->module,
            'controller' => $this->controller,
            'action' => $this->action,
            'url' => $this->url,
            'params' => $this->params,
            'session' => $this->session,
            'cookie' => $this->cookie,
            'header' => $this->header,
            'cookie' => $this->cookie,
            'file' => $this->file,
        ];
    }

    /**
     * 获取request参数
     */
    public function get($attribute)
    {
        return arr_get($this->request, $attribute, false);
    }

    /**
     * 客户端IP
     */
    protected function get_client_ip()
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * path info
     */
    protected function getMCA()
    {
        $path = explode('/', substr($this->path, 1));
        $this->module = arr_get($path, 0, 'Empty');
        $this->controller = arr_get($path, 1, 'Empty');
        $this->action = arr_get($path, 2, 'output');
    }

    /**
     * 解析传参
     */
    protected function getParams()
    {
        return file_get_contents('php://input');
    }

    /**
     * HTTP头属性
     */
    protected function getHeaders()
    {
        return [
            
        ];
    }
}