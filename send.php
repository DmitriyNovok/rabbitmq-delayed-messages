<?php

error_reporting(error_reporting() ^ E_DEPRECATED);

require_once __DIR__ . '/bootstrap/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

$connection = new AMQPStreamConnection(
    HOST,
    PORT,
    USER,
    PASSWORD,
    VHOST
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
$channel->queue_bind('delayed_queue', 'delayed_exchange');

$delay = 7;
while (true) {

    $msg = time();

    $message = new AMQPMessage(
        $msg,
        ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
    );

    $message->set(
        'application_headers',
        new AMQPTable([
            'x-delay' => $delay * 1000
        ])
    );

    $channel->basic_publish($message, 'delayed_exchange');

    printf(PHP_EOL. ' [x] Message sent: %s', $msg, PHP_EOL);
    sleep(1);
}
