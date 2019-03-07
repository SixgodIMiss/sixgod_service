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
        $this->request = $this;
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
    }

    public function get()
    {
        return $_SERVER;
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

    protected function getHeaders()
    {
        return [
            
        ];
    }
}