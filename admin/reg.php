<?php

/**
 * A simple, clean and secure PHP Login Script / MINIMAL VERSION
 * For more versions (one-file, advanced, framework-like) visit http://www.php-login.net
 *
 * Uses PHP SESSIONS, modern password-hashing and salting and gives the basic functions a proper login system needs.
 *
 * @author Panique
 * @link https://github.com/panique/php-login-minimal/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("password_compatibility_library.php");
}

// include the configs / constants for the database connection
require_once("../config.php");

// load the registration class
require_once("class.php");

// create the registration object. when this object is created, it will do all registration stuff automatically
// so this single line handles the entire registration process.
$registration = new Registration();
?>


<?php
// show potential errors / feedback (from registration object)
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<!-- register form -->
<form method="post" action="reg.php" name="registerform">

    <!-- the user name input field uses a HTML5 pattern check -->
    <label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label>
    <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />

    <!-- the email input field uses a HTML5 email type check -->
    <label for="login_input_email">User's email</label>
    <input id="login_input_email" class="login_input" type="email" name="user_email" required />

    <label for="login_input_password_new">Password (min. 6 characters)</label>
    <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off" />

    <label for="login_input_password_repeat">Repeat password</label>
    <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off" />
    <input type="submit"  name="register" value="Register" />

</form>

<!-- backlink -->
<a href="index.php">Back to Login Page</a>
