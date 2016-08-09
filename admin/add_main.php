<?php
require_once("header.php");
dev_var_dump('post');
dev_var_dump('get');

if (isset($_POST["pro_id"])) {
	$pro_id=$_POST["pro_id"];
}elseif (isset($_GET["pro_id"])) {
	$pro_id=$_GET["pro_id"];
}else{
	die('please back');
}

$course_id=NULL;
$year=NULL;
$term=NULL;
$stu_grade=NULL;
$stu_major=NULL;


?>



<form action="add_result.php" method="post">
<?php 
$update_time=getNowTime();
echo '<input name="pro_id" value="'.$pro_id.'" style="display:none;" >';
echo '<input name="update_time" value="'.$update_time.'" style="display:none;" >';
?>
<?php addNewAt($pro_id); ?>
<input type="submit" name="add_data_submit" value="提交">
<button onclick="window.history.back()">取消</button>
<!-- <a href="add.php">cancel</a> -->
</form>




<script type="text/javascript">
	$('[type="checkbox"]').bootstrapSwitch();
</script>





