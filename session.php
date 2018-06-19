<?php

$host = gethostname();
require_once "{$host}config.php";
require_once "classSession.php";

function check_session(){

    // If session variable is not set it will redirect to login page
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header("location: login.php");
        exit;
    } else {
        restore_session();
    }
}

function restore_session(){
    $rC = new Redis();
    $rC->connect( REDIS_SERVER, REDIS_PORT );
	$rC->auth(REDIS_PASSWORD);
	
	$ret = Session::unserialize($rC->get($key));
	// $ret=json_decode($rC->get($key),true);
    $rC->close();
	return $ret;
}

?>