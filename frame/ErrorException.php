<?php

namespace frame;

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