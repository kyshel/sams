<?php
// set DB_HOST, DB_USER, DB_PASS, DB_NAME
//require_once("../config.php");

$db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
// change character set to utf8 and check it
if (!$db->set_charset("utf8")) {
    die('Unable to change character set to utf8 [' . $db->error . ']');
}

?>