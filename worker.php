<?php

error_reporting(error_reporting() ^ E_DEPRECATED);

require_once __DIR__ . '/bootstrap/bootstrap.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection(
    HOST,
    PORT,
    USER,
    PASSWORD,
    VHOST
);
$channel = $connection->channel();

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function (AMQPMessage $message) {
    printf(' [x] Message received: %s %s', 'delay '. time() - (int)$message->body, PHP_EOL);
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

$channel->close();
$connection->close();