<?PHP
    $host = gethostname();
    require_once "{$host}config.php";


function session_check()
{
        if(!isset($_COOKIE['MYSID'])) {
                $token=md5(rand(0,1000000000));
                setcookie('MYSID', $token);
                $user=array('id'=>NULL,'username'=>"Visitor");
                redis_set_json($token, $user,0);
        }
        else
                $token=$_COOKIE['MYSID'];
        if (isset($_POST['username']) and isset($_POST['loggedin']))
                return authorize($_POST['username'],$_POST['loggedin'],$token);
        else
                return authorize(NULL,NULL,$token);
}
function authorize($username,$password, $token)
{
        if ($username!=NULL and $password!=NULL)
        {
                if ($username=="kalkos" and $password)
                        $user=array('username'=>$username);
                else
                        $user=array('username'=>"Visitor");
                redis_set_json($token,$user,"0");
                return $user;
        }
        else
                return redis_get_json($token);
}
function logout($user)
{
        $token=$_COOKIE['MYSID'];
        $user=array('id'=>NULL,'username'=>"Visitor");
        redis_set_json($token,$user,"0");
        return $user;
}
function redis_set_json($key, $val, $expire)
{
        $rC = new Redis();
        $rC->connect( REDIS_SERVER, REDIS_PORT );
        $value=json_encode($val);
        if ($expire > 0)
                $rC->setex($key, $expire, $value );
        else
                $rC->set($key, $value);
        $rC->close();
}
function redis_get_json($key)
{
        $rC = new Redis();
        $rC->connect( REDIS_SERVER, REDIS_PORT );
	$rC->auth(REDIS_PASSWORD);
        $ret=json_decode($rC->get($key),true);
        $rC->close();
        return $ret;
}


function show_menu($user)
{
echo '
<nav class="uk-navbar">
    <ul class="uk-navbar-nav">';
                if ($user==NULL or $user['id']==NULL)
                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
                else
                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>';
echo '        <li class="uk-parent"><a href="index.php">Home</a></li>
    </ul>
</nav>';
}
?>