<?php
require_once("auth.php");

if ( isset( $_GET['getAddedDate'] ) ) {
	$stu_grade=NULL;
	$stu_major=NULL;
	$course_id=NULL;

	$pro_id=$_GET['pro_id'];
	getProDetail($pro_id,$stu_grade,$stu_major,$course_id);	
	require_once("datepicker.php");
} 



// add students
//var_dump($_GET);
// $op=isset($_GET['op']) ? $_GET['op'] : 'not_set';
// if ($op == 'not_set') {
// }elseif ($op == 'add') {
// 	$stu_id = isset($_GET['stu_id']) ? $_GET['stu_id'] : die(' stu_id is not set');
// 	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
// 	$sql_add="INSERT into attend(pro_id,stu_id,no_sum) values('$pro_id','$stu_id',0)";
// 	insertOne($sql_add);
// 	makeTableForAddedStudent($pro_id);
// }elseif ($op == 'del') {
// 	$stu_id = isset($_GET['stu_id']) ? $_GET['stu_id'] : die(' stu_id is not set');
// 	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
// 	$sql_del="DELETE FROM attend where pro_id = '$pro_id' and stu_id = '$stu_id' ";
// 	delOne($sql_del);
// 	makeTableForAddedStudent($pro_id);
// }elseif ($op == 'add_refresh') {
// 	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
// 	makeTableForAddStudent($pro_id);
// }

// receive showGrid() op 
//  !!!!!! compromise way, only for stu manage  !!!!
if (isset($_GET['op'])) {
	runOperateWithGet('student','SELECT * from student','stu_id');
}



// set student
$action=isset($_GET['action']) ? $_GET['action'] : 'action not_set';
switch ($action) {
	case 'add_student_to_course':
		//var_dump($_POST);
		$pro_id=$_POST['pro_id'];
		$stu_array=$_POST['stu_id'];
		addStudentToCourse($pro_id,$stu_array);
		makeFormForDelStudent($pro_id);
		break;

	case 'del_student_from_course':
		//var_dump($_POST);
		$pro_id=$_POST['pro_id'];
		$stu_array=$_POST['stu_id'];
		delStudentFromCourse($pro_id,$stu_array);
		makeFormForDelStudent($pro_id);
		break;

	case 'refresh_left_div':
		$pro_id=$_GET['pro_id'];
		makeFormForAddStudent($pro_id);
		break;

	case 'filter_stu_for_add':
		$pro_id=$_GET['pro_id'];
		//var_dump($_POST);
		$condition_array=$_POST['condition'];
		makeFormForAddStudent($pro_id,$condition_array);
		break;

	case 'filter_stu_for_manage':
		
		//var_dump($_POST);
		$condition_array=$_POST['condition'];
		echo '<span>过滤结果：<span><br>';
		showGrid('student',buildFilterStuSql($condition_array),'stu_id',0,0,1);
		echo '<a href="manage_stu.php?op=add">'.lang('add_new_stu').'</a>';
		break;

	// never use
	case 'show_all_stu':
		echo '<span>所有学生：<span><br>';
		echo '<a href="manage_stu.php?op=add">'.lang('add_new_stu').'</a>';
		// below func has parameter to receive, not recommend for single show
		showGrid('student','SELECT * from student','stu_id',0,1);
		echo '<a href="manage_stu.php?op=add">'.lang('add_new_stu').'</a>';
		break;
	
	default:
		echo $action;
		break;
}























