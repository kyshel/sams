<?php
require_once("header.php");
?>
<script type="text/javascript">	
	$(document).ready(function() { 
		document.getElementById('tablesort').addEventListener('afterSort', function() {
			$('.danger').tooltip('show');
		});

		$('.danger').tooltip('show');

	});

</script>

<?php
$php_self=php_self();
$user_role=$_SESSION['user_role'];

if (isset($_POST["pro_id"])) {
	$pro_id=$_POST["pro_id"];
}elseif (isset($_GET["pro_id"])) {
	$pro_id=$_GET["pro_id"];
}else{
	$pro_id='not_set';
}


if ($pro_id != 'not_set') {
	echo '<div><a href="manage_pro.php">返回</a></div>';
	showAttendTable($pro_id);
	echo '<a href="manage_pro.php">返回</a>';
	die();
}
?>





<form action="show_at.php" method="post">
<span>Please choose course:</span>
<br>
<?php echoMyCourseSelect($_SESSION['tea_id']);?>
<input type="submit" name="add_submit">
</form>











