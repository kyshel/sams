<?php
require_once("header.php");

$php_self=php_self();
$user_role=$_SESSION['user_role'];

if(isset($_POST['show_submit']) || isset($_GET["pro_id"])){
	showAtDetail();
	die();
}
?>

<form action="show_at.php" method="post">
	<p>查询已添加点名:</p>
	<?php
	if($user_role == 'teacher'){
		$tea_id=$_SESSION['tea_id'];
		echoSelectForAddedGo('go_id',"SELECT distinct stu_grade,stu_major,course_id from go where tea_id = '$tea_id' ");
	}elseif ($user_role == 'admin') {
		echoSelectForAddedGo('go_id',"SELECT distinct stu_grade,stu_major,course_id,tea_id from go ");
	}
	?>
	<input type="submit" name="show_submit">
</form>


<!-- ******************************************* -->


<!-- <form action="show_at.php" method="post">
	<p>查询现有课程:</p>
	<?php
	// if($user_role == 'teacher'){
	// 	teacher_get_own_course($_SESSION['tea_id']);
	// }elseif ($user_role == 'admin') {
	// 	makeSelectForPro();
	// }
	?>
	<input type="submit" name="show_submit">
</form>  -->




