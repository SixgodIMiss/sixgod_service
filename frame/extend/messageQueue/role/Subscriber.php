<?php
/**
 * Author: Sixgod
 * Datetime: 2019/4/22 15:11
 * Description:
 * Mark:
 */

namespace frame\extend\MQ;


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;

class Subscriber extends RabbitMQ
{
    /**
     * @var AMQPStreamConnection
     */
    private $conn;
    /**
     * @var AMQPChannel
     */
    private $channel;
    private $real_exchange_name;
    private $real_route_name;
    private $real_queue_name;

    public function connect($host = '', $port = 0, $login = '', $password = '', $vhost = '')
    {
        // TODO: Implement connect() method.

        $this->conn = $this->setConnect($host, $port, $login, $password, $vhost);
//        $this->conn->isConnected();
    }

    public function channel()
    {
        // TODO: Implement channel() method.

        $this->channel = $this->setChannel($this->conn);
    }

    public function exchange($exchange_name = '')
    {
        // TODO: Implement exchange() method.

        $this->real_exchange_name = empty($exchange_name) ? $this->exchange_name : $exchange_name;
        $this->channel->exchange_declare($this->real_exchange_name, AMQP_EX_TYPE_DIRECT, false, true, false);

    }

    public function route($route_name = '')
    {
        // TODO: Implement route() method.

        $this->real_route_name = $this->setRoute($route_name);
    }

    public function queue($queue_name = '')
    {
        // TODO: Implement queue() method.

        $this->real_queue_name = $this->setQueue($queue_name);

        $this->channel->queue_bind($this->real_queue_name, $this->real_exchange_name, $this->real_route_name);
        $this->channel->queue_declare($this->queue_name, false, true, false, false);
    }

    public function subscribe()
    {
        $callback = function ($msg) {
            echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
        };

        $this->channel->basic_consume($this->real_queue_name, '', false, true, false, false, $callback);

        $this->close();
    }

    protected function callback($msg)
    {
        echo ' [x] ', $msg->delivery_info['routing_key'], ':', $msg->body, "\n";
    }

    protected function close()
    {
        $this->channel->close();
        $this->conn->close();
    }
}