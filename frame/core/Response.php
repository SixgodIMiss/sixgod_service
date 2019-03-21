<?php

namespace frame\core;

class Response
{
    /**
     * @param array 返回数据
     * @param int status code
     * @param array 响应头 [
     *      'content-type' => 'application/json | text/html',
     *      ···
     * ]
     * @return string|mixed
     */
    public static function response(
        $data = [],
        $code = 200,
        $header = [
            'Content-type' => 'application/json; charset=UTF-8'
        ]
    ) {
        
        $default = [
            'code' => 500,
            'message' => 'Server Exception',
            'data' => []
        ];
        $result = [
            'code' => arr_get($data, 'code', $default['code']),
            'message' => arr_get($data, 'message', $default['message']),
            'data' => arr_get($data, 'data', $default['data'])
        ];

        if (!headers_sent()) {
            // 发送状态码
            http_response_code($code);
            // 发送头部信息
            foreach ($header as $name => $val) {
                header($name . (!is_null($val) ? ':' . $val : ''));
            }
        }

        return json_encode($result, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}