<?PHP 
require_once('functions.php');


    $token = "MYSID:".$_COOKIE['MYSID'];
    echo $token;
    $user  = array(
        'id' => NULL,
        'username' => "VisitorLoggedOut"
    );
    redis_set_json($token, $user, "0");
    
    // header("location: index.php");
    // exit;


?>