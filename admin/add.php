<?php
require_once("header.php");
?>

<form action="add_main.php" method="post">
<span>Please choose course:</span>
<br>
<?php echoMyCourseSelect($_SESSION['tea_id']);?>
<input type="submit" name="add_submit">
</form>


