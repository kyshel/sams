
<?php
require_once("auth.php");

if ( isset( $_GET['getAddedDate'] ) ) {
	$stu_grade=NULL;
	$stu_major=NULL;
	$course_id=NULL;

	$pro_id=$_GET['pro_id'];
	getProDetail($pro_id,$stu_grade,$stu_major,$course_id);	
} 

 require_once("datepicker.php");




















