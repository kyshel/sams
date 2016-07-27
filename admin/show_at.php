<?php
require_once("header.php");

$php_self=php_self();
$user_role=$_SESSION['user_role'];

if(isset($_POST['show_submit'])){
	showAtDetail($user_role);
	die();
}


if($user_role == 'teacher'){?>

	<form action="show_at.php" method="post">
		<p>Please choose:</p>
		<span>Course</span>
		<?php teacher_get_own_course($_SESSION['tea_id']);?>
		<input type="submit" name="show_submit">
	</form>



<?php
}elseif ($user_role == 'admin') {?>
	<form action="show_at.php" method="post">
		<p>Please choose:</p>
		<span>Course</span>
		<?php makeSelectForPro();?>
		<input type="submit" name="show_submit">
	</form>

<?php
}
?>