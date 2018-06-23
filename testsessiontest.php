<?php
//simple counter to test sessions. should increment on each page reload.
//
session_start();
$count = isset($_SESSION['count']) ? $_SESSION['count'] : 1;
$host = gethostname();
echo "$host $count";
$_SESSION['count'] = ++$count;
