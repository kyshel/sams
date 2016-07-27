<?php
require_once("header.php");

//showGrid('attend','SELECT * from attend','at_id');

$php_self=php_self();
$user_role=$_SESSION['user_role'];


showAtDetail();




if($user_role == 'teacher'){

?>

<form action="show_at.php" method="post">
<p>Please choose:</p>
<p>Course</p>
<?php teacher_get_own_course($_SESSION['tea_id']);?>



<?php //require_once("datepicker.php");?>

<br>
<input type="submit" name="show_submit">
</form>


<?php
}elseif ($user_role == 'admin') {
	;
}


?>