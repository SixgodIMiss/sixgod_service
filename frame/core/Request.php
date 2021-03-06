<?php

namespace frame\core;

class Request
{
    public $request;
    protected $method;
    protected $url;
    protected $uri;
    protected $clientIp;
    protected $schema;
    protected $domain;
    protected $pathInfo;
    protected $path;  // 不含后缀
    protected $queryString;
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
        $this->init();
    }

    /**
     * 初始化
     */
    protected function init()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->clientIp = $this->get_client_ip();
        $this->schema = $_SERVER['REQUEST_SCHEME'];
        $this->domain = $_SERVER['HTTP_HOST'];
        $this->pathInfo = arr_get($_SERVER, 'PATH_INFO', '/');
        $this->path = preg_replace ('/(\..*)/', '', $this->pathInfo);
        $this->queryString = $_SERVER['QUERY_STRING'];
        $this->url = $this->domain . $this->path;
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->getMCA();
        $this->header = $this->getHeaders();
        $this->file = $_FILES;
        $this->params = $this->getParams();

        // 考虑要不要用
        // $this->session = $_SESSION; // session_start()
        // $this->cookie = $_COOKIE;

         $this->request = [
             'method' => $this->method,
             'clientIp' => $this->clientIp,
             'schema' => $this->schema,
             'domain' => $this->domain,
             'pathInfo' => $this->pathInfo,
             'path' => $this->path,
             'url' => $this->url,
             'queryString' => $this->queryString,
             'uri' => $this->uri,
             'module' => $this->module,
             'controller' => $this->controller,
             'action' => $this->action,
             'params' => $this->params,
//             'session' => $this->session,
//             'cookie' => $this->cookie,
             'header' => $this->header,
             'file' => $this->file,
         ];
    }

    /**
     * 获取request参数
     * @param $attribute
     * @param bool $default
     * @return mixed|string
     */
    public function get($attribute, $default = false)
    {
        return arr_get($this->request, $attribute, $default);
    }

    /**
     * 客户端IP
     */
    protected function get_client_ip()
    {
        if (isset($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])) {
            $ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])) {
            $ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];
        } elseif (isset($HTTP_SERVER_VARS["REMOTE_ADDR"])) {
            $ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        } else {
            $ip = "0.0.0.0";
        }
        return $ip;
    }

    /**
     * path info
     */
    protected function getMCA()
    {
        $path = explode('/', substr($this->path, 1));
        $this->module = arr_get($path, 0, 'api');
        $this->controller = arr_get($path, 1, 'My_Empty');
        $this->action = arr_get($path, 2, 'output');
    }

    /**
     * 解析传参
     */
    protected function getParams()
    {
        switch ($this->method)
        {
            case 'GET':
                $params = $_GET;
                break;
            case 'POST':
                // 这个地方有点怪 就是 CONTENT_TYPE => multipart/form-data; boundary=--------------------------980089571099425908981742
                // if ('multipart/form-data' == $this->header['content-type'] || 'application/x-www-form-urlencoded' == $this->header['content-type']) {

                if ('application/x-www-form-urlencoded' == $this->header['content-type'] ||
                    preg_match('/multipart\/form-data/', $this->header['content-type'])) {

                    $params = $_POST;

                } else {
                    $params = json_decode(file_get_contents('php://input'), true);
                }

                break;
            default:
                throw new \Exception('老铁，没问题', 200);
        }

        return $params;
    }

    /**
     * HTTP头属性
     * copy TP5
     */
    protected function getHeaders()
    {
        $header = [];
        if (function_exists('apache_request_headers') && $result = apache_request_headers()) {
            $header = $result;
        } else {
            $server = $_SERVER;
            foreach ($server as $key => $val) {
                if (0 === strpos($key, 'HTTP_')) {
                    $key          = str_replace('_', '-', strtolower(substr($key, 5)));
                    $header[$key] = $val;
                }
            }
            if (isset($server['CONTENT_TYPE'])) {
                $header['content-type'] = $server['CONTENT_TYPE'];
            }
            if (isset($server['CONTENT_LENGTH'])) {
                $header['content-length'] = $server['CONTENT_LENGTH'];
            }
        }
        return array_change_key_case($header);
    }

    /**
     * 路由
     * @param string $module
     * @param string $controller
     * @param string $action
     * @param array $params
     * @return mixed
     * @throws \Exception
     */
    public function route($module = '', $controller = '', $action = '', $params = [])
    {
        $module = $module ? $module : $this->module;
        $controller = $controller ? $controller : $this->controller;
        $action = $action ? $action : $this->action;
        
        $real_controller = '\\app\\'. $module .'\\controller\\'. $controller;
        if (!class_exists($real_controller)) {
            throw new \Exception('未定义模块');
        }
        if (!method_exists($real_controller, $action)) {
            throw new \Exception('未定义控制器');
        }

        // ???? call_user_func 跟 $this->xxx 冲突
        $c = new $real_controller();
        return $c->$action();
        
        // call_user_func([
        //     '\\app\\'. $module .'\\controller\\'. $controller,
        //     $action
        // ], $params);
        
    }
}
