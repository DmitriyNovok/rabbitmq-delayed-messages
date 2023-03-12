<?php

namespace src\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Wire\AMQPTable;

class Connection
{
    protected string $channel;
    private $connection = null;

    public  function connection($channel = null)
    {
        $channel = $this->channel ?: $channel;

        if ($this->connection === null) {

            $connection = new AMQPStreamConnection(
                HOST, PORT, USER, PASSWORD, VHOST
            );

            $channel = $connection->channel();

            $channel->exchange_declare(
                'delayed_exchange',
                'x-delayed-message',
                false,
                true,
                false,
                false,
                false,
                new AMQPTable([
                    'x-delayed-type' => 'fanout'
                ])
            );

            $channel->queue_declare(
                'delayed_queue',
                false,
                true,
                false,
                false,
                false,
                new AMQPTable([
                    'x-dead-letter-exchange' => 'delayed'
                ])
            );

            $this->connection = $channel->queue_bind('delayed_queue', 'delayed_exchange');

            return $this->connection;
        }

        return $this->connection;
    }
}