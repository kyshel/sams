<?php
require_once("functions.php");
check_phpversion_for_hash();

require_once("../config.php");
require_once("class.php");

$login = new Login();

//... ask if we are not logged in here:
if ($login->isUserLoggedIn() == false) {
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
    //die();
} else{
	$login->go();
}

dev_var_dump('post');

?>


<p>this is login page</p>
<form method="post" action="login.php" >		
name: 
<input type="text" name="user_name"><br>
pass: 
<input type="password" name="user_password"><br>
<input type="submit" name="login">

<?php
echo '<input type="hidden" name="redirect_to" value="';
if(isset($_GET['redirect_to'])) {
    echo htmlspecialchars($_GET['redirect_to']);
}
echo '" />';
?>

</form>










<?php
echo 'this is test zone>>>>>>>>>>>>>>>>>>>>>> <br/>';






echo '<br/> this is test zone<<<<<<<<<<<<<<<<<<<<<<';
?>