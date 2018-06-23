<?php

$host = gethostname();

require_once "{$host}config.php";
require_once "functions.php";

$token=$_COOKIE['MYSID'];
$user=array('username'=>"Visitor");
redis_set_json($token,$user,"0");

 
// Redirect to login page
header("location: login.php");

return $user;
exit;
?>
