<?php
require_once("header.php");

var_dump($_POST);//dev

?>


<form action="add_result.php" method="post">

<?php 
echo '<input name="pro_id" value="'.$_POST["pro_id"].'" style="display:none;" >';
echo '<input name="date" value="'.$_POST["date"].'" style="display:none;" >';
?>

<?php generate_stu_list($_POST["pro_id"]); ?>

<input type="submit" name="add_data_submit">
</form>


<script type="text/javascript">
	$('[type="checkbox"]').bootstrapSwitch();
</script>





