<?php

namespace frame\core;

class ErrorExceptionHandler
{
    protected static $response;
    protected static $log;
    public static function register()
    {
        set_error_handler([__CLASS__, 'handleError']);
        set_exception_handler([__CLASS__, 'handleException']);
        register_shutdown_function([__CLASS__, 'handleShutdown']);
    }

    /**
     * 错误
     * @param int $severity E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR ···
     * @param string $errstr
     * @param string $errfile
     * @param int $errline
     */
    public static function handleError($severity = E_ERROR, $errstr = '', $errfile = '', $errline = 0)
    {
        self::handleResponse($severity, $errstr, [
            'file' => $errfile,
            'line' => $errline,
        ]);
    }

    /**
     * 异常
     * @param \Throwable|NULL $e
     */
    public static function handleException(\Throwable $e = NULL)
    {
        if (!$e instanceof \Throwable) {
            $e = new \Exception($e);
        }

        self::handleResponse($e->getCode(), $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);
    }

    /**
     * 终止运行
     */
    public static function handleShutdown()
    {
        
    }

    /**
     * @param $code
     * @param $message
     * @param $data
     */
    protected static function handleResponse($code, $message, $data)
    {
        self::$response = [
            'code' => $code,
            'message' => $message
        ];

        self::$response['data'] = ONLINE ? [] : $data;

        echo Response::response(self::$response);

        // 提高页面响应
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        // 写日志
        $request = new Request();
        Log::setAccessLog('date', date('Y-m-d_H:i:s'));
        Log::setAccessLog('ip', $request->get('clientIp'));
        Log::setAccessLog('url', $request->get('domain') . $request->get('uri') .' '. $request->get('method'));
        Log::setAccessLog('params', $request->get('params'));
        Log::setAccessLog('response', self::$response);
        Log::storeAccessLog();

        exit(1);
    }
}

ErrorExceptionHandler::register();