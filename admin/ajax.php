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


// set student
$action=isset($_GET['action']) ? $_GET['action'] : 'action not_set';
switch ($action) {
	case 'add_student_to_course':
		//var_dump($_POST);
		$pro_id=$_POST['pro_id'];
		$stu_array=$_POST['stu_id'];
		addStudentToCourse($pro_id,$stu_array);
		echoGreen('添加成功!',1);
		makeFormForDelStudent($pro_id);
		break;

	case 'del_student_from_course':
		//var_dump($_POST);
		$pro_id=$_POST['pro_id'];
		$stu_array=$_POST['stu_id'];
		delStudentFromCourse($pro_id,$stu_array);
		echoGreen('删除成功!',1);
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
		$condition_array=$_POST['condition'];
		echo '
		<div class="panel panel-success">
			<div class="panel-heading"> 
				<h3 class="panel-title">过滤结果</h3> 
			</div> 
			';
		// bad ways, edit link not calc auto
		showGrid('student',buildFilterStuSql($condition_array),'stu_id',0,0,1);
		//echo '<a href="manage_stu.php?op=add">'.lang('add_new_stu').'</a>';
		break;

	case 'show_static':
		switch ($_GET['type']) {
			case 'pro':
				showAttendTable($_POST['pro_id']);
				break;

			case 'class':
				
				break;

			case 'stu':
				
				break;
			
			default:
				# code...
				break;
		}		
		break;


	
	default:
		echo $action;
		break;
}























