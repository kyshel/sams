<?php
require_once("header.php");

dev_var_dump('post');

?>



<?php 
$stu_grade=NULL;
$stu_major=NULL;
$course_name=NULL;
get_pro_detail_with_id($_POST["pro_id"],$stu_grade,$stu_major,$course_name);

echo '<h3>您选择的年级为'.$stu_grade.'级，专业为'.$stu_major.'，课程为'.$course_name.'，日期为'.$_POST["date"].'：
</h3>';
?>

<form action="add_result.php" method="post">

<?php 
echo '<input name="pro_id" value="'.$_POST["pro_id"].'" style="display:none;" >';
echo '<input name="date" value="'.$_POST["date"].'" style="display:none;" >';
?>

<?php generate_stu_list($_POST["pro_id"]); ?>

<input type="submit" name="add_data_submit" value="提交">
</form>


<script type="text/javascript">
	$('[type="checkbox"]').bootstrapSwitch();
</script>





