<?PHP
$redisClient = new Redis();
$redisClient->connect( '127.0.0.1', 6379 );
$redisClient->auth('zaq12wsx');

echo "\r\n";
echo $redisClient->get('mykey');
echo "\r\n\r\n";
$redisClient->close();
?>
