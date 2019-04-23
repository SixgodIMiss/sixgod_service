<?php
/**
 * Author: Sixgod
 * Datetime: 2019/4/22 14:26
 * Description:
 * Mark: 注意各项参数到底是 true 还是 false
 */


namespace frame\extend\MQ;


use PhpAmqpLib\Connection\AMQPStreamConnection;


abstract class RabbitMQ
{
    protected $host = '127.0.0.1';
    protected $port = 5672;
    protected $login = 'guest'; // 注意用户角色和权限设定问题
    protected $password = 'guest';
    protected $vhost = '/';
    protected $exchange_name = 'exchange_name';
    protected $route_name = 'route_name';
    protected $queue_name = 'queue_name';

    /**
     * 连接（订阅发布不共用连接）
     * @param string $host
     * @param int $port
     * @param string $login
     * @param string $password
     * @param $vhost
     * @return mixed
     */
    protected abstract function connect($host = '', $port = 0, $login = '', $password = '', $vhost);
    /**
     * @param string $host
     * @param int $port
     * @param string $login
     * @param string $password
     * @param string $vhost
     * @return AMQPStreamConnection
     */
    protected function setConnect($host = '', $port = 0, $login = '', $password = '', $vhost = '')
    {
        $host = empty($host) ? $this->host : $host;
        $port = empty($port) ? $this->port : $port;
        $login = empty($login) ? $this->login : $login;
        $password = empty($password) ? $this->password : $password;
        $vhost = empty($vhost) ? $this->vhost : $vhost;

        return new AMQPStreamConnection($host, $port, $login, $password, $vhost);
    }

    /**
     * 通道
     * @return mixed
     */
    protected abstract function channel();
    protected function setChannel(AMQPStreamConnection $conn)
    {
        return $conn->channel();
    }

    /**
     * 交换机
     * @param string $exchange_name
     * @return mixed
     */
    protected abstract function exchange($exchange_name = '');

    /**
     * 路由
     * @param string $route_name
     * @return mixed
     */
    protected abstract function route($route_name = '');
    protected function setRoute($route_name)
    {
        return empty($route_name) ? $this->route_name : $route_name;
    }

    /**
     * 队列
     * @param string $queue_name
     * @return mixed
     */
    protected abstract function queue($queue_name = '');
    protected function setQueue($queue_name)
    {
        return empty($queue_name) ? $this->queue_name : $queue_name;
    }

    protected abstract function close();
}