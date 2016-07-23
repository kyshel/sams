<?php
require_once("header.php");

var_dump($_POST);//dev
echo '<br>';

$pro_id=$_POST["pro_id"];
$go_time=$_POST["date"];
$stu_sum=$_POST["stu_sum"];
$go_id=NULL;
$stu_id=NULL;
$is_online=NULL;

check_go_unique();

$sql="INSERT INTO 
go(pro_id,go_time,go_meta)VALUES
('$pro_id','$go_time','')
";
$db->query($sql) or die($db->error);

$go_id=get_go_id($pro_id,$go_time);

for($j=0;$j<$stu_sum;$j++){
	$stu_num_j="stu_num".$j;
	$check_j="check".$j;

	$stu_id=$_POST[$stu_num_j];
	if (isset($_POST[$check_j])) {
		$is_online=1;		
	}else{
		$is_online=0;
	}

	echo $stu_id."-".$is_online.'<br>';

	sql="INSERT INTO go_id,stu_id,at_yes,at_meta";

	
}










?>

