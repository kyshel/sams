<?php

/**
 * Class login
 * handles the user's login and logout process
 * Need: bootstrap
 */
class Login
{
	/**
	 * @var object The database connection
	 */
	private $db_connection = null;
	/**
	 * @var array Collection of error messages
	 */
	public $errors = array();
	/**
	 * @var array Collection of success / neutral messages
	 */
	public $messages = array();

	/**
	 * the function "__construct()" automatically starts whenever an object of this class is created,
	 * you know, when you do "$login = new Login();"
	 */
	public function __construct()
	{
		// create/read session, absolutely necessary
		session_start();

		// check the possible login actions:
		// if user tried to log out (happen when user clicks logout button)
		if (isset($_GET["logout"])) {
			$this->doLogout();
		}
		elseif (isset($_GET["timeout"])) {
			$this->doTimeout();
		}
		// login via post data (if user just submitted a login form)
		elseif (isset($_POST["login"])) {
			$this->dologinWithPostData();
		}

	}

	/**
	 * log in with post data
	 */
	private function dologinWithPostData()
	{
		// check login form contents
		if (empty($_POST['user_name'])) {
			//$this->errors[] = "Username field was empty.";
			$this->errors[] = "未输入用户名";
		} elseif (empty($_POST['user_password'])) {
			//$this->errors[] = "Password field was empty.";
			$this->errors[] = "未输入密码";
		} elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

			// create a database connection, using the constants from config/db.php (which we loaded in index.php)
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			// change character set to utf8 and check it
			if (!$this->db_connection->set_charset("utf8")) {
				$this->errors[] = $this->db_connection->error;
			}

			// if no connection errors (= working database connection)
			if (!$this->db_connection->connect_errno) {

				// escape the POST stuff
				$user_name = $this->db_connection->real_escape_string($_POST['user_name']);

				// database query, getting all the info of the selected user (allows login via email address in the
				// username field)
				$sql = "SELECT user_name, user_email, user_password_hash
						FROM users
						WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_name . "';";
				$result_of_login_check = $this->db_connection->query($sql);

				$sql2 = "SELECT tea_id, tea_name, tea_password
						FROM teacher
						WHERE tea_name = '" . $user_name . "';";
				$result_of_login_check2 = $this->db_connection->query($sql2);

				//********* admin verify *****
				// if this user exists
				if ($result_of_login_check->num_rows == 1) {

					// get result row (as an object)
					$result_row = $result_of_login_check->fetch_object();

					// using PHP 5.5's password_verify() function to check if the provided password fits
					// the hash of that user's password
					if (password_verify($_POST['user_password'], $result_row->user_password_hash)) {

						// write user data into PHP SESSION (a file on your server)
						$_SESSION['user_name'] = $result_row->user_name;
						$_SESSION['user_email'] = $result_row->user_email;
						$_SESSION['user_role'] = 'admin';
						$_SESSION['user_login_status'] = 1;

						//log add
						$action="登录系统";
						//add_log($action,$this->db_connection);
					} else {
						//$this->errors[] = "<div class='alert alert-danger' role='alert'>密码错误，请重试！</div>";
						$this->errors[] = "<div class='alert alert-danger' role='alert'>用户名密码不匹配，请重试！</div>";
					}

				//******* teacher verify *****
				} elseif ($result_of_login_check2->num_rows == 1) {

					$sql4 = "SELECT tea_lock
					FROM teacher
					WHERE tea_name = '" . $user_name . "';";
					//var_dump($sql4);
					$result4 = $this->db_connection->query($sql4);
					$row4 = $result4->fetch_object();

					if ($row4->tea_lock=='1') {
						die('<h1>此账户暂停使用</h1><a href="index.php">返回</a>');
					}

					// get result row (as an object)
					$result_row = $result_of_login_check2->fetch_object();

					// using PHP 5.5's password_verify() function to check if the provided password fits
					// the hash of that user's password
					if ( $_POST['user_password'] == $result_row->tea_password ) {

						$sql3 = "SELECT tea_active
						FROM teacher
						WHERE tea_name = '" . $user_name . "';";
						$result3 = $this->db_connection->query($sql3);
						$row3 = $result3->fetch_object();

						if ($row3->tea_active=='0') {
							$_SESSION['tea_id'] = $result_row->tea_id;
							$_SESSION['user_name'] = $result_row->tea_name;
							$_SESSION['user_role'] = 'tea_first';
							$_SESSION['user_login_status'] = 1;

							$_SESSION['tea_active'] = 0;
							header("Location:set_password.php");

							die();
						}

						// write user data into PHP SESSION (a file on your server)
						$_SESSION['tea_id'] = $result_row->tea_id;
						$_SESSION['user_name'] = $result_row->tea_name;
						$_SESSION['user_role'] = 'teacher';
						$_SESSION['user_login_status'] = 1;

						//log add
						//$action="登录系统";
						//add_log($action,$this->db_connection);
					} else {
						//$this->errors[] = "<div class='alert alert-danger' role='alert'>密码错误，请重试！</div>";
						$this->errors[] = "<div class='alert alert-danger' role='alert'>用户名密码不匹配，请重试！</div>";
					}
				} 



				else {
					//$this->errors[] = "<div class='alert alert-danger' role='alert'>用户不存在！</div>";
					$this->errors[] = "<div class='alert alert-danger' role='alert'>用户名密码不匹配，请重试！</div>";
				}

			} else {
				$this->errors[] = "<div class='alert alert-danger' role='alert'>数据库连接出错！</div>";
			}
		}
	}

	/**
	 * perform the logout
	 */
	public function doLogout()
	{
		//log
		// if(isset($_SESSION['user_name'])){
		//     $action="退出系统";
		//     require_once("db_connection.php");
		//     add_log($action,$db);
		// }


		// delete the session of the user
		$_SESSION = array();
		session_destroy();
		// return a little feeedback message
		$this->messages[] = "<div class='alert alert-success' role='alert'>您已退出系统！</div>";

	}
	public function doTimeout()
	{
		// delete the session of the user
		//$_SESSION = array();
		//session_destroy();
		// return a little feeedback message
		$this->messages[] = "<div class='alert alert-success' role='alert'>由于长时间未操作，您已退出系统，请重新登录！</div>";

	}

	/**
	 * simply return the current state of the user's login
	 * @return boolean user's login status
	 */
	public function isUserLoggedIn()
	{
		if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
			return true;
		}
		// default return
		return false;
	}
	public function go()
	{
		$redirectURL = NULL;
		if($_POST['redirect_to'] != '') {
			$redirectURL = $_POST['redirect_to'];
		}else{
			$redirectURL = 'index.php';
		}

		header("Location: " . $redirectURL);
	}
}


