<?php

namespace src\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class Publisher
{
    public int $delay = 0;
    private int $max_priority = MAX_PRIORITY;

    public function publisher()
    {
        $this->delay = 7;

        while (true) {
            $msg = time();
            $message = new AMQPMessage(
                $msg,
                ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
            );

            $message->set(
                'application_headers',
                new AMQPTable([
                    'x-delay' => $this->delay * 1000
                ])
            );

            $channel->basic_publish($message, 'delayed_exchange');

            printf(PHP_EOL. ' [x] Message sent: %s', $msg, PHP_EOL);
            sleep(1);
        }
    }

}