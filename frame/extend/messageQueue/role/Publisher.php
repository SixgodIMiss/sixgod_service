<?php
/**
 * Author: Sixgod
 * Datetime: 2019/4/22 14:55
 * Description:
 * Mark:
 */

namespace frame\extend\MQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class Publisher extends RabbitMQ
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
    }

    public function publish($data)
    {
        $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        $msg = new AMQPMessage($data);

        $this->channel->basic_publish($msg, $this->real_exchange_name, $this->real_route_name);

        $this->close();
    }

    protected function close()
    {
        $this->channel->close();
        $this->conn->close();
    }
}