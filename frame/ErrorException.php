<?php

namespace frame;

class ErrorException extends \Error
{
    public static $CODE = 0;
    public static $MESSAGE = 0;

    public function __construct($message = '', $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'error']);
        set_exception_handler([__CLASS__, 'exception']);
        register_shutdown_function([__CLASS__, 'shutdown']);
    }

    protected function error()
    {
        
    }
}
function my_error($no, $message, $file, $line)
{
    print_r($message);
    exit;
}
function my_exception(Throwable $e)
{
    print_r($e->getMessage());
    exit;
}
set_error_handler('my_error', E_ERROR | E_PARSE);
set_exception_handler('my_exception');