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
        self::$response = [
            'code' => $severity,
            'message' => $errstr,
            'data' => [
                'file' => $errfile,
                'line' => $errline,
            ]
        ];
        
        echo Response::response(self::$response);
        exit(1);
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
        self::$response = [
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
            'data' => [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
        ];

        echo Response::response(self::$response);
        exit(1);
    }

    /**
     * 终止运行
     */
    public static function handleShutdown()
    {
        
    }
}

ErrorExceptionHandler::register();