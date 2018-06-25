<?php 

$host = gethostname();
require_once "{$host}config.php";
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage; 


$exchange = 'post_exchange';
$queue = 'post_queue';

$conn = new AMQPStreamConnection(RABBIT_SERVER, RABBIT_PORT, RABBIT_USERNAME, RABBIT_PASSWORD, RABBIT_VHOST);
$ch = $conn->channel();
$ch->queue_declare($queue, false, true, false, false);
$ch->exchange_declare($exchange, 'direct', false, true, false);
$ch->queue_bind($queue, $exchange);

$message = $ch->basic_get($queue);

if (empty($message)) {
    $ch->close();
    $conn->close();
    echo 'COS NIE DZIALA';
    return false;
}

$ch->basic_ack($message->delivery_info['delivery_tag']);

echo " [*] Waiting for messages. To exit press CTRL+C\n";


$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
};

$ch->basic_consume($queue, '', false, true, false, false, $callback);

while (count($ch->callbacks)) {
    $ch->wait();
}

$ch->close();
$conn->close();
?>