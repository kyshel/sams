<?php
require_once("header.php");
?>

<?php

// define sql,message
$message='';
$status=( (isset($_GET['op'])?$_GET['op']:'') == 'show_off_pro')?'off':'on';
noise($status,'status');
$add_col=($status == 'off')?',off_time':'';
$user_role=$_SESSION['user_role'];
if ($user_role=='admin') {
	$sql="SELECT pro_id,course_id,course_name,year,term,hour,stu_grade,stu_major,tea_id,tea_name".$add_col." from project 
	where status = '$status'
	order by pro_id";	
	$message = '提示：你是管理员身份登录系统，如果点名，将以对应课程的教师身份来进行';
}elseif ($user_role=='teacher') {
	$tea_id=$_SESSION['tea_id'];
	$sql="SELECT pro_id,course_id,course_name,year,term,hour,stu_grade,stu_major".$add_col." from project 
	where tea_id = '$tea_id' and status = '$status'
	order by pro_id";

}


// show
showPro($sql,$status);


// display off_link , message
if ($status== 'on' && isAnyProOff() ) {
	echo '<br><br>';
	echo '<a href="'.php_self().'?op=show_off_pro'.'">'.lang('show_off_pro').'</a>';
}elseif($status== 'off'){
	echo '<br><br>';
	echo '<a href="'.php_self().'">'.lang('show_on_pro').'</a>';
}
echo '<br><br><span>'.$message.'</span>';
?>