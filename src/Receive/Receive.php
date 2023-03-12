<?php

namespace src\Receive;

use PhpAmqpLib\Message\AMQPMessage;
use src\Connection\Connection;

class Receive
{
    public function receive(): void
    {
        $connection = (new Connection())->connection();
        $channel = $connection->channel();

        echo "\n", '[*] Waiting for messages. To exit press CTRL+C', "\n";

        $callback = function (AMQPMessage $message) {
            printf(PHP_EOL. ' [x] Message received: %s', 'Delay '. time() - $message->body, PHP_EOL);
            $message->delivery_info['channel']->basic_ack($message->delivery_info['delivery_tag']);
        };

        $channel->basic_qos(null, 1, null);
        $channel->basic_consume(
            'delayed_queue',
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while(count($channel->callbacks)) {
            $channel->wait();
        }

        $this->closeConnection($channel, $connection);
    }

    private function closeConnection($channel, $connection): void
    {
        $channel->close();
        $connection->close();
    }
}