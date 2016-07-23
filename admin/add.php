<?php
require_once("header.php");
?>

<p>This is add.php</p>

<form action="add_main.php" method="post">
<p>Please choose:</p>

<p>Course</p>
<?php teacher_get_own_course($_SESSION['tea_id']);?>

<p>Date</p>
<?php require_once("datepicker.php");?>

<br>
<input type="submit" name="add_submit">

</form>