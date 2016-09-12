<?php
require_once("header.php");
dev_dump($_POST);
$redirect='<script>window.location.href = "index.php";</script>';
if (!isset($_POST["pro_id"])) {
	echo $redirect;
	die();
}

$go_id=NULL;
$stu_id=NULL;
$stu_grade=NULL;
$stu_major=NULL;
$course_id=NULL;
getProDetail($_POST["pro_id"],$course_id,$year,$term,$stu_grade,$stu_major,$old_last_update);

$pro_id=$_POST["pro_id"];
$update_time=$_POST["update_time"];
$stu_sum=$_POST["stu_sum"];


$sql="UPDATE project SET last_update= '$update_time' WHERE pro_id = '$pro_id' ";
$db->query($sql) or die($db->error);
for($j=0;$j<$stu_sum;$j++){
	// make string that match the var name
	$stu_num_j="stu_num".$j;
	$no_sum_j="no_sum".$j;

	$stu_id=$_POST[$stu_num_j];
	$no_sum=$_POST[$no_sum_j];
	$no_sum = (int)$no_sum;

	$sql="UPDATE attend SET no_sum= $no_sum
	WHERE stu_id='$stu_id' and pro_id = '$pro_id' ";

	$db->query($sql) or die($db->error);	
}

?>




<?php
// show result
echoGreen('提交成功！<a href="index.php" class="pull-right">完成</a> ',1);
showAttendTable($pro_id);

	$array_pro=getArrayFromEntry('project','pro_id',$pro_id);
	$year=$array_pro['year'];
	$term=$array_pro['term'];
	$course_id=$array_pro['course_id'];
	$course_name=$array_pro['course_name'];
	//$stu_grade=$array_pro['stu_grade'];
	//$stu_major=$array_pro['stu_major'];
	//$tea_id=$array_pro['tea_id'];
	//$tea_name=$array_pro['tea_name'];

$action='更新考勤记录,记录号: '.$pro_id.','.$year.'学年,'.$term.'学期,'.$course_name;
addLog($action);

?>
<a href="index.php">完成</a>

<script type="text/javascript">	
	$(document).ready(function() { 

		document.getElementById('tablesort').addEventListener('afterSort', function() {
			$('.danger').tooltip('show');
		});
		$('.danger').tooltip('show');
	});
</script>


