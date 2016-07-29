<?php
require_once("header.php");

dev_var_dump('post');

?>



<?php 
$stu_grade=NULL;
$stu_major=NULL;
$course_id=NULL;
getProDetail($_POST["pro_id"],$stu_grade,$stu_major,$course_id);
checkGoUnique($stu_grade,$stu_major,$course_id,$_POST["date"]);
$course_name=getCoursename($course_id);

echo '<p>您选择的年级为'.s($stu_grade).'级，专业为'.s($stu_major).'，课程为'.s($course_name).'，日期为'.s($_POST["date"]).'：
</p>';
?>

<form action="add_result.php" method="post">

<?php 
echo '<input name="pro_id" value="'.$_POST["pro_id"].'" style="display:none;" >';
echo '<input name="date" value="'.$_POST["date"].'" style="display:none;" >';
?>

<?php generate_stu_list($_POST["pro_id"]); ?>

<input type="submit" name="add_data_submit" value="提交">
<a href="add.php">cancel</a>
</form>




<script type="text/javascript">
	$('[type="checkbox"]').bootstrapSwitch();
</script>





