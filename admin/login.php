<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CDTF SAMS</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="css/bootstrap-theme.min.css"> -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<link rel="stylesheet" href="css/sams.css">
</head>




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
} else{
	$login->go();
}

//dev_dump($_POST);

?>

<form class="form-signin" method="post" action="login.php">
	<h2 class="form-signin-heading sys_name">中德考勤录入系统</h2>

	<label for="inputEmail" class="sr-only">用户名</label>
	<input type="text" name="user_name" id="inputEmail" class="form-control" placeholder="用户名" required="" autofocus="">

	<label for="inputPassword" class="sr-only">密码</label>
	<input type="password" name="user_password" id="inputPassword" class="form-control" placeholder="密码" required="">
	<div class="checkbox" style="display:none;">
		<label>
			<input type="checkbox" value="remember-me"> Remember me
		</label>
	</div>
	<br>
	<button class="btn btn-lg btn-primary btn-block" name="login" type="submit">登录</button>
	<?php
	echo '<input type="hidden" name="redirect_to" value="';
	if(isset($_GET['redirect_to'])) {
		echo htmlspecialchars($_GET['redirect_to']);
	}
	echo '" />';
	?>
</form>










<style type="text/css">

body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}

.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading,
.form-signin .checkbox {
  margin-bottom: 10px;
}
.form-signin .checkbox {
  font-weight: normal;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}

.sys_name{
	text-align:center;
}

</style>