<?php
require_once("header.php");
?>


<?php
$user_role=$_SESSION['user_role'];
if ($user_role=='admin') {
	$sql="SELECT pro_id,course_id,year,term,stu_grade,stu_major,tea_id from project order by pro_id";
	$message = '提示：你是管理员身份登录系统，如果点名，将以对应课程的教师身份来进行';
}elseif ($user_role=='teacher') {
	$tea_id=$_SESSION['tea_id'];
	$sql="SELECT pro_id,course_id,year,term,stu_grade,stu_major from project where tea_id = '$tea_id' order by pro_id";
}


showGrid('project',$sql,'pro_id');

echo '<br><br><span>'.$message.'</span>';
?>





























