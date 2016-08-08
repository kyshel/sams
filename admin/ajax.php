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

$op=isset($_GET['op']) ? $_GET['op'] : die(' op is not set');




if ($op == 'add') {
	$stu_id = isset($_GET['stu_id']) ? $_GET['stu_id'] : die(' stu_id is not set');
	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
	$sql_add="INSERT into attend(pro_id,stu_id,no_sum,last_update) values('$pro_id','$stu_id',0,'never')";
	insertOne($sql_add);

	makeTableForAddedStudent($pro_id);
}elseif ($op == 'del') {
	$stu_id = isset($_GET['stu_id']) ? $_GET['stu_id'] : die(' stu_id is not set');
	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
	$sql_del="DELETE FROM attend where pro_id = '$pro_id' and stu_id = '$stu_id' ";
	delOne($sql_del);

	makeTableForAddedStudent($pro_id);
}elseif ($op == 'add_refresh') {
	$pro_id = isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');
	makeTableForAddStudent($pro_id);
}






















