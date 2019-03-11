<?php

namespace frame;

class Response
{
    /**
     * @param array 返回数据
     * @param int status code
     * @param string message
     * @param array 响应头 [
     *      'content-type' => 'application/json | text/html',
     *      ''
     * ]
     */
    public static function response($data = [], $code = 200, $message = '', $header = [])
    {
        $default = [
            'code' => 500,
            'message' => 'Server Exception',
            'data' => []
        ];
        $result = [
            'code' => $code ? $code : $default['code'],
            'message' => $message ? $message : $default['message'],
            'data' => empty($data) ? $default['data']: $data
        ];

        if (!headers_sent() && !empty($header)) {
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