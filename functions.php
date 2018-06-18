<?PHP
// Include config file
$host = gethostname();
require_once "{$host}config.php";
require_once "session.php";


function session_check()
{
	echo 'Session check> ';
	if(!isset($_COOKIE['MYSID'])) {
                $token=md5(rand(0,1000000000));
                setcookie('MYSID', $token);
                $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token, $user,0);
        }
        else
                $token="PHPREDIS_SESSION:{$_COOKIE['PHPSESSID']}";
	if (isset($_POST['username']) and isset($_POST['password']))
                return authorize($_POST['username'],$_POST['password'],$token);
        else
                return authorize(NULL,NULL,$token);
}
function authorize($username,$password, $token)
{
	echo 'Authorize> ';
        if ($username!=NULL and $password!=NULL)
        {
                if ($username=="kalkos" and $password=="qwerty")
                        $user=array('id'=>333,'username'=>$username);
                else
                        $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token,$user,"0");
                return $user;
        }
        else
                return redis_get_json($token);
}
function logout($user)
{
	echo 'Logout';
        $token=$_COOKIE['MYSID'];
        $user=array('id'=>NULL,'username'=>"Visitor");
        redis_set_json($token,$user,"0");
        return $user;
}
function redis_set_json($key, $val, $expire)
{
	echo'redis_set_jeson> ';
        $redisClient = new Redis();
        $redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD);
        $value=json_encode($val);
        if ($expire > 0)
                $redisClient->setex($key, $expire, $value );
        else
                $redisClient->set($key, $value);
        $redisClient->close();
}
function redis_get_json($key)
{
	echo 'redis_get_json> ';

	$redisClient = new Redis();
        $redisClient->connect( REDIS_SERVER, REDIS_PORT );
	$redisClient->auth(REDIS_PASSWORD);
	
	// echo session_decode($redisClient->get($key));
	$ret = Session::unserialize($redisClient->get($key));
	// $ret=json_decode($redisClient->get($key),true);
        $redisClient->close();
	return $ret;
}
function show_menu($user)
{
	echo $user;
	echo '<pre>'; print_r($user); echo '</pre>';
	
	
echo '
<nav class="uk-navbar">
    <ul class="uk-navbar-nav">';
                if ($user==NULL or $user['username']==NULL)
                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
                else
                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>';
echo '        <li class="uk-parent"><a href="index.php">Home</a></li>
    </ul>
</nav>';
}
?>
