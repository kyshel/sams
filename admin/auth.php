<?php
require_once("functions.php");
require_once("db_connection.php");

session_start();

exec_login_if_not();
exec_kickout_if_timeout();



?>