/**
 * Class registration
 * handles the user registration
 */
class Registration
{
	/**
	 * @var object $db_connection The database connection
	 */
	private $db_connection = null;
	/**
	 * @var array $errors Collection of error messages
	 */
	public $errors = array();
	/**
	 * @var array $messages Collection of success / neutral messages
	 */
	public $messages = array();

	/**
	 * the function "__construct()" automatically starts whenever an object of this class is created,
	 * you know, when you do "$registration = new Registration();"
	 */
	public function __construct()
	{
		if (isset($_POST["register"])) {
			$this->registerNewUser();
		}
	}

	/**
	 * handles the entire registration process. checks all error possibilities
	 * and creates a new user in the database if everything is fine
	 */
	private function registerNewUser()
	{
		if (empty($_POST['user_name'])) {
			$this->errors[] = "Empty Username";
		} elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
			$this->errors[] = "Empty Password";
		} elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
			$this->errors[] = "Password and password repeat are not the same";
		} elseif (strlen($_POST['user_password_new']) < 6) {
			$this->errors[] = "Password has a minimum length of 6 characters";
		} elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
			$this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
		} elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
			$this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
		} elseif (empty($_POST['user_email'])) {
			$this->errors[] = "Email cannot be empty";
		} elseif (strlen($_POST['user_email']) > 64) {
			$this->errors[] = "Email cannot be longer than 64 characters";
		} elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
			$this->errors[] = "Your email address is not in a valid email format";
		} elseif (!empty($_POST['user_name'])
			&& strlen($_POST['user_name']) <= 64
			&& strlen($_POST['user_name']) >= 2
			&& preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
			&& !empty($_POST['user_email'])
			&& strlen($_POST['user_email']) <= 64
			&& filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
			&& !empty($_POST['user_password_new'])
			&& !empty($_POST['user_password_repeat'])
			&& ($_POST['user_password_new'] === $_POST['user_password_repeat'])
		) {
			// create a database connection
			$this->db_connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

			// change character set to utf8 and check it
			if (!$this->db_connection->set_charset("utf8")) {
				$this->errors[] = $this->db_connection->error;
			}

			// if no connection errors (= working database connection)
			if (!$this->db_connection->connect_errno) {

				// escaping, additionally removing everything that could be (html/javascript-) code
				$user_name = $this->db_connection->real_escape_string(strip_tags($_POST['user_name'], ENT_QUOTES));
				$user_email = $this->db_connection->real_escape_string(strip_tags($_POST['user_email'], ENT_QUOTES));

				$user_password = $_POST['user_password_new'];

				// crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
				// hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
				// PHP 5.3/5.4, by the password hashing compatibility library
				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

				// check if user or email address already exists
				$sql = "SELECT * FROM users WHERE user_name = '" . $user_name . "' OR user_email = '" . $user_email . "';";
				$query_check_user_name = $this->db_connection->query($sql);

				if ($query_check_user_name->num_rows == 1) {
					$this->errors[] = "Sorry, that username / email address is already taken.";
				} else {
					// write new user's data into database
					$sql = "INSERT INTO users (user_name, user_password_hash, user_email)
							VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $user_email . "');";
					$query_new_user_insert = $this->db_connection->query($sql);

					// if user has been added successfully
					if ($query_new_user_insert) {
						$this->messages[] = "Your account has been created successfully. You can now log in.";
					} else {
						$this->errors[] = "Sorry, your registration failed. Please go back and try again.";
					}
				}
			} else {
				$this->errors[] = "Sorry, no database connection.";
			}
		} else {
			$this->errors[] = "An unknown error occurred.";
		}
	}
}


