<?PHP
$redisClient = new Redis();
$redisClient->connect( '127.0.0.1', 6379 ) or die('Problem with REDIS connection');
$redisClient->auth('zaq12wsx');
$redisClient->set('mykey', 'testowawartosc');
$redisClient->close();
?>
