<?php
require_once("header.php");
?>
<script type="text/javascript">
	
	$(document).ready(function() { 

		new Tablesort(document.getElementById('tablesort'), {	
				
		});

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
	showAttendTable($pro_id);
	echo '<button onclick="window.history.back()">返回</button>';
	die();
}

?>


<form action="show_at.php" method="post">
<span>Please choose course:</span>
<br>
<?php echoMyCourseSelect($_SESSION['tea_id']);?>
<input type="submit" name="add_submit">
</form>











