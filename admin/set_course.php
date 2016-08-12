<?php
require_once("header.php");
?>


<?php
$user_role=$_SESSION['user_role'];
if ($user_role=='admin') {
	$sql="SELECT pro_id,course_id,year,term,hour,stu_grade,stu_major,tea_id from project order by pro_id";
	$message = '提示：你是管理员身份登录系统，如果点名，将以对应课程的教师身份来进行';
}elseif ($user_role=='teacher') {
	$tea_id=$_SESSION['tea_id'];
	$sql="SELECT pro_id,course_id,year,term,hour,stu_grade,stu_major from project where tea_id = '$tea_id' order by pro_id";
}


showGrid('project',$sql,'pro_id');
echo '<br><br><span>'.$message.'</span>';
?>



<script type="text/javascript">
// function confirmDel($a,$b){
// 	if (confirm('要保留本课程的考勤数据吗？')) {
// 		if (confirm('删除本课程，但保留本课程考勤数据，\n您确定？')) {
// 			delCourseButSaveAttend();
// 		} else {
// 			return ;
// 		}
// 	} else {
// 		if (confirm('删除本课程，同时清空本课程考勤数据，\n您确定？')) {
// 			delCourseAndDelAttend();
// 		} else {
// 			return;
// 		}

// 	}
// }

// function delCourseAndDelAttend(){

// }

// function delCourseButSaveAttend(){

// }
</script>
	



























