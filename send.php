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

$message = new AMQPMessage('wiadmosc z tego tutaj glownego folderu');

$ch->basic_publish($message, $exchange);

$ch->close();
$conn->close();

echo 'wyslane'



?>