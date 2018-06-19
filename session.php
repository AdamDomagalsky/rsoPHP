<?php

$host = gethostname();
require_once "{$host}config.php";
require_once "classSession.php";


// kodzik znalazlem na http://php.net/manual/en/function.session-decode.php#108037
class Session {
    public static function unserialize($session_data) {
        $method = ini_get("session.serialize_handler");
        switch ($method) {
            case "php":
                return self::unserialize_php($session_data);
                break;
            case "php_binary":
                return self::unserialize_phpbinary($session_data);
                break;
            default:
                throw new Exception("Unsupported session.serialize_handler: " . $method . ". Supported: php, php_binary");
        }
    }

    private static function unserialize_php($session_data) {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            if (!strstr(substr($session_data, $offset), "|")) {
                throw new Exception("invalid data, remaining: " . substr($session_data, $offset));
            }
            $pos = strpos($session_data, "|", $offset);
            $num = $pos - $offset;
            $varname = substr($session_data, $offset, $num);
            $offset += $num + 1;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        return $return_data;
    }

    private static function unserialize_phpbinary($session_data) {
        $return_data = array();
        $offset = 0;
        while ($offset < strlen($session_data)) {
            $num = ord($session_data[$offset]);
            $offset += 1;
            $varname = substr($session_data, $offset, $num);
            $offset += $num;
            $data = unserialize(substr($session_data, $offset));
            $return_data[$varname] = $data;
            $offset += strlen(serialize($data));
        }
        return $return_data;
    }
}



function check_session(){

    // If session variable is not set it will redirect to login page
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header("location: login.php");
        exit;
    } else {
        $token="PHPREDIS_SESSION:{$_COOKIE['PHPSESSID']}";
        restore_session($token);
    }
}

function restore_session($key){
    $rC = new Redis();
    $rC->connect(REDIS_SERVER, REDIS_PORT);
	$rC->auth(REDIS_PASSWORD);
	
	$ret = Session::unserialize($rC->get($key));
    $rC->close();
	return $ret;
}

?>