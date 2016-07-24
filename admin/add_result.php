<?php
require_once("header.php");

dev_var_dump('post');

$pro_id=$_POST["pro_id"];
$go_time=$_POST["date"];
$stu_sum=$_POST["stu_sum"];
$go_id=NULL;
$stu_id=NULL;
$is_online=NULL;

//insert go
check_go_unique('','');
$sql="INSERT INTO 
go(pro_id,go_time,go_meta)VALUES
('$pro_id','$go_time','')
";
$db->query($sql) or die($db->error);

//insert at
$go_id=get_go_id($pro_id,$go_time);
for($j=0;$j<$stu_sum;$j++){
	// make string that match the var name
	$stu_num_j="stu_num".$j;
	$check_j="check".$j;
	$at_meta_j="at_meta".$j;

	$stu_id=$_POST[$stu_num_j];
	if (isset($_POST[$check_j])) {
		$is_online=1;		
	}else{
		$is_online=0;
	}
	$at_meta=$_POST[$at_meta_j];

	echo $stu_id."-".$is_online.'<br>';

	$sql="INSERT INTO attend(go_id, stu_id, at_yes, at_meta) VALUES
	('$go_id','$stu_id','$is_online','$at_meta')";

	$db->query($sql) or die($db->error);

	
}


$stu_grade=NULL;
$stu_major=NULL;
$course_name=NULL;
get_pro_detail_with_id($_POST["pro_id"],$stu_grade,$stu_major,$course_name);
echo '<h1>提交成功！'.$stu_grade.'级，'.$stu_major.'专业，'.$course_name.'课，'.$go_time.'的点名结果如下：</h1>';


// table --------------------------
echo '<table class="table-bordered">';
echo '<tr>';
			echo '<th>';
			echo '学号';
			echo '</th>';

			echo '<th>';
			echo '姓名';
			echo '</th>';

			echo '<th>';
			echo '结果';
			echo '</th>';

			echo '<th>';
			echo '备注';
			echo '</th>';
echo '</tr>';

$sql="SELECT stu_id,at_yes,at_meta 
from attend
where go_id = '$go_id'";
$result = $db->query($sql);
if ($result->num_rows == 0) {
	echo "<h1>No Result</h1>";
} else {
	while($row = $result->fetch_array(MYSQLI_ASSOC)){
		$stu_name=get_stu_name_with_id($row['stu_id']);

		echo '<tr>';
			echo '<td>';
			echo $row['stu_id'];
			echo '</td>';

			echo '<td>';
			echo $stu_name;
			echo '</td>';


			echo '<td>';
			echo $row['at_yes'];
			echo '</td>';

			echo '<td>';
			echo $row['at_meta'];
			echo '</td>';
		echo '</tr>';
	}
}












?>

