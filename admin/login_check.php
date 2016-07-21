<?php
require_once("../config.php");
require_once("class.php");

var_dump($_POST);

$login = new Login();
//... ask if we are not logged in here:
if ($login->isUserLoggedIn() == false) {
    // the user is logged in. you can do whatever you want here.
    // for demonstration purposes, we simply show the "you are logged in" view.
	if (isset($login)) {
	    if ($login->errors) {
	        foreach ($login->errors as $error) {
	            echo $error;
	        }
	    }
	    if ($login->messages) {
	        foreach ($login->messages as $message) {
	            echo $message;
	        }
	    }
	}
    die();
} 



?>