<?php
// use session judge is logged
function exec_login_if_not(){	
	if (!(isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1)) {
		header("Location:login.php?redirect_to=" . urlencode($_SERVER['REQUEST_URI']));
		die();
	}
}

//judge last_opreate to now opreate_time distance, 
//if greater than 60 , logout
function exec_kickout_if_timeout(){	
    if(!(isset($_SESSION['timeout']))){
            //echo "setting time ~~~~~~~~~~~~~</ br>";
         $_SESSION['timeout'] = time();
    }
        //set delay time,secs
    else{
        $delay=5;
        if ($_SESSION['timeout'] + $delay < time()) {
            $_SESSION = array();
            session_destroy();
            // return a little feeedback message
            //echo "<div class='alert alert-success' role='alert'>由于长时间未操作，您已退出系统，请重新登录！</div>";
            // session timed out

            header("Location: login.php?timeout");
            die();

        } else {
            $cha=time()-$_SESSION['timeout'];
            $cha_div=$cha / 60 ;
            echo '
            <script type="text/javascript">console.log("您距离上一次操作相差'.$cha.'秒，即'.$cha_div.' 分钟，超过60分钟会强制退出！")</script>  
            ';
            $_SESSION['timeout'] = time();
            // session ok
        }
    }
}


// checking for minimum PHP version
function check_phpversion_for_hash(){
	if (version_compare(PHP_VERSION, '5.3.7', '<')) {
	    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
	} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
	    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
	    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
	    require_once("password_compatibility_library.php");
	}
}













