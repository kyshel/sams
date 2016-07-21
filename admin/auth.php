<?php
require_once("functions.php");

session_start();

exec_login_if_not();
exec_kickout_if_timeout();



?>