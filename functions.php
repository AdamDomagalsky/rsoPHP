<?PHP
$host = gethostname();
require_once "{$host}config.php";

function session_check()
{
    if (!isset($_COOKIE['MYSID'])) {
        $token = md5(rand(0, 1000000000));
        setcookie('MYSID', $token);
        $user = array(
            'id' => NULL,
            'username' => "Visitor"
        );
        redis_set_json($token, $user, 0);
    } else {
        $token = $_COOKIE['MYSID'];
    }
    
    $expire = isset($_POST['remember']) ? 0 : 3600;

    if (isset($_POST['username']) and isset($_POST['password'])) {
        return authorize($_POST['username'], $_POST['password'], $token, $expire);
    } else {
        return authorize(NULL, NULL, $token, $expire);
    }
}

function authorize($username, $password, $token)
{



	
    if ($username != NULL and $password != NULL) {
        // if ($username=="kalkos" and $password=="qwerty")
        //         $user=array('id'=>333,'username'=>$username);
        // else
        //         $user=array('id'=>NULL,'username'=>"Visitor");
        $user         = array();
        $user['username_err'] = $user['password_err'] = "";
        
        // Processing form data when form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Check if username is empty
            if (empty(trim($username))) {
                $user['username_err'] = 'Please enter username.';
            } else {
                $user['username'] = trim($username);
            }
            // Check if password is empty
            if (empty(trim($password))) {
                $user['password_err'] = 'Please enter your password.';
            } else {
                $password = trim($password);
	    }

            // Validate credentials
            if (empty($username_err) && empty($password_err)) {
                // Prepare a select statement
                $sql = "SELECT id, username, password, isAdmin FROM users WHERE username = ?";
                $dbSlave = mysqli_connect(DB_SERVER_SLAVE,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
                if($dbSlave === false){
                    die("ERROR(dbSlave): Could not connect. " . mysqli_connect_error());
                    exit;
                }


		if ($stmt = mysqli_prepare($dbSlave, $sql)) {
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "s", $param_username);
		    // Set parameters
		    
	    echo $username;
		    $param_username = $username;
                    
                    // Attempt to execute the prepared statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Store result
                        mysqli_stmt_store_result($stmt);
                        
                        // Check if username exists, if yes then verify password
                        if (mysqli_stmt_num_rows($stmt) == 1) {
                            // Bind result variables
                            mysqli_stmt_bind_result($stmt, $user['id'], $user['username'], $hashed_password, $user['isAdmin']);
                            
                            if (mysqli_stmt_fetch($stmt)) {
                                if (password_verify($password, $hashed_password)) {
                                    /* Password is correct, so start a new session and
                                    save the username to the session */
                                    redis_set_json($token, $user, $expire);
                                    return $user;
                                } else {
                                    // Display an error message if password is not valid
                                    $user['password_err'] = 'The password you entered was not valid.';
                                    return $user;
                                }
                            }
                        } else {
                            // Display an error message if username doesn't exist
                            $user['username_err'] = 'No account found with that username.';
                            return $user;
                        }
                    } else {
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                }
                // Close statement
                mysqli_stmt_close($stmt);
                   // Close connection
	        mysqli_close($dbSlave);
            }
         
        }
    } else {
        return redis_get_json($token);
    }
}

function logout($user)
{
    $token = $_COOKIE['MYSID'];
    $user  = array(
        'id' => NULL,
        'username' => "Visitor"
    );
    redis_set_json($token, $user, "0");
    return $user;
}
function redis_set_json($key, $val, $expire)
{
    $rC = new Redis();
    $rC->connect(REDIS_SERVER, REDIS_PORT);
    $rC->auth(REDIS_PASSWORD);
    
    $value = json_encode($val);
    if ($expire > 0)
        $rC->setex($key, $expire, $value);
    else
        $rC->set($key, $value);
    
    $rC->close();
}
function redis_get_json($key)
{
    $rC = new Redis();
    $rC->connect(REDIS_SERVER, REDIS_PORT); 
    $rC->auth(REDIS_PASSWORD);
    $ret = json_decode($rC->get($key), true);
    $rC->close();
    return $ret;
}

function show_menu($user)
{
    echo '
<nav class="uk-navbar">
<ul class="uk-navbar-nav">';
    if ($user == NULL or $user['id'] != true)
        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
    else
        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>
<li class="uk-parent"><a href="welcome.php">Welcome</a></li>
<li class="uk-parent"><a href="profile.php">Profile</a></li>
';
    echo '        <li class="uk-parent"><a href="index.php">Home</a></li>
</ul>
</nav>';
    echo $user;
    echo '<pre>';
    print_r($user);
    echo '</pre>';
}



?>